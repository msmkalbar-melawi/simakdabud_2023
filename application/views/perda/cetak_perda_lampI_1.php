<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css" />

    <STYLE TYPE="text/css">
        input.right {
            text-align: right;
        }

        fieldset {
            border-radius: 10px;
        }
    </STYLE>

</head>

<body>

    <div id="content">
        <fieldset>
            <legend>
                <h3>CETAK LAMP 1.1 </h3>
            </legend>
            <div id="accordion">
                <p align="right">
                <table id="sp2d" title="Cetak Perda Lamp. 1" style="width:100%;">
                    <tr>
                        <td width="20%"> Periode</td>
                        <td width="70%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><select name="bulan" id="bulan">
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
                            </select></td>
                    </tr>
                    <tr>
                        <td> Anggaran</td>
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">
                            <input type="hidden" name="jenis" id="jenis" value="1" />
                            <select name="anggaran" id="anggaran">
                                <option value="M">Penetapan</option>
                                <option value="P1">Penyempurnaan I</option>
                                <option value="P2">Penyempurnaan II</option>
                                <option value="P3">Penyempurnaan III</option>
                                <option value="P4">Penyempurnaan IV</option>
                                <option value="P5">Penyempurnaan V</option>
                                <option value="U1">Perubahan I</option>
                                <option value="U2">Perubahan II</option>
                            </select>
                        </td>
                    </tr>

                    <!-- <tr>
                        <td>Tanggal TTD</td>
                        <td><input type="text" id="tgl_ttd" style="width: 150px;" /> </td>
                    </tr> -->

                    <tr>
                        <td colspan="2">
                            <input id="qttd2" name="qttd2" type="checkbox" value="2" onclick="javascript:runEffect2();" /> Tanpa TTD
                            <input id="qttd" name="qttd" type="checkbox" value="1" onclick="javascript:runEffect();" /> Dengan TTD &nbsp;&nbsp;
                            <input type="text" id="tgl_ttd" style="width: 100px;" />
                        </td>
                    </tr>

                    <!-- <tr>
                        <td>Penandatanganan</td>
                        <td><input type="text" id="ttd" style="width: 200px;" /> </td>
                    </tr> -->
                    <tr>
                        <td colspan="2" width="80%"><b>Cetak Lampiran</b></td>
                    </tr>
                    </tr>
                    <tr>
                        <td colspan="2" width="80%">
                            <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak(0);">Cetak Layar PERWA</a>
                            <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak(1);">Cetak Pdf </a>
                            <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak(2);">Cetak excel </a>
                            <a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:cetak(3);">Cetak word </a>
                        </td>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" width="80%">
                            <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak2(0);">Cetak Layar PERDA</a>
                            <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak2(1);">Cetak Pdf </a>
                            <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak2(2);">Cetak excel </a>
                            <a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:cetak2(3);">Cetak word </a>
                        </td>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" width="80%"><b>Cetak Rekap</b></td>
                    </tr>
                    <tr>
                        <td colspan="2" width="80%">
                            <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetakrekap(0);">Cetak Layar</a>
                            <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetakrekap(1);">Cetak PDF</a>
                            <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetakrekap(2);">Cetak excel</a>
                            <a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:cetakrekap(3);">Cetak word</a>
                        </td>
                    </tr>
                </table>
                </p>
            </div>
        </fieldset>
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

        $(document).ready(function() {
            $("#accordion").accordion();
            $("#dialog-modal").dialog({
                height: 100,
                width: 922
            });

            $('#rek3').combogrid({
                panelWidth: 630,
                idField: 'kd_rek3',
                textField: 'kd_rek3',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/tukd/load_rek3_lamp_aset/',
                columns: [
                    [{
                            field: 'kd_rek3',
                            title: 'Kode Rekening',
                            width: 100
                        },
                        {
                            field: 'nm_rek3',
                            title: 'Nama Rekening',
                            width: 500
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    rekening = rowData.kd_rek3;
                    //$("#kdrek5").attr("value",rowData.kd_rek5);
                    $("#nmrek3").attr("value", rowData.nm_rek3);
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

        $(function() {

        });

        $(function() {
            //$("#status").attr("option",false);

        });
        $(function() {


            /*$('#ttd').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/lamp_perda/load_ttd_pa',  
                    idField:'nip',                    
                    textField:'nip',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'nip',title:'NIP',width:60},  
                        {field:'nama',title:'NAMA',align:'left',width:100}  
                    ]],
                    onSelect:function(rowIndex,rowData){
                    nip = rowData.nip;  
                    }   
                });*/
        });

        //cdate = '<?php echo date("Y-m-d"); ?>';
        function validate_ttd() {
            $(function() {
                $('#ttd').combogrid({
                    panelWidth: 500,
                    url: '<?php echo base_url(); ?>/index.php/tukd/pilih_ttd/' + kdskpd,
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
            });
        }


        function runEffect() {
            $('#qttd2')._propAttr('checked', false);
            pilihttd = "1";
        };

        function runEffect2() {
            $('#qttd')._propAttr('checked', false);
            pilihttd = "2";
        };

        function cetak(ctk) {
            var initx = pilihttd;
            var skpd = kdskpd;
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            //var ttd1 = $('#ttd').combogrid('getValue');     
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            if (ctglttd == '') {
                ctglttd = "-";
            }

            var url = "<?php echo site_url(); ?>perda/cetak_perda_lampI_1_233_perwa";
            window.open(url + '/' + bulan + '/' + anggaran + '/' + ctk + '/' + ctglttd + '/' + initx, '_blank');
            window.focus();
        }

        function cetak2(ctk) {
            var initx = pilihttd;
            var skpd = kdskpd;
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            //var ttd1 = $('#ttd').combogrid('getValue');     
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            if (ctglttd == '') {
                ctglttd = "-";
            }

            var url = "<?php echo site_url(); ?>perda/cetak_perda_lampI_1_233_perda";
            window.open(url + '/' + bulan + '/' + anggaran + '/' + ctk + '/' + ctglttd + '/' + initx, '_blank');
            window.focus();
        }

        function cetakrekap(ctk) {
            var initx = pilihttd;
            var skpd = kdskpd;
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            //var ttd1 = $('#ttd').combogrid('getValue');     
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            if (ctglttd == '') {
                ctglttd = "-";
            }

            var url = "<?php echo site_url(); ?>perda/cetak_perda_lampI_1_2_rekap";
            window.open(url + '/' + bulan + '/' + anggaran + '/' + ctk + '/' + ctglttd + '/' + initx, '_blank');
            window.focus();
        }
    </script>
</body>

</html>