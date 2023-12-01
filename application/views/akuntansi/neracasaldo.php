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
    </STYLE>

</head>

<body>

    <div id="content">

        <div id="accordion">

            <h3>CETAK NERACA SALDO</h3>
            <div>
                <p align="right">
                <table id="sp2d" title="Cetak Neraca Saldo" style="width:870px;height:300px;">
                    <tr>
                        <td width="20%" height="40"><B>SKPD</B></td>
                        <td width="80%"><input id="sskpd" name="sskpd" readonly="true" style="width: 150px;border: 0;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" readonly="true" style="width: 500px; border:0;" /></td>
                    </tr>
                    <tr>
                        <td width="20%" height="40"><B>PERIODE</B></td>
                        <td width="80%"><input id="dcetak" name="dcetak" type="text" style="width:155px" />&nbsp;&nbsp;s/d&nbsp;&nbsp;<input id="dcetak2" name="dcetak2" type="text" style="width:155px" /></td>
                    </tr>
                    <tr>
                        <td width="20%" height="40"><B>TANGGAL TTD</B></td>
                        <td width="80%"><input id="tglttd" name="tglttd" type="text" style="width:155px" /></td>
                    </tr>
                    <tr>
                        <td width="20%" height="40"><b>CETAK</>
                        </td>
                        <td width="80%">
                            <a class="button" ONCLICK="cetakbb(1)"><i class="fa fa-print"></i> Layar</a>
                            <a class="button" ONCLICK="cetakbb(2)"><i class="fa fa-pdf"></i> PDF</a>

                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                    </tr>
                </table>
                </p>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>


    <script type="text/javascript">
        var nip = '';
        var kdskpd = '';

        $(document).ready(function() {
            //get_skpd();                                                            
        });

        $(function() {
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

                }
            });
        });

        $(function() {

            $('#dcetak').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });

            $('#tglttd').datebox({
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

            $('#ttd').combogrid({
                panelWidth: 500,
                url: '<?php echo base_url(); ?>/index.php/tukd/list_ttd',
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


        /*function get_skpd()
            {
            
            	$.ajax({
            		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
            		type: "POST",
            		dataType:"json",                         
            		success:function(data){
            								$("#sskpd").attr("value",data.kd_skpd);
            								$("#nmskpd").attr("value",data.nm_skpd);
                                            $("#skpd").attr("value",data.kd_skpd);
            								kdskpd = data.kd_skpd;
                                                   
            							  }                                     
            	});  
            }*/



        function cetakbb($cetak) {
            var cetak = $cetak;
            var dcetak = $('#dcetak').datebox('getValue');
            var dcetak2 = $('#dcetak2').datebox('getValue');
            var tglttd = $('#tglttd').datebox('getValue');
            var skpd = kdskpd;

            var url = "<?php echo site_url(); ?>akuntansi/cetakneraca_saldo";
            window.open(url + '/' + dcetak + '/' + skpd + '/' + dcetak2 + '/' + cetak + '/' + tglttd, '_blank');
            window.focus();
        }
    </script>
</body>

</html>