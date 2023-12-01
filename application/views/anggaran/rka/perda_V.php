

	<div id="content">      
    	<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp; 
       </h1>
 
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow"><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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

        function cek($cetak,$jns){

			var ckdskpd = $jns;        
            if ($('input[name="chkrancang"]:checked').val()=='1'){
                var chkrancang = '1';
            }else{
                var chkrancang = '0';
            } 

			
            url="<?php echo site_url(); ?>/rka/preview_perdaV/"+ckdskpd+'/'+$cetak+'/'+chkrancang+'/Lampiran V - '+ckdskpd;
    
            openWindow( url,$jns );
        }

        function openWindow( url,$jns ){
            var  ctglttd = $('#tgl_ttd').datebox('getValue');
            var ttd_2 = $('#ttd2').combogrid('getValue');
            var ttd2 = ttd_2.split(" ").join("a");
        
        
            if (ctglttd == ''){ 
                alert("Tanggal Tidak Boleh Kosong"); 
                return;
            }
            if (ttd2 == ''){ 
                alert("Penandatangan Tidak Boleh Kosong"); 
                return;
            }

            lc = '?tgl_ttd='+ctglttd+'&ttd2='+ttd2+'';   
            window.open(url+lc,'_blank');
			window.focus();        		  
         } 

    </script>

    <div>
            <table style="width:100%;" border="0">
            <tr>
                <td width="15%" style="border: none;">TANGGAL TTD</td>
                <td width="1%" style="border: none;">:</td>
                <td style="border: none;"><input type="text" id="tgl_ttd" style="width: 100px;" />&nbsp;&nbsp;<input type="checkbox" name="chkrancang" id="chkrancang" value="1"/> Rancangan 
                </td>    
            </tr>
            <tr>
                <td style="border: none;">GUB / SEKDA</td>         
                <td style="border: none;">:</td>            
                <td style="border: none;"><input type="text" id="ttd2" style="width: 200px;" /> 
                </td> 
            </tr>

            <tr>                
                <td style="border: none;">&nbsp;&nbsp;</td>
                <td style="border: none;" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;                     
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(0,'FUNGSI');return false" >
                    <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="preview"/></a>
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(1,'FUNGSI');return false">                    
                    <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
                </td>    
            </tr>

 <!--           <tr>                
                <td><?php echo 'SKPD'; ?></td>            	
                <td>
                    <a href="<?php echo site_url(); ?>/rka/preview_perdaV/<?php echo 'SKPD'; ?>/<?php echo '0' ?>">
                    <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="preview"/></a>
                    <a href="<?php echo site_url(); ?>/rka/preview_perdaV/<?php echo 'SKPD'; ?>/<?php echo '1';?>"target='_blank'>
                    <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
                </td>
            </tr>
            <tr>                
                <td><?php echo 'UNIT'; ?></td>            	
                <td>
                    <a href="<?php echo site_url(); ?>/rka/preview_perdaV/<?php echo 'UNIT'; ?>/<?php echo '0' ?>">
                    <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="preview"/></a>
                    <a href="<?php echo site_url(); ?>/rka/preview_perdaV/<?php echo 'UNIT'; ?>/<?php echo '1';?>"target='_blank'>
                    <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
                </td>
            </tr>
-->
        </table>
 
        <div class="clear"></div>
	</div>