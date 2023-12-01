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
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
    <script type="text/javascript"> 
   
    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 400,
                width: 800            
            });
        });   
	
	$(function(){
		$('#tgl1').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        }); 
		
		$('#tgl2').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        }); 
			
    });
	  
		function cetak1(ctk)
        {
			var ctgl1 = $('#tgl1').datebox('getValue');
			var ctgl2 = $('#tgl2').datebox('getValue');
			var spasi   = document.getElementById('spasi').value;
			
			var url    = "<?php echo site_url(); ?>/tukd2/register_pajak3";  
			window.open(url+'/'+ctk+'/'+ctgl1+'/'+ctgl2+'/'+spasi, '_blank');
			window.focus();
			
        }
        

    </script>

    <STYLE TYPE="text/css"> 
		 input.right{ 
         text-align:right; 
         } 
	</STYLE> 

</head>
<body>

<div id="content">



<h3>CETAK REGISTER PAJAK</h3>
<div id="accordion">
    
    <p align="right">         
        <table id="sp2d" title="Cetak Buku Besar" style="width:922px;height:200px;" >  
       <tr >
			<td width="20%" height="40" ><B>Periode</B></td>
			<td width="80%"><input id="tgl1" name="tgl1" style="width: 90px;" /> s/d <input id="tgl2" name="tgl2" style="width:90px;" /></td>
		</tr>
		<tr >
			<td width="20%" height="40" ><B>SPASI</B></td>
			<td><input id="spasi"  style="width: 50px" type="number" value="3" /> </td>
		</tr>
		<tr >
			<td colspan="2">
			<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak1(0);">Cetak Rekap</a>
			<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak1(1);">Cetak Rekap</a>
			</td>
		</tr>
        </table>                      
    </p> 
    

</div>
</div>

 	
</body>

</html>