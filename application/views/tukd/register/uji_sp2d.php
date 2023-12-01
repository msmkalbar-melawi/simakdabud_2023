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


    $(document).ready(function(){
        $('#periode1').datebox({
            required: true,
            formatter: function(date) {
                var y = date.getFullYear();
                var m = date.getMonth() + 1;
                var d = date.getDate();
                return y + '-' + m + '-' + d;
            }
        });
        $('#periode2').datebox({
            required: true,
            formatter: function(date) {
                var y = date.getFullYear();
                var m = date.getMonth() + 1;
                var d = date.getDate();
                return y + '-' + m + '-' + d;
            }
        });
    });

    function cetak($ctk) {
        var periode1 = $('#periode1').datebox('getValue');
        var periode2 = $('#periode2').datebox('getValue');
    
        //   let lemparan = '?jnscetak='+ctk+'&periode1='+periode1+'&dan'+'&periode2='+periode2;
        //   let url = "<?php echo site_url(); ?>index.php/Ujisp2d/cetakujisp2d" + lemparan;
        //   window.open(url,'_blank');
        //   window.send();
        urll = "<?php echo base_url(); ?>index.php/Ujisp2d/cetakujisp2d";
        window.open(urll + '?periode1=' + periode1 + '&periode2=' + periode2 + '&ctk=' + ctk, '_blank');
        window.focus();

    }
</script>


<div id="content" align="center" style="background: white">
    <h3 align="center"><b>CETAK LAPORAN UJI SP2D</b></h3>
    <!--  <fieldset style="width: 70%;"> -->
    <table align="center" style="width:100%;" border="0">
        <tr>
            <td colspan="3">
                <div id="div_periode">
                    <table style="width:100%;" border="0">
                        <td width="20%"><b>PERIODE</b></td>
                        <td width="1%">:</td>
                        <td width="79%"><input type="text" id="periode1" style="width: 200px;" /> s.d. <input type="text" id="periode2" style="width: 200px;" />
                        </td>
                    </table>
                </div>
            </td>
        </tr>
        <!-- <tr>
            <td colspan="3">
                <div id="div_bend">
                    <table style="width:100%;" border="0">
                        <td width="20%">TANGGAL TTD</td>
                        <td width="1%">:</td>
                        <td><input type="text" id="tgl_ttd" style="width: 200px;" />
                        </td>
                    </table>
                </div>
            </td>
        </tr> -->
       
        <tr>
            <td colspan="3" align="center">
                <button class="button-biru" plain="true" onclick="javascript:cetak(0);"><i class="fa fa-television"></i> Layar </button>
                <button class="button-kuning" plain="true" onclick="javascript:cetak(1);"><i class="fa fa-pdf"></i> PDF </button>
            </td>
        </tr>
    </table>
</div>