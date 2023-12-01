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
    var nip='';
	var kdskpd='';
	var kdrek5='';
    var kode='';
    
	$(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 100,
                width: 922            
            });             
        }); 

    $(function(){
        $("#kode_skpd").hide();
   	});

	
	function cetak(ctk){
		
			var judul = document.getElementById('judul').value;
			var judul2 = document.getElementById('judul2').value;
			
			var url    = "<?php echo site_url(); ?>/calk/cetak_lap_calk_aset";  
			
			window.open(url+'/'+ctk+'/'+judul+'/'+judul2, '_blank');
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


<h3>CETAK LAP. CALK ASET</h3>
<div id="accordion">

    
    <p align="right">         
        <table id="sp2d" title="CETAK LAP. CALK ASET" style="width:922px;height:200px;" >  
		<tr>
			<td colspan="2" width="80%">
				<select name="judul" id="judul" style="height: 27px; width: 130px;">
							 <option value="">--Pilih Rekening--</option>
							 <option value="131">Tanah</option>
							 <option value="132">Peralatan dan Mesin</option>
							 <option value="133">Gedung dan Bangunan</option>
							 <option value="134">Jalan, Irigasi dan Jaringan</option>
							 <option value="135">Aset Tetap Lainnya</option>
							 <option value="136">Konstruksi Dalam Pengerjaan</option>
							 <option value="143">Aset Tidak Berwujud</option>
							 <option value="144">Aset Lain-lain</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" width="80%">
				<select name="judul2" id="judul2" style="height: 27px; width: 130px;">
							 <option value="">--Pilih Jenis--</option>
							 <option value="1">Mutasi Bertambah</option>
							 <option value="2">Mutasi Berkurang</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" width="80%"> 
            <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak(0);">Cetak</a>
            <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak(1);">Cetak</a>
            <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak(2);">Cetak excel</a>
            <a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:cetak(3);">Cetak word</a></td>
			</td>
		</tr>
		<tr >
			<td ></td>
			<td ></td>
		</tr>
        </table>                      
    </p> 
   
</div>
</div>

 	
</body>

</html>