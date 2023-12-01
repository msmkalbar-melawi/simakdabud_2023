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


        $(document).ready(function() {
            get_skpd();
            cekskpd();

            $("#tertanda").hide();
            $("#tanggal_ttd").hide();


        });

        $(function() {
            //$('#sskpd').combogrid({  
            //      panelWidth:630,  
            //      idField:'kd_skpd',  
            //      textField:'kd_skpd',  
            //      mode:'remote',
            //      url:'<?php echo base_url(); ?>index.php/akuntansi/skpd',  
            //      columns:[[  
            //          {field:'kd_skpd',title:'Kode SKPD',width:100},  
            //          {field:'nm_skpd',title:'Nama SKPD',width:500}    
            //      ]],
            //      onSelect:function(rowIndex,rowData){
            //          kdskpd = rowData.kd_skpd;
            //          $("#nmskpd").attr("value",rowData.nm_skpd);
            //          $("#skpd").attr("value",rowData.kd_skpd);
            //          //validate_giat();
            //      }  
            //      }); 

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
        });

        $(function() {
            $('#ttd1').combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/tukd_blud/load_ttd/pa',
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
                ]
            });
        });

        $(function() {
            $('#ttd2').combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/tukd_blud/load_ttd/bp',
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
                ]
            });
        });


        function validate_pilihan() {
            var beban = document.getElementById('jns_ttd').value;
            if (beban == '1') {
                $("#tanggal_ttd").show();
                $("#tertanda").show();
            } else {
                $("#tertanda").hide();
                $("#tanggal_ttd").hide();
            }

        }

        function get_skpd() {

            $.ajax({
                url: '<?php echo base_url(); ?>index.php/rka_blud/config_skpd',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#sskpd").attr("value", data.kd_skpd);
                    $("#nmskpd").attr("value", data.nm_skpd);
                    $("#skpd").attr("value", data.kd_skpd);
                    kdskpd = data.kd_skpd;
                    vskpd = kdskpd.substring(8, 10);
                    if (vskpd == '00' || vskpd == '01' || vskpd == '22' || vskpd == '23' || vskpd == '24' || vskpd == '25' || vskpd == '26') {

                    } else {
                        document.getElementById("jnsctk").options[0] = null;
                    }
                }
            });
        }


        function ttd1() {
            var ckdskpd = $("#sskpd2").combogrid("getValue");
            $('#ttd1').combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/tukd_blud/load_ttd/pa',
                queryParams: ({
                    kdskpd: ckdskpd
                }),
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
                ]
            });
        }


        function ttd2() {
            var ckdskpd = $("#sskpd2").combogrid("getValue");
            $('#ttd2').combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/tukd_blud/load_ttd/bp',
                queryParams: ({
                    kdskpd: ckdskpd
                }),
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
                ]
            });
        };


        function cekskpd() {
            $('#sskpd2').combogrid({
                panelWidth: 700,
                idField: 'kd_skpd',
                textField: 'kd_skpd',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/tukd_blud/skpd__pend',
                //queryParams: ({kdskpd:kdskpd2}),
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
                    $("#sskpd2").attr("value", rowData.kd_skpd);
                    $("#nmskpd").attr("value", rowData.nm_skpd);
                    ttd1();
                    ttd2();
                }
            });
        }


        function cetak(jns) {
            var dcetak = $('#dcetak').datebox('getValue');
            var dcetak2 = $('#dcetak2').datebox('getValue');


            var skpd = kdskpd;
            var jnsctk = document.getElementById('jnsctkx').value;

            if (dcetak == '' || dcetak2 == '') {
                alert('Periode belum dipilih');
                return;
            }

            if (jns_ttd == '1') {
                lc = '?&tgl_ttd=' + ctglttd + '&ttd=' + ttd + '&ttd2=' + ttd2;
            } else {
                lc = '';
            }
            var url = "<?php echo site_url(); ?>/tukd_laporan/rekap_pajak";
            window.open(url + '/' + dcetak + '/' + dcetak2 + '/' + skpd + '/' + jnsctk + '/' + jns);
            window.focus();
        }
    </script>


    <STYLE TYPE="text/css">
        input.right {
            text-align: right;
        }

        #content {
            padding-top: 30px;
        }

        .form-table {
            width: 100%;
        }

        .form-table tr td:first-child {
            width: 20%;
            text-align: right;
            padding-right: 10px;
        }

        .form-table tr td:nth-child(2) {
            width: 2%;
        }


        .form-table tr td>select {
            min-width: 100px;
            margin: 0;
        }

        .form-table tr td>input[type="submit"] {
            min-width: 70px;
        }

        .btn {
            border: 2px;
            background: #069;
            color: white;

            border-radius: 10px;
            text-align: center;
        }

        .btn {
            text-decoration: none;
        }

        #kanan {
            float: right;
        }
    </STYLE>

