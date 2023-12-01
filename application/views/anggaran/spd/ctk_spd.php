<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets\font-awesome\css\font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets\font-awesome\css\font-awesome.css">
<style>
    button[type=submit] {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        margin: 2px 2px;
        cursor: pointer;
    }

    button[type=primary] {
        background-color: #38a2ff;
        border: none;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        margin: 2px 2px;
        width: 100px;
        text-align: center;
        cursor: pointer;
    }

    button[type=pdf] {
        background-color: #ff9900;
        border: none;
        color: black;
        padding: 10px 20px;
        text-decoration: none;
        margin: 2px 2px;
        width: auto;
        cursor: pointer;
    }

    button[type=delete] {
        background-color: #ff2003;
        border: none;
        color: white;
        text-align: center;
        padding: 10px 20px;
        text-decoration: none;
        margin: 2px 2px;
        width: 100px;
        cursor: pointer;
    }
</style>

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
    var ctk = '';


    $(function() {
        $('#tgl_ttd').datebox({
            required: true,
            formatter: function(date) {
                var y = date.getFullYear();
                var m = date.getMonth() + 1;
                var d = date.getDate();
                return y + '-' + m + '-' + d;
            }
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


        $('#cunit').combogrid({
            panelWidth: 700,
            idField: 'kd_skpd',
            textField: 'kd_skpd',
            mode: 'remote',
            url: '<?php echo base_url(); ?>index.php/spd/skpduser',
            columns: [
                [{
                        field: 'kd_skpd',
                        title: 'Kode Unit',
                        width: 100
                    },
                    {
                        field: 'nm_skpd',
                        title: 'Nama Unit',
                        width: 700
                    }
                ]
            ],
            onSelect: function(rowIndex, rowData) {
                skpd = rowData.kd_skpd;
                $("#nmskpd").attr("value", rowData.nm_skpd);
                $(function() {
                    $('#ttd1').combogrid({
                        panelWidth: 500,
                        url: '<?php echo base_url(); ?>/index.php/spd/load_ttd_unit/' + skpd,
                        idField: 'nip',
                        textField: 'nama',
                        mode: 'remote',
                        fitColumns: true
                    });

                    $('#ttd2').combogrid({
                        panelWidth: 500,
                        url: '<?php echo base_url(); ?>/index.php/spd/load_ttd_kba/' + skpd,
                        idField: 'nip',
                        textField: 'nama',
                        mode: 'remote',
                        fitColumns: true
                    });


                });


            }
        });



        $(function() {
            $('#ttd2').combogrid({
                panelWidth: 400,
                idField: 'nip',
                textField: 'nama',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/rka/load_ttd_kba',
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




    });

    function cek($cetak, $jns) {
        var ckdskpd = $('#cunit').combogrid('getValue');
        if ($jns == 'skpd') {
            var ckdskpd = ckdskpd.substring(0, 17);
        }
        url = "<?php echo site_url(); ?>spd/preview_reg_spd/" + ckdskpd + '/' + $cetak + '/' + $jns + '/Register SPD/ ' + skpd;
        openWindow(url, $jns);
    }


    function openWindow(url, $jns) {
        var ckdskpd = $('#cunit').combogrid('getValue');
        var ctglttd = $('#tgl_ttd').datebox('getValue');
        var ckdunit = $('#cunit').combogrid('getValue');
        var ttd_2 = $('#ttd2').combogrid('getValue');
        var tgl1 = $('#tgl1').datebox('getValue');
        var tgl2 = $('#tgl2').datebox('getValue');
        var ttd2 = alltrim(ttd_2);
        if ($jns != 'all') {
            if (ckdunit == '') {
                alert("Kode Unit Tidak Boleh Kosong");
                return;
            }
        }

        lc = '?tgl_ttd=' + ctglttd + '&ttd2=' + ttd2 + '&tgl1=' + tgl1 + '&tgl2=' + tgl2 + '';
        window.open(url + lc, '_blank');
        window.focus();

    }

    function alltrim(kata) {
        //alert(kata);
        b = (kata.split(' ').join(''));
        c = (b.replace(/\s/g, ""));
        return c

    }
</script>

<div id="content">
    <h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp;
    </h1>

    <?php echo form_close(); ?>

    <?php if ($this->session->flashdata('notify') <> "") : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
    <?php endif; ?>


    <td colspan="4">
        <div id="div_bend">
            <table style="width:100%;" border="0">
                <td width="20%">Unit</td>
                <td width="1%">:</td>
                <td><input type="text" id="cunit" style="width: 200px;" />
                <td><input type="text" id="nmskpd" readonly="true" style="width: 505px;border:0" /></td>
    </td>
    </table>
</div>
</td>
</tr>

<tr>
    <td colspan="3">
        <div id="div_bend">
            <table style="width:100%;" border="0">
                <td width="20%">Tgl Periode</td>
                <td width="1%">:</td>
                <td><input type="text" id="tgl1" style="width: 100px;" />
                    s/d <input type="text" id="tgl2" style="width: 100px;" /></td>

            </table>
        </div>
    </td>
</tr>

<tr>
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

<tr>

    <td colspan="4">
        <div id="div_bend">
            <table style="width:100%;" border="0">
                <td width="20%">Penandatanganan</td>
                <td width="1%">:</td>
                <td><input type="text" id="ttd2" style="width: 200px;" />
                </td>
            </table>
        </div>
    </td>


</tr>

<tr>



    <table class="narrow">
        <tr>
            <td width="20%">
                <h3>Cetak</h3>
            </td>
            <td>
            </td>
        </tr>


        <tr>
            <td width="20%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - Unit</td>
            <td>
                <button type="primary" onclick="javascript:cek(0,'unit');return false"><i class="fa fa-television"></i> Layar</button>
                <button type="pdf" onclick="javascript:cek(1,'unit');return false"><i class="fa fa-file-pdf-o"></i> PDF</button>
                <button type="submit" onclick="javascript:cek(2,'unit');return false"><i class="fa fa-file-excel-o"></i> Excel</button>

                <!-- <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(0,'unit');return false" >
                <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="preview"/></a>
                <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(1,'unit');return false">                    
                <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
                <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(2,'unit');return false">                    
                <img src="<?php echo base_url(); ?>assets/images/icon/excel.jpg" width="25" height="23" title="cetak"/></a> -->

            </td>
        </tr>

        <tr>
            <td width="20%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - SKPD</td>
            <td>
                <button type="primary" onclick="javascript:cek(0,'skpd');return false"><i class="fa fa-television"></i> Layar</button>
                <button type="pdf" onclick="javascript:cek(1,'skpd');return false"><i class="fa fa-file-pdf-o"></i> PDF</button>
                <button type="submit" onclick="javascript:cek(2,'skpd');return false"><i class="fa fa-file-excel-o"></i> Excel</button>
                <!-- 
                <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(0,'skpd');return false" >
                <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="preview"/></a>
                <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(1,'skpd');return false">                    
                <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
                <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(2,'skpd');return false">                    
                <img src="<?php echo base_url(); ?>assets/images/icon/excel.jpg" width="25" height="23" title="cetak"/></a> -->
            </td>
        </tr>

        <tr>
            <td width="20%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Keseluruhan</td>
            <td>
                <button type="primary" onclick="javascript:cek(0,'all');return false"><i class="fa fa-television"></i> Layar</button>
                <button type="pdf" onclick="javascript:cek(1,'all');return false"><i class="fa fa-file-pdf-o"></i> PDF</button>
                <button type="submit" onclick="javascript:cek(2,'all');return false"><i class="fa fa-file-excel-o"></i> Excel</button>

                <!--  <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(0,'all');return false" >
                <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="preview"/></a>
                <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(1,'all');return false">                    
                <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
                <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(2,'all');return false">                    
                <img src="<?php echo base_url(); ?>assets/images/icon/excel.jpg" width="25" height="23" title="cetak"/></a> -->
            </td>
        </tr>
    </table>
    <div class="clear"></div>
    </div>