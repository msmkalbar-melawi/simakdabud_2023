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
        if ($('input[name="chkttd"]:checked').val()=='1'){
            var ckttd = 1;
        }else{
            var ckttd = 0;
        }

         if ($('input[name="chkrancang"]:checked').val()=='1'){
            var chkrancang = '1';
         }else{
            var chkrancang = '0';
         } 
                        
        url="<?php echo site_url(); ?>/perda_penetapan/preview_perdaIII/"+ckdskpd+'/'+$cetak+'/'+ckttd+'/'+chkrancang+'/Lampiran III SKPD - '+ckdskpd;
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
         
    function alltrim(kata){
	 //alert(kata);
        b = (kata.split(' ').join(''));
        c = (b.replace( /\s/g, ""));
        return c
    }
 
    </script>

	<div id="content">      
    	<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp; 
       </h1>
       <!-- <?php echo form_open('rka/cari_perdaIII', array('class' => 'basic')); ?>
		Karakter yang di cari :&nbsp;&nbsp;&nbsp;<input type="text" name="nm_org" id="nm_org" value="<?php echo set_value('text'); ?>" />
        <input type='submit' name='cari' value='cari' class='btn' />
        <?php echo form_close(); ?>   
		
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>-->
        <table>
            <tr>                   
                <td width="15%" style="border: none;">TANGGAL TTD</td>
                <td width="1%" style="border: none;">:</td>
                <td style="border: none;"><input type="text" id="tgl_ttd" style="width: 100px;" /> &nbsp;&nbsp;<input type="checkbox" name="chkrancang" id="chkrancang" value="1"/> Rancangan 
                &nbsp;&nbsp;<input type="checkbox" name="chkttd" id="chkttd" value="1" checked="checked" /> Penandatanganan di SKPD Terakhir</td>     
                </td>
            </tr> 
            <tr>
                <td style="border: none;">GUB / SEKDA</td>         
                <td style="border: none;">:</td>            
                <td style="border: none;"><input type="text" id="ttd2" style="width: 200px;" /> 
                </td> 
            </tr>
        </table>
    
        <table class="narrow">

        	<tr>
 	            <th>Kode SKPD </th>            	
                <th>Nama SKPD</th>                
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $skpd) : ?>
            <tr>                
                <td><?php echo $skpd->kd_org; ?></td>            	
                <td><?php echo $skpd->nm_org; ?></td>  
                <td>                     
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(0,'<?php echo $skpd->kd_org; ?>');return false" >
                    <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="preview"/></a>
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(1,'<?php echo $skpd->kd_org; ?>');return false">                    
                    <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
                </td>
              </tr>
            <?php endforeach; ?>
        </table>
        <!--<?php echo $this->pagination->create_links(); ?> <span class="totalitem">Total Item <?php echo $total_rows ; ?></span>-->
        <div class="clear"></div>
	</div>