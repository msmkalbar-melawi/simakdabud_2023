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
    
    $(document).ready(function() { 
      get_skpd();                                                            
    }); 
    
	$(function(){
        
        $('#dcetak').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        }); 
        
        $('#ttd').combogrid({  
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/pa',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},  
                    {field:'nama',title:'Nama',width:400}    
                ]]  
            });        
	});
	
    
    function get_skpd()
        {
        
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#sskpd").attr("value",data.kd_skpd);
        								$("#nmskpd").attr("value",data.nm_skpd);
                                        $("#skpd").attr("value",data.kd_skpd);
        								kdskpd = data.kd_skpd;
                                               
        							  }                                     
        	});  
        }
		
		function mapping_calk(){
		document.getElementById('load').style.visibility='visible';
		var bln1 = document.getElementById('bulan1').value;
		$(function(){      
		 $.ajax({
			type: 'POST',
			data: ({nomor:'1',bln:bln1}),
			dataType:"json",
			url:"<?php echo base_url(); ?>index.php/calk/proses_mapping",
			success:function(data){
			if (data = 1){
					alert('REKAL CALK SELESAI');
					document.getElementById('load').style.visibility='hidden';
				}
			}
		 });
		});
	}
		
		function cetak(print)
        {
			var dcetak = $('#dcetak').datebox('getValue');         
			/*var  ttd = $('#ttd').combogrid('getValue');     
            var ttd1   = ttd.split(" ").join("a"); 
			var skpd = document.getElementById('sskpd').value; */
			var lampiran = document.getElementById('lampiran').value;
			var jnsctk = document.getElementById('jnsctk').value;
			var judul = document.getElementById('judul').value;
            
			/* if(dcetak>'2017-12-31'){
				alert("Tahun Pada Tanggal Cetak Tidak Boleh Melebihi Tahun 2017");
				exit();
			} */
            
        //if(lampiran=='12'){
				var url    = "<?php echo site_url(); ?>/calk_prov/cetak_babv";
		//}
			
			window.open(url+'/'+dcetak+'/'+'-'+'/'+'-'+'/'+jnsctk+'/'+judul, '_blank');
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

<div id="accordion">

<h3>CETAK CALK</h3>
    <div>
    <p align="right">         
        <table id="sp2d" title="Cetak Buku Besar" style="width:870px;height:300px;" >
		 <!--<tr>
			<td width="20%" height="40" ><B>Rekal s/d Bulan</B></td>
			<td><?php echo $this->rka_model->combo_bulan('bulan1'); ?> </td>
		</tr>
		<tr >
			<td width="20%" height="40" >&nbsp;</td>
			<td width="80%" align="left"> <INPUT TYPE="button" VALUE="Rekal CALK" style="height:40px;width:100px" onclick="mapping_calk()" >			
			</td>
		</tr>
		<tr >
			<td width="20%" height="40" ><B>SKPD</B></td>
			<td width="80%"><input id="sskpd" name="sskpd" readonly="true" style="width: 150px;border: 0;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" readonly="true" style="width: 500px; border:0;" /></td>
		</tr>
		<tr >
			<td width="20%" height="40" ><B>PENGGUNA ANGGARAN</B></td>
			<td width="80%"><input id="ttd" name="ttd" type="text"  style="width:230px" /></td>
		</tr>-->

		<tr >
			<td width="20%" height="40" ><B>TANGGAL CETAK</B></td>
			<td width="80%"><input id="dcetak" name="dcetak" type="text"  style="width:155px" /></td>
		</tr>
		
		<tr >
			<td width="20%" height="40" ><B>Lampiran</B></td>
			<td width="80%">
				<select name="lampiran" id="lampiran" style="height: 27px; width: 350px;">
					 <option value="1">BAB V. PENJELASAN POS - POS LAPORAN KEUANGAN</option>
				 </select>
				 <select name="judul" id="judul" style="height: 27px; width: 120px;">
					 <option value="1">Tanpa Judul</option>
					 <option value="2">Dengan Judul</option>
				 </select>
            </td>
			<td width="80%">
            </td>
		</tr>
		<tr >
			<td width="20%" height="40" ><B>Jenis Cetakkan</B></td>
			<td width="80%"><select name="jnsctk" id="jnsctk" style="height: 27px; width: 190px;">
             <option value="1" >Preview</option>
             <option value="2" >Cetak</option>
            </td>
		</tr>
		<tr >
			<td >&nbsp;</td>
			<td >&nbsp;</td>
		</tr>
		<tr >
			<td colspan="3" align="center">
				<INPUT TYPE="button" VALUE="CETAK" ONCLICK="cetak(0)" style="height:40px;width:100px"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <!--<INPUT TYPE="button" VALUE="CETAK PDF" ONCLICK="cetak(1)" style="height:40px;width:100px" >-->
			</td>
		</tr>
		<tr >
			<td colspan="3" align="center" style="visibility:hidden" >	<DIV id="load" > <IMG SRC="<?php echo base_url(); ?>assets/images/mapping.gif" WIDTH="270" HEIGHT="40" BORDER="0" ALT=""></DIV></td>
		</tr>
        </table>                      
    </p> 
    </div>
</div>
</div>

 	
</body>

</html>