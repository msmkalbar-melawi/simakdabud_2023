<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>
    
    
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
    <script type="text/javascript">
    
    var kode = '';
    var giat = '';
    var nomor= '';
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
    var ctk = '';
        
   
        $(function(){
        $('#tgl_ttd').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
        $('#ttd2').combogrid({  
            panelWidth:400,  
            idField:'nip',  
            textField:'nama',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka/load_ttd_gub',  
            columns:[[  
                {field:'nip',title:'NIP',width:200},  
                {field:'nama',title:'Nama',width:400}    
            ]]  
        });          
		
		
		
		
		
		});	

    function openWindow( url ){
        var  ctglttd = $('#tgl_ttd').datebox('getValue');
        var thn  = "<?php echo $this->session->userdata('pcThang'); ?>" 
        if ($('input[name="chkrancang"]:checked').val()=='1'){
            var chkrancang = '1';
            var $rancang = 'Rancangan';
        }else{
            var chkrancang = '0';
            var $rancang = '';
        } 
        var ttd_2 = $('#ttd2').combogrid('getValue');
        var ttd2 = ttd_2.split(" ").join("a");

        if (ttd2 == ''){ 
            alert("Penandatangan Tidak Boleh Kosong"); 
            return;
        }

        if (ctglttd==''){
            alert("Tanggal TTD tidak boleh kosong");
        } else {
            lc = '/'+chkrancang+'/Lamp II Ringkasan '+$rancang+' APBD MUPDO?tgl_ttd='+ctglttd+'&ttd2='+ttd2+'';
            window.open(url+lc,'_blank');
            window.focus();
        }
		  
     } 
	 
	 function alltrim(kata){
		b = (kata.split(' ').join(''));
		c = (b.replace( /\s/g, ""));
		return c	 
	 }
   </script>

	<tr>    
	</tr>
    
	<div id="content">      
    	<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp; 
       </h1>
 
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
            <tr>
                <td width="20%" style="border: none;">GUB / SEKDA</td>         
                <td width="1%" style="border: none;">:</td>            
                <td style="border: none;"><input type="text" id="ttd2" style="width: 200px;" /> 
                </td> 
            </tr>
            <tr>
                <td style="border: none;">TANGGAL TTD</td>
                <td style="border: none;">:</td>
                <td style="border: none;"><input type="text" id="tgl_ttd" style="width: 100px;" /> &nbsp;&nbsp;&nbsp; <input type="checkbox" name="chkrancang" id="chkrancang" value="1"/> Rancangan
                </td> 
    		</tr>

            <tr>                
                <td style="border: none;"></td>            	
                <td colspan="2" style="border: none;">&nbsp;&nbsp;&nbsp;
                    <a href="<?php echo site_url(); ?>/rka/preview_ringkasangg_urus/<?php echo '0'; ?>
                    "class="easyui-linkbutton" plain="true" onclick="javascript:openWindow(this.href);return false">
                    <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="preview"/></a>
                    <a href="<?php echo site_url(); ?>/rka/preview_ringkasangg_urus/<?php echo '1';?>
                    "class="easyui-linkbutton" plain="true" onclick="javascript:openWindow(this.href);return false">                    
                    <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
                </td>
            </tr>
        </table>
 
        <div class="clear"></div>
	</div>