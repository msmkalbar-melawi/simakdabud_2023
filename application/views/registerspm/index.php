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
    var ctk = '';
    var text = '';



    $(document).ready(function() {
        get_skpd();
    });

    $(function() {
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
    });


    function cetak() {
        $("#dialog-modal").dialog('close');
    }

    function get_skpd() {
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
                // $("#skpd").attr("value", data.kd_skpd);       
                $("#nmskpd").attr("value", rowData.nm_skpd);
            }
        });

    }

    function cetak($cetak) {
        var cetak = $cetak;
        var jns = $('#spmbatal').prop('checked');
        var ckdskpd = $('#skpd').combogrid('getValue');
        var ctgl1 = $('#tgl1').datebox('getValue');
        var ctgl2 = $('#tgl2').datebox('getValue');
        if(jns== true){
            url = "<?php echo site_url(); ?>registerspm/cetakregisterspmbatal/"+ ckdskpd + "/" + ctgl1 + "/" + ctgl2 + "/" + cetak;
        }else{
            url = "<?php echo site_url(); ?>registerspm/cetakregister/"+ ckdskpd + "/" + ctgl1 + "/" + ctgl2 + "/" + cetak;
        }
       
        if(ckdskpd ==''){
            alert('Pilih SPKD Terlebih dahulu');
            return;
        }else if(ctgl1 ==''){
            alert('Pilih Periode Terlebih dahulu');
            return;
        }else if(ctgl2 ==''){
            alert('Pilih Periode Terlebih dahulu');
            return;
        }else{
            window.open(url, '_blank');
            window.focus();
        }
        
    }
</script>


<div id="content" align="center" style="background: white">
    <h3 align="center"><b>CETAK REGISTER SPM</b></h3>
    <!--  <fieldset style="width: 70%;"> -->
    <table align="center" style="width:100%;" border="0">
        <tr>
        <tr>
            <td colspan="3">
            <input type="checkbox" name="spmbatal" id="spmbatal" value="1">SPM Batal<br>
            </td>
        </tr>
            <td colspan="3">
                    <table style="width:100%;" border="0">
                        <td width="20%">SKPD</td>
                        <td width="1%">:</td>
                        <td width="79%"><input id="skpd" name="skpd" style="width: 200px;" />&ensp;
                            <input type="text" id="nmskpd" readonly="true" style="width: 400px;border:0" />
                        </td>
                    </table>
            </td>
        </tr>
        <tr>
        <td width="20%">PERIODE</td>
        <td width="1%">:</td>
        <td width="79%"><input type="text" id="tgl1" style="width: 200px;" /> s.d. <input type="text" id="tgl2" style="width: 200px;" />
        </td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <button class="button-biru" plain="true" onclick="javascript:cetak(0);"><i class="fa fa-television"></i> Cetak Layar</button>
                <button class="button-kuning" plain="true" onclick="javascript:cetak(1);"><i class="fa fa-pdf"></i> Cetak PDF</button>
            </td>
        </tr>
    </table>
</div>