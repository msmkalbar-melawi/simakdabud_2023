

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
    var ctk = '1';
        
    
	function cek(cetak){
        var ctk=cetak;
        var jns=document.getElementById('jns').value;;
         
		url="<?php echo site_url(); ?>rka/preview_cek_kua_semua"
		window.open(url+'/'+ctk+'/'+jns,'_blank');           
    }

    
 
  
  
   </script>


<div id="content1" align="center"> 
    <h3 align="center"><b>CETAK KUA</b></h3>
    <fieldset style="width: 90%;">
     <table align="center" style="width:100%;" border="0">
			
            <tr>
				<td align="right">
					<select name="jns" id="jns" style="height: 27px; width: 190px;">
						 <option value="1">PENYUSUNAN</option>
						 <option value="2">MURNI</option>
						 <option value="3">SEMPURNA</option>
						 <option value="4">PERUBAHAN</option>
				    </select>
				 </td>
                <td colspan="2" align="left">
                 <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cek(0)">Cetak</a>   
                <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cek(1)">Cetak</a>
                <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cek(2)">Cetak</a>
			</td>                
            </tr>
        </table>  
            
    </fieldset>  
    <h1><h1>
</div>  
