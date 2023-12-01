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

    $(function(){
       $('#dg_cunit').edatagrid({
			url           : '<?php echo base_url(); ?>/index.php/rka/skpd_trdrka',
             idField      : 'id',
             toolbar      : "#toolbar",              
             rownumbers   : "true", 
             fitColumns   : "true",
             singleSelect : "true",
		 	onSelect:function(rowIndex,rowData){							
				},
			columns:[[
                {field:'id',
				 title:'id',
				 width:10,
                 hidden:true
				},
				{field:'kd_skpd',
				 title:'Rekening',
				 width:12,
				 align:'left'	
				}
			]]
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
    })
   
    function alltrim(kata){
        b = (kata.split(' ').join(''));
        c = (b.replace( /\s/g, ""));
        return c
    }
	
    function cek($cetak,$skpd){
    var ckdskpd = $skpd;    
    var thn = '<?php echo $this->session->userdata("pcThang"); ?>';
    if ($('input[name="chkrancang"]:checked').val()=='1'){
        var chkrancang = '1';
    }else{
        var chkrancang = '0';
    } 
   var  ttd_2 = $('#ttd2').combogrid('getValue');
   var ttd2 = ttd_2.split(" ").join("a");
    
    if($skpd==''){
    	$('#dg_cunit').datagrid('selectAll');
    	var rows = $('#dg_cunit').datagrid('getSelections');   
    	for(var p=0;p<rows.length;p++){
    		ckdskpd  = rows[p].kd_skpd;     
            url="<?php echo site_url(); ?>/rka/preview_perkada/"+ckdskpd+'/'+$cetak+'/'+chkrancang+'/Penjabaran_APBD_'+ckdskpd+'_Tahun_'+thn+'.pdf'+'?&ttd2='+ttd2+'';
            openWindow( url );        
    	}	                     
    }else{
        url="<?php echo site_url(); ?>/rka/preview_perkada/"+ckdskpd+'/'+$cetak+'/'+chkrancang+'/P_APBD_'+ckdskpd+'_Tahun_'+thn+'?&ttd2='+ttd2+'';
        openWindow( url );        
    }
    
    
    /*     
    if($semua == '1'){
    	$('#dg_cunit').datagrid('selectAll');
    	var rows = $('#dg_cunit').datagrid('getSelections');   
    	for(var p=0;p<rows.length;p++){
    		ckdskpd  = rows[p].kd_skpd; 
    		if ($jns != 'unit'){
    			var ckdskpd = ckdskpd.substring(0,7);  
    		}
    
    		url="<?php echo site_url(); ?>/rka/preview_dpa0/"+ckdskpd+'/'+$cetak+'/'+crinci+'/DPA-0 '+ckdskpd+' Tahun '+thn+'.pdf'
    		openWindow( url,$jns );		
    	}	             
    }else{
    	if ($jns != 'unit'){
    		var ckdskpd = ckdskpd.substring(0,7);  
    	}
    
    	url="<?php echo site_url(); ?>/rka/preview_dpa0/"+ckdskpd+'/'+$cetak+'/'+crinci+'/DPA-0 '+ckdskpd+' Tahun '+thn
    	openWindow( url,$jns );
     */    
        
    }
    
 
     function openWindow( url ){
     			window.open(url,'_blank');
    			window.focus();		  
     } 


    </script>
   
	<div id="content">      
    	<h1><?php echo $page_title; ?> </h1>
        <!--<?php echo form_open('rka/cari_perkadaII', array('class' => 'basic')); ?>
		Karakter yang di cari :&nbsp;&nbsp;&nbsp;<input type="text" name="nm_skpd" id="nm_skpd" value="<?php echo set_value('text'); ?>" />
        <input type='submit' name='cari' value='cari' class='btn' />

        <?php echo form_close(); ?>   -->

        <table>
            <tr>
    			<td width="20%" style="border: none;">Penandatanganan</td>
                <td width="1%" style="border: none;">:</td>
                <td style="border: none;"><input type="text" id="ttd2" style="width: 200px;" />&nbsp;&nbsp;<input type="checkbox" name="chkrancang" id="chkrancang" value="1"/> Rancangan 
                </td>
            </tr> 
            <tr>
                <td style="border-collapse:collapse;border-bottom-style:hidden;" valign="bottom">Cetak Semua 
                <a class="easyui-linkbutton" align="top" plain="true" onclick="javascript:cek(3,'');return false">                    
                    <img src="<?php echo base_url(); ?>assets/images/icon/down_pdf.png" width="25" height="23" title="Save Semua Unit - *.pdf" />
                </td>
            </tr>
        </table>		
		<div style="display:none">
			<table id="dg_cunit"  style="width:875px;height:370px;"> 
			</table> 
		</div>

		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>

        <table class="narrow" >
        	<tr>
 	            <th>Kode SKPD </th>            	
                <th>Nama SKPD</th>                
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $skpd) : ?>
            <tr>                
                <td width="8%"><?php echo $skpd->kd_skpd; ?></td>            	
                <td width="80%"><?php echo $skpd->nm_skpd; ?></td>  
                <td>                     
                    <!--<a href="<?php echo site_url(); ?>/rka/preview_perkada/<?php echo $skpd->kd_skpd;?>/<?php echo '0';?>" ><img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="cetak" /></a>
                    <a href="<?php echo site_url(); ?>/rka/preview_perkada/<?php echo $skpd->kd_skpd; ?>/<?php echo '1';?>" target='_blank'><img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
                    <a href="<?php echo site_url(); ?>/rka/preview_perkada/<?php echo $skpd->kd_skpd; ?>/<?php echo '2';?>"><img src="<?php echo base_url(); ?>assets/images/icon/excel.jpg" width="25" height="23" title="cetak"/></a></td>-->
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(0,'<?php echo $skpd->kd_skpd;?>');return false" >
                    <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="preview"/></a>
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(1,'<?php echo $skpd->kd_skpd;?>');return false">                    
                    <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>

                 </td>   
            </tr>
            <?php endforeach; ?>
        </table>
        <!--<?php echo $this->pagination->create_links(); ?> <span class="totalitem">Total Item <?php echo $total_rows ; ?></span>-->
        <div class="clear"></div>
	</div>