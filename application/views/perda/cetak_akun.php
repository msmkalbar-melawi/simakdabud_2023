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
    <STYLE TYPE="text/css">
        input.right {
            text-align: right;
        }
    </STYLE>
</head>

<body>

    <div id="content">
        <h3>LRA</h3>
        <div id="">
            <p align="center">
            <table id="sp2d" title="Cetak Perda Lamp. 1" width="100%" border="0">
                <!-- <tr>
                    <td colspan="2"><input type="radio" name="status" value="1" onclick="opt(this.value)" /><b>Keseluruhan</b></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="radio" name="status" value="2" id="status" onclick="opt(this.value)" /><b>Per Unit</b>
                        <div id="kode_skpd">
                            <table style="width:100%;" border="0">
                                <tr>
                                    <td width="22px" height="40%"><B>Unit&nbsp;&nbsp;</B></td>
                                    <td width="900px"><input id="sskpd" name="sskpd" style="width: 100px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 670px; border:0;" /></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr> -->
                <tr>
                    <td>
                        <input type="radio" name="cetak" value="bulan" onclick="optbulan(this.value)" />Bulan
                        <input type="radio" name="cetak" value="periode" onclick="optbulan(this.value)" />Periode
                    </td>
                </tr>

                <tr id="bulanbulan">
                    <td width="20%"> Bulan</td>
                    <td width="70%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">
                        <select class="select" style="width: 200px" name="bulan" id="bulan">
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April </option>
                            <option value="5">Mei </option>
                            <option value="6">Juni </option>
                            <option value="7">Juli </option>
                            <option value="8">Agustus </option>
                            <option value="9">September </option>
                            <option value="10">Oktober </option>
                            <option value="11">Nopember </option>
                            <option value="12">Desember </option>
                        </select>
                    </td>
                </tr>

                <tr id="div_periode">
                    <td width="20%">Periode</td>
                    <td><input type="text" id="tgl1" style="width: 200px;" /> s.d. <input type="text" id="tgl2" style="width: 200px;" />
                    </td>
                </tr>

                <tr>
                    <td>Anggaran</td>
                    <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">
                        <input type="hidden" name="jenis" id="jenis" value="1" />
                        <select class="select" style="width: 200px" name="anggaran" id="anggaran">
                            <option value="M">Penetapan</option>
                            <option value="P1">Penyempurnaan I</option>
                            <option value="P2">Penyempurnaan II</option>
                            <option value="P3">Penyempurnaan III</option>
                            <option value="P4">Penyempurnaan IV</option>
                            <option value="P4">Penyempurnaan V</option>
                            <option value="U1">Perubahan I</option>
                            <option value="U2">Perubahan II</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="15%">TTD</td>
                    <td>
                        &nbsp;<input type="text" id="ttdx1" style="width: 200px;" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <input id="qttd2" name="qttd2" type="checkbox" value="2" onclick="javascript:runEffect2();" checked /> Tanpa TTD
                        <input id="qttd" name="qttd" type="checkbox" value="1" onclick="javascript:runEffect();" /> Dengan TTD
                    </td>
                    <td>
                        <input type="text" id="tgl_ttd1" style="width: 200px;" />
                    </td>
                </tr>
                <tr>
                    <td>Label Audited</td>
                    <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">
                        <select class="select" style="width: 200px;" name="label" id="label">
                            <option value="1">Unaudited</option>
                            <option value="2">Audited</option>
                            <option value="0">Kosong</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>PERMEN 90</td>
                    <td align="left">
                        <a class="button" onclick="javascript:cetak6(0);"><i class="fa fa-print"></i> Layar</a>
                        <a class="button" onclick="javascript:cetak6(1);"><i class="fa fa-pdf"></i> PDF</a>
                        <a class="button" onclick="javascript:cetak6(2);"><i class="fa fa-excel"></i> Excel</a>
                    </td>
                </tr>
                <tr>
                    <td>PERMEN 90 OBJEK</td>
                    <td align="left">
                        <a class="button" onclick="javascript:cetak_lra90_objek(0);"><i class="fa fa-print"></i> Layar</a>
                        <a class="button" onclick="javascript:cetak_lra90_objek(1);"><i class="fa fa-pdf"></i> PDF</a>
                        <a class="button" onclick="javascript:cetak_lra90_objek(2);"><i class="fa fa-excel"></i> Excel</a>
                    </td>
                </tr>
                <tr>
                    <td>PERMEN 90 SUB RO</td>
                    <td align="left">
                        <a class="button" onclick="javascript:cetak_90_sub_ro(0);"><i class="fa fa-print"></i> Layar</a>
                        <a class="button" onclick="javascript:cetak_90_sub_ro(1);"><i class="fa fa-pdf"></i> PDF</a>
                        <a class="button" onclick="javascript:cetak_90_sub_ro(2);"><i class="fa fa-excel"></i> Excel</a>
                    </td>
                </tr>
                <tr hidden>
                    <td>Laporan Realisasi Output Penggunaan APBD</td>
                    <td align="left">
                        <a class="button" onclick="javascript:cetak6(0);"><i class="fa fa-print"></i> Layar</a>
                        <a class="button" onclick="javascript:cetak6(1);"><i class="fa fa-pdf"></i> PDF</a>
                        <a class="button" onclick="javascript:cetak6(2);"><i class="fa fa-excel"></i> Excel</a>
                    </td>
                </tr>
                <tr hidden>
                    <td>PERMEN DJPK</td>
                    <td align="left">
                        <a class="button" onclick="javascript:cetak6_djpk(0);"><i class="fa fa-print"></i> Layar</a>
                        <a class="button" onclick="javascript:cetak6_djpk(1);"><i class="fa fa-pdf"></i> PDF</a>
                        <a class="button" onclick="javascript:cetak6_djpk(2);"><i class="fa fa-excel"></i> Excel</a>
                    </td>
                </tr>
                <tr hidden>
                    <td>PERMEN 90 RO (Gabungan)</td>
                    <td align="left">
                        <a class="button" onclick="javascript:cetak_lra_pemkot_90_ro_gabungan(0);"><i class="fa fa-print"></i> Layar</a>
                        <a class="button" onclick="javascript:cetak_lra_pemkot_90_ro_gabungan(1);"><i class="fa fa-pdf"></i> PDF</a>
                        <a class="button" onclick="javascript:cetak_lra_pemkot_90_ro_gabungan(2);"><i class="fa fa-excel"></i> Excel</a>
                    </td>
                </tr>
                <tr hidden>
                    <td>PERMEN 13</td>
                    <td align="left">
                        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak2(0);">Layar</a>
                        <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak2(1);">PDF</a>
                        <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak2(2);">excel</a>
                    </td>
                </tr>
                <tr hidden>
                    <td>PERMEN 64</td>
                    <td align="left">
                        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak6_64(0);">Layar</a>
                        <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak6_64(1);">PDF</a>
                        <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak6_64(2);">excel</a>
                    </td>
                </tr>
                <tr hidden>
                    <td>PERMEN 64 OBJEK</td>
                    <td align="left">
                        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak6_64_4digit(0);">Layar</a>
                        <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak6_64_4digit(1);">PDF</a>
                        <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak6_64_4digit(2);">excel</a>
                    </td>
                </tr>
                <tr hidden>
                    <td>SAP 64</td>
                    <td align="left">
                        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak3(0);">Layar</a>
                        <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak3(1);">PDF</a>
                        <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak3(2);">excel</a>
                    </td>
                </tr>
                <tr hidden>
                    <td>LRA 33 RO</td>
                    <td align="left">
                        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak4(0);">Layar</a>
                        <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak4(1);">PDF</a>
                        <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak4(2);">excel</a>
                    </td>
                </tr>
                <tr>
                <tr hidden>
                    <td>LRA 64 RO</td>
                    <td align="left">
                        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak51(0);">Layar</a>
                        <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak51(1);">PDF</a>
                        <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak51(2);">excel</a>
                    </td>
                </tr>
                <!-- <tr>
                    <td>LRA 13 APBD</td>
                    <td align="left">
                        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak2_apbd(0);">Layar</a>
                        <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak2_apbd(1);">PDF</a>
                        <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak2_apbd(2);">excel</a>
                    </td>
                </tr> -->
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
        var kode = '';
        var pilihttd = '';
        var ctkbulan = '';

        $(document).ready(function() {

            $('#bulanbulan').hide();
            $('#div_periode').hide();
            $("#accordion").accordion();
            $("#dialog-modal").dialog({
                height: 100,
                width: 922
            });

            $('#tgl1').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });
            $('#tgl2').datebox({
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

            $('#sskpd').combogrid({
                panelWidth: 630,
                idField: 'kd_skpd',
                textField: 'kd_skpd',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/akuntansi/skpd_new',
                columns: [
                    [{
                            field: 'kd_skpd',
                            title: 'Kode SKPD',
                            width: 100
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

            $('#ttdx1').combogrid({
                panelWidth: 180,
                url: '<?php echo base_url(); ?>/index.php/lamp_perda/load_ttd_perda',
                idField: 'nip',
                textField: 'nama',
                mode: 'remote',
                fitColumns: true,
                columns: [
                    [{
                        field: 'nama',
                        title: 'NAMA',
                        align: 'left',
                        width: 170
                    }]
                ],
                onSelect: function(rowIndex, rowData) {
                    nip = rowData.nip;
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

            $("#kode_skpd").hide();

        });

        //cdate = '<?php echo date("Y-m-d"); ?>';

        function opt(val) {
            ctk = val;
            if (ctk == '1') {
                $("#kode_skpd").hide();
            } else {
                $("#kode_skpd").show();
            }
        }

        function optbulan(val) {
            ctkbulan = val;
            if (ctkbulan == 'bulan') {
                $("#bulanbulan").show();
                $("#div_periode").hide();
            } else if (ctkbulan == 'periode') {
                $("#bulanbulan").hide();
                $("#div_periode").show();
            }
        }

        function runEffect() {
            $('#qttd2')._propAttr('checked', false);
            pilihttd = "1";
        };

        function runEffect2() {
            $('#qttd')._propAttr('checked', false);
            pilihttd = "2";
        };

        function cetak2(pilih) {
            var initx = pilihttd;
            var ctglttd = $('#tgl_ttd1').datebox('getValue');
            var ttdperdax = $('#ttdx1').datebox('getValue');
            var ttdperda = ttdperdax.split(" ").join("n");
            //alert(ttdperda);
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            var jenis = document.getElementById("jenis").value;
            var skpd = '-';
            var ctk = '1';
            var label = document.getElementById("label").value;

            if (ctk == '2') {
                var skpd = kdskpd;
            }

            if (ttdperda == '') {
                ttdperda = '-'
            }

            if (ctglttd == '') {
                ctglttd = '-'
            }

            //alert(ttdperda);
            var url = "<?php echo site_url(); ?>perda/cetak_perda_lampI_permen_spj_akun";
            window.open(url + '/' + bulan + '/' + pilih + '/' + anggaran + '/' + jenis + '/' + skpd + '/' + initx + '/' + ctglttd + '/' + ttdperda + '/' + label, '_blank');
            window.focus();
        }

        function cetak2_apbd(pilih) {
            var initx = pilihttd;
            var ctglttd = $('#tgl_ttd1').datebox('getValue');
            var ttdperdax = $('#ttdx1').datebox('getValue');
            var ttdperda = ttdperdax.split(" ").join("n");
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            var jenis = document.getElementById("jenis").value;
            var skpd = '-';
            var ctk = '1';
            var label = document.getElementById("label").value;

            if (ctk == '2') {
                var skpd = kdskpd;
            }

            if (ttdperda == '') {
                ttdperda = '-'
            }

            if (ctglttd == '') {
                ctglttd = '-'
            }

            //alert(ttdperda);
            var url = "<?php echo site_url(); ?>perda/cetak_perda_lampI_permen_spj_akun_apbd";
            window.open(url + '/' + bulan + '/' + pilih + '/' + anggaran + '/' + jenis + '/' + skpd + '/' + initx + '/' + ctglttd + '/' + ttdperda + '/' + label, '_blank');
            window.focus();
        }

        function cetak3(pilih) {
            var initx = pilihttd;
            var ctglttd = $('#tgl_ttd1').datebox('getValue');
            var ttdperdax = $('#ttdx1').datebox('getValue');
            var ttdperda = ttdperdax.split(" ").join("n");
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            var label = document.getElementById("label").value;
            var skpd = '-';
            var url = "<?php echo site_url(); ?>perda/cetak_lra_pemkot_64_akun";
            window.open(url + '/' + bulan + '/' + pilih + '/' + anggaran + '/-/' + skpd + '/' + initx + '/' + ctglttd + '/' + ttdperda + '/' + label, '_blank');
            window.focus();
        }

        function cetak4(pilih) {
            var initx = pilihttd;
            var ctglttd = $('#tgl_ttd1').datebox('getValue');
            var ttdperdax = $('#ttdx1').datebox('getValue');
            var ttdperda = ttdperdax.split(" ").join("n");
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            var label = document.getElementById("label").value;
            var skpd = '-';
            var url = "<?php echo site_url(); ?>perda_33/cetak_lra_pemkot_33_ro";
            window.open(url + '/' + bulan + '/' + pilih + '/' + anggaran + '/-/' + skpd + '/' + initx + '/' + ctglttd + '/' + ttdperda + '/' + label, '_blank');
            window.focus();
        }

        function cetak5(pilih) {
            var initx = pilihttd;
            var ctglttd = $('#tgl_ttd1').datebox('getValue');
            var ttdperdax = $('#ttdx1').datebox('getValue');
            var ttdperda = ttdperdax.split(" ").join("n");
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            var label = document.getElementById("label").value;
            var skpd = '-';
            var url = "<?php echo site_url(); ?>perda_64/cetak_lra_pemkot_64_ro";
            window.open(url + '/' + bulan + '/' + pilih + '/' + anggaran + '/-/' + skpd + '/' + initx + '/' + ctglttd + '/' + ttdperda + '/' + label, '_blank');
            window.focus();
        }

        function cetak51(pilih) {
            var initx = pilihttd;
            var ctglttd = $('#tgl_ttd1').datebox('getValue');
            var ttdperdax = $('#ttdx1').datebox('getValue');
            var ttdperda = ttdperdax.split(" ").join("n");
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            var label = document.getElementById("label").value;
            var skpd = '-';
            var url = "<?php echo site_url(); ?>akuntansi/cetak_perda_64_ro";
            window.open(url + '/' + bulan + '/' + pilih + '/' + anggaran + '/-/' + skpd + '/' + initx + '/' + ctglttd + '/' + ttdperda + '/' + label, '_blank');
            window.focus();
        }

        function cetak6(pilih) {
            var initx = pilihttd;
            var ctglttd = $('#tgl_ttd1').datebox('getValue');
            var ttdperdax = $('#ttdx1').datebox('getValue');
            var ttdperda = ttdperdax.split(" ").join("n");
            var tgl1 = $('#tgl1').datebox('getValue');
            var tgl2 = $('#tgl2').datebox('getValue');
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            var label = document.getElementById("label").value;
            var skpd = '-';
            var cetakbulan = ctkbulan;
            if (cetakbulan == '') {
                swal("Error", "Pilih Bulan atau per periode", "warning");
                return;
            }
            if (initx == '') {
                swal("Error", "Pilih Dengan Tandatangan atau tidak", "warning");
                return;
            }
            if (cetakbulan == 'bulan') {
                var url = "<?php echo site_url(); ?>perda/cetak_lra_pemkot_33_permen";
                window.open(url + '/' + bulan + '/' + pilih + '/' + anggaran + '/-/' + skpd + '/' + initx + '/' + ctglttd + '/' + ttdperda + '/' + label, '_blank');
            } else if (cetakbulan == 'periode') {
                var url = "<?php echo site_url(); ?>perda/cetak_lra_pemkot_33_permen_periode";
                window.open(url + '/' + tgl1 + '/' + tgl2 + '/' + pilih + '/' + anggaran + '/-/' + skpd + '/' + initx + '/' + ctglttd + '/' + ttdperda + '/' + label, '_blank');
            }
            window.focus();

        }

        function cetak_90_sub_ro(pilih) {
            var initx = pilihttd;
            var ctglttd = $('#tgl_ttd1').datebox('getValue');
            var ttdperdax = $('#ttdx1').datebox('getValue');
            var ttdperda = ttdperdax.split(" ").join("n");
            var tgl1 = $('#tgl1').datebox('getValue');
            var tgl2 = $('#tgl2').datebox('getValue');
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            var label = document.getElementById("label").value;
            var skpd = '-';
            var cetakbulan = ctkbulan;
            if (cetakbulan == '') {
                swal("Error", "Pilih Bulan atau per periode", "warning");
                return;
            }
            if (initx == '') {
                swal("Error", "Pilih Dengan Tandatangan atau tidak", "warning");
                return;
            }
            if (cetakbulan == 'bulan') {
                var url = "<?php echo site_url(); ?>perda/cetak_lra_pemkot_90_sub_ro";
                window.open(url + '/' + bulan + '/' + pilih + '/' + anggaran + '/-/' + skpd + '/' + initx + '/' + ctglttd + '/' + ttdperda + '/' + label, '_blank');
            } else if (cetakbulan == 'periode') {
                var url = "<?php echo site_url(); ?>perda/cetak_lra_pemkot_90_sub_ro_periode";
                window.open(url + '/' + tgl1 + '/' + tgl2 + '/' + pilih + '/' + anggaran + '/-/' + skpd + '/' + initx + '/' + ctglttd + '/' + ttdperda + '/' + label, '_blank');
            }
            window.focus();
        }

        function cetak_lra90_objek(pilih) {
            var initx = pilihttd;
            var ctglttd = $('#tgl_ttd1').datebox('getValue');
            var ttdperdax = $('#ttdx1').datebox('getValue');
            var ttdperda = ttdperdax.split(" ").join("n");
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            var label = document.getElementById("label").value;
            var skpd = '-';
            var tgl1 = $('#tgl1').datebox('getValue');
            var tgl2 = $('#tgl2').datebox('getValue');
            var cetakbulan = ctkbulan;
            if (cetakbulan == '') {
                swal("Error", "Pilih Bulan atau per periode", "warning");
                return;
            }
            if (initx == '') {
                swal("Error", "Pilih Dengan Tandatangan atau tidak", "warning");
                return;
            }
            if (cetakbulan == 'bulan') {
                var url = "<?php echo site_url(); ?>perda/cetak_lra90_objek";
                window.open(url + '/' + bulan + '/' + pilih + '/' + anggaran + '/-/' + skpd + '/' + initx + '/' + ctglttd + '/' + ttdperda + '/' + label, '_blank');
            } else if (cetakbulan == 'periode') {
                var url = "<?php echo site_url(); ?>perda/cetak_lra90_objek_periode";
                window.open(url + '/' + tgl1 + '/' + tgl2 + '/' + pilih + '/' + anggaran + '/-/' + skpd + '/' + initx + '/' + ctglttd + '/' + ttdperda + '/' + label, '_blank');
            }
            window.focus();
        }

        function realisasi_output(pilih) {
            var initx = pilihttd;
            var ctglttd = $('#tgl_ttd1').datebox('getValue');
            var ttdperdax = $('#ttdx1').datebox('getValue');
            var ttdperda = ttdperdax.split(" ").join("n");
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            var label = document.getElementById("label").value;
            var skpd = '-';
            var url = "<?php echo site_url(); ?>perda/realisasi_output";
            window.open(url + '/' + bulan + '/' + pilih + '/' + anggaran + '/-/' + skpd + '/' + initx + '/' + ctglttd + '/' + ttdperda + '/' + label, '_blank');
            window.focus();
        }

        function cetak6_djpk(pilih) {
            var initx = pilihttd;
            var ctglttd = $('#tgl_ttd1').datebox('getValue');
            var ttdperdax = $('#ttdx1').datebox('getValue');
            var ttdperda = ttdperdax.split(" ").join("n");
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            var label = document.getElementById("label").value;
            var skpd = '-';
            var url = "<?php echo site_url(); ?>perda/cetak_lra_djpk";
            window.open(url + '/' + bulan + '/' + pilih + '/' + anggaran + '/-/' + skpd + '/' + initx + '/' + ctglttd + '/' + ttdperda + '/' + label, '_blank');
            window.focus();
        }

        function cetak_lra_pemkot_90_ro_gabungan(pilih) {
            var initx = pilihttd;
            var ctglttd = $('#tgl_ttd1').datebox('getValue');
            var ttdperdax = $('#ttdx1').datebox('getValue');
            var ttdperda = ttdperdax.split(" ").join("n");
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            var label = document.getElementById("label").value;
            var skpd = '-';
            var tgl1 = $('#tgl1').datebox('getValue');
            var tgl2 = $('#tgl2').datebox('getValue');
            var cetakbulan = ctkbulan;
            if (cetakbulan == '') {
                alert("Pilih Bulan atau per periode");
                return;
            }
            if (initx == '') {
                alert("Pilih Dengan Tandatangan atau tidak");
                return;
            }
            if (cetakbulan == 'bulan') {
                var url = "<?php echo site_url(); ?>perda/cetak_lra_pemkot_90_ro_gabungan";
                window.open(url + '/' + bulan + '/' + pilih + '/' + anggaran + '/-/' + skpd + '/' + initx + '/' + ctglttd + '/' + ttdperda + '/' + label, '_blank');
            } else if (cetakbulan == 'periode') {
                var url = "<?php echo site_url(); ?>perda/cetak_lra_pemkot_90_ro_gabungan_periode";
                window.open(url + '/' + bulan + '/' + pilih + '/' + anggaran + '/-/' + skpd + '/' + initx + '/' + ctglttd + '/' + ttdperda + '/' + label, '_blank');
            }
            window.focus();
        }

        function cetak6_64(pilih) {
            var initx = pilihttd;
            var ctglttd = $('#tgl_ttd1').datebox('getValue');
            var ttdperdax = $('#ttdx1').datebox('getValue');
            var ttdperda = ttdperdax.split(" ").join("n");
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            var label = document.getElementById("label").value;
            var skpd = '-';
            var url = "<?php echo site_url(); ?>perda/cetak_lra_pemkot_64_permen";
            window.open(url + '/' + bulan + '/' + pilih + '/' + anggaran + '/-/' + skpd + '/' + initx + '/' + ctglttd + '/' + ttdperda + '/' + label, '_blank');
            window.focus();
        }

        function cetak6_64_4digit(pilih) {
            var initx = pilihttd;
            var ctglttd = $('#tgl_ttd1').datebox('getValue');
            var ttdperdax = $('#ttdx1').datebox('getValue');
            var ttdperda = ttdperdax.split(" ").join("n");
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            var label = document.getElementById("label").value;
            var skpd = '-';
            var url = "<?php echo site_url(); ?>perda/cetak_lra_pemkot_64_akun_4";
            window.open(url + '/' + bulan + '/' + pilih + '/' + anggaran + '/-/' + skpd + '/' + initx + '/' + ctglttd + '/' + ttdperda + '/' + label, '_blank');
            window.focus();
        }
    </script>
</body>

</html>