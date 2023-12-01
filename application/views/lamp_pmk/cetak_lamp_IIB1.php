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
	  $('#sskpd').combogrid({  
		panelWidth:630,  
		idField:'kd_skpd',  
		textField:'kd_skpd',  
		mode:'remote',
		url:'<?php echo base_url(); ?>index.php/rka/skpd',  
		columns:[[  
			{field:'kd_skpd',title:'Kode SKPD',width:100},  
			{field:'nm_skpd',title:'Nama SKPD',width:500}    
		]],
		onSelect:function(rowIndex,rowData){
			kdskpd = rowData.kd_skpd;
			$("#nmskpd").attr("value",rowData.nm_skpd);
			$("#skpd").attr("value",rowData.kd_skpd);
		}  
		});
	});
    
    $(function(){
   	    //$("#status").attr("option",false);
        $("#kode_skpd").hide();
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
   	});

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
		
		$('#ttd').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/akuntansi/pilih_ttd_kd',  
                    idField:'nip',                    
                    textField:'nip',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'nip',title:'NIP',width:60},  
                        {field:'nama',title:'NAMA',align:'left',width:100}  
                    ]],
                    onSelect:function(rowIndex,rowData){
                    nip = rowData.nip;  
                    }   
                });
   	});  
	 
	$(function(){
		//$("#status").attr("option",false);
		$("#kode_skpd").hide();
		});  
		function opt(val){  
        ctk = val; 
        if (ctk=='1'){
			$("#kode_skpd").hide();
        }  else {
		   $("#kode_skpd").show();
        }          
		}
		function cetak(pilih)
        {
            var bulan = document.getElementById("bulan").value;  
            var anggaran = document.getElementById("anggaran").value;  
            var jenis = document.getElementById("jenis").value;
			var ttd1 = $('#ttd').combogrid('getValue');     
			var  ctglttd = $('#tgl_ttd').datebox('getValue');
			if(ctglttd==''){
				ctglttd="-";
			}
			if(ttd1==''){
				ttd="-";
			}else{
				ttd=ttd1.split(' ').join('abc');
			}
			
			if (jenis=='1'){
			var url    = "<?php echo site_url(); ?>/lamp_pmk/cetak_lamp_IIB1"; 
			}
			if (jenis=='2'){
			var url    = "<?php echo site_url(); ?>/lamp_pmk/cetak_lamp_IIB1A"; 
			}
			if (jenis=='3'){
			var url    = "<?php echo site_url(); ?>/lamp_pmk/cetak_lamp_IIB1B"; 
			}
			if (jenis=='4'){
			var url    = "<?php echo site_url(); ?>/lamp_pmk/cetak_lamp_IIB1C"; 
			}
			if (jenis=='5'){
			var url    = "<?php echo site_url(); ?>/lamp_pmk/cetak_lamp_IIG"; 
			}
			window.open(url+'/'+bulan+'/'+pilih+'/'+ctglttd+'/'+ttd+'/PMK_LAMP.II'+'/'+anggaran, '_blank');
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


<h3>CETAK LAMP IIB PMK</h3>
<div id="accordion">
    <p align="center">         
        <table id="sp2d" title="Cetak Perda Lamp. 1" width="100%" border="0">  
   	
	<td> Jenis</td> 
	<td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><select  name="jenis" id="jenis" >
     <option value="1">Lamp II.B1</option>
     <option value="2">Lamp II.B1A</option>
     <option value="3">Lamp II.B1B</option>
     <option value="4">Lamp II.B1C</option>
     <option value="5">Lamp II.G</option>
   </select></td>
	</tr>	
	<tr>
	<td width="20%"> Periode</td> 
	<td width="70%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><select  name="bulan" id="bulan" >
     <option value="3">Triwulan I</option>
     <option value="6">Semester I</option>
     <option value="9">Triwulan III </option>
     <option value="12">Semester II </option>
   </select></td> 
   </tr>
   <tr>
   <td> Anggaran</td> 
	<td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><select  name="anggaran" id="anggaran" >
     <option value="1">Penyusunan</option>
     <option value="2">Penyempurnaan</option>
     <option value="3">Perubahan</option>
   </select></td>
	</tr>
   <tr>
   <td>Tanggal TTD</td>
	<td><input type="text" id="tgl_ttd" style="width: 150px;" /> </td> 
	</tr>
    <tr>
	<td>Penandatanganan</td>
	<td><input type="text" id="ttd" style="width: 200px;" /> </td> 
	</tr>
	
	<tr>
		<td> Cetak</td> 
		<td align="left"> 
		<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak(0);">Layar</a>
		<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak(1);">PDF</a>
		<a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak(2);">excel</a>
		</td>
	</tr>
	
        </table>                      
    </p> 
  </div> 
</div>

 	
</body>

</html>