</head>

<body>

    <div id="content">

        <div id="accordion">
            <fieldset>
                <h3>
                    <center><u><b>CETAK BKU PAJAK 2022</b></u></center>
                </h3>
                <div>
                    <p align="right">
                    <table id="sp2d" title="Rekap Belanja BLUD" style="width:870px;height:300px;">
                        <tr>
                            <td width="20%" height="40"><B>SKPD</B></td>
                            <td width="80%"><input id="sskpd" name="sskpd" style="width: 150px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 500px; border:0;" /></td>
                        </tr>


                        <tr>
                            <td width="20%" height="40"><B>Periode</B></td>
                            <td width="80%"><input id="dcetak" name="dcetak" type="text" style="width:155px" />&nbsp;&nbsp;s/d&nbsp;&nbsp;<input id="dcetak2" name="dcetak2" type="text" style="width:155px" /></td>
                        </tr>



                        <tr>
                            <td width="20%" height="40"><B>Jenis Cetakkan</B></td>
                            <td width="80%"><select name="jnsctkx" id="jnsctkx" style="height: 28px; width: 190px;" onchange="javascript:validate_pilihan();">
                                    <option value="2">SKPD</option>
                        </tr>
                        <tr id="pilihan">
                            <td width="20%" height="40"><B>Jenis Tanda Tangan</B></td>
                            <td width="80%"><select name="jns_ttd" id="jns_ttd" style="height: 28px; width: 190px;" onchange="javascript:validate_pilihan();">
                                    <option value="0">Tanpa Penandatangan</option>
                                    <option value="1">Dengan Penandatangan</option>
                            </td>
                        </tr>

                        <tr id="tanggal_ttd">
                            <td colspan="3">
                                <div id="div_bend">
                                    <table style="width:100%;" border="0">
                                        <td width="20%">TANGGAL TTD</td>
                                        <td width="1%">:</td>
                                        <td><input type="text" id="tgl_ttd" style="width: 100px;" />
                                        </td>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr id="tertanda">
                            <td colspan="4">
                                <div id="div_bend">
                                    <table style="width:100%;" border="0">
                                        <td width="20%">Pengguna Anggaran</td>
                                        <td width="1%">:</td>
                                        <td><select type="text" id="ttd1" style="width: 100px;" />
                                        </td>

                                        <td width="20%">Bendahara Penerimaan</td>
                                        <td width="1%">:</td>
                                        <td><input type="text" id="ttd2" style="width: 100px;" />
                                        </td>
                                    </table>
                                </div>
                            </td>
                        </tr>


                        <tr>
                            <td width="20%" height="40"></td>
                            <td>
                                <a class="easyui-linkbutton" iconCls="icon-print" plain="true" ONCLICK="cetak(0)" style="height:40px;width:100px">Cetak Layar
                                    <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" ONCLICK="cetak(1)" style="height:40px;width:100px">Cetak Excel
                                        <a class="easyui-linkbutton" iconCls="icon-word" plain="true" ONCLICK="cetak(2)" style="height:40px;width:100px">Cetak Word
                                            <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" ONCLICK="cetak(3)" style="height:40px;width:100px">Cetak PDF
                            </td>
                        </tr>


                    </table>
                    </p>

                </div>
        </div>
    </div>
    </fieldset>
</body>

</html>