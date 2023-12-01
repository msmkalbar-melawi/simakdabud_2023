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
				
		$('#org').combogrid({  
		panelWidth:630,  
		idField:'kd_org',  
		textField:'kd_org',  
		mode:'remote',
		url:'<?php echo base_url(); ?>index.php/akuntansi/list_org',  
		columns:[[  
			{field:'kd_org',title:'Kode SKPD',width:100},  
			{field:'nm_org',title:'Nama SKPD',width:500}    
		]],
		onSelect:function(rowIndex,rowData){
			kd_org = rowData.kd_org;
			$("#nm_org").attr("value",rowData.nm_org);
			$("#org").attr("value",rowData.kd_org);
           
		}  
		});
   	});

	 
	$(function(){
			$("#kode_skpd").hide();
			$("#kode_org").hide();
		});  
		function opt(val){        
        ctk = val; 
        if (ctk=='1'){
			$("#kode_skpd").hide();
			$("#kode_org").hide();
           
        } else if (ctk=='2'){
			$("#kode_skpd").show();
			$("#kode_org").hide();
           
        } else {
           $("#kode_skpd").hide();
		   $("#kode_org").show();
        }          
                            
    }

		function cetak(pilih)
        {
            var bulan = document.getElementById("bulan").value;  
            var anggaran = document.getElementById("anggaran").value;  
            var jenis = document.getElementById("jenis").value;
            var spj = document.getElementById("spj").value;
            
			if(ctk==2){
				var skpd   = kdskpd; 
				if(spj==1){
					var url    = '<?php echo site_url(); ?>/lamp_perda/cetak_angreal_prog_giat_unit_spj/'+bulan+'/'+pilih+'/'+anggaran+'/'+skpd+'/'+jenis+'/'+ctglttd+'/'+ttd+'/'+spj; 
				}else{
					var url    = '<?php echo site_url(); ?>/lamp_perda/cetak_angreal_prog_giat_unit_sp2d/'+bulan+'/'+pilih+'/'+anggaran+'/'+skpd+'/'+jenis+'/'+spj; 
				}	
			}else{
				if(spj==1){
				var url    = '<?php echo site_url(); ?>/lamp_perda/cetak_angreal_prog_giat_all_spj/'+bulan+'/'+pilih+'/'+anggaran+'/'+'-'+'/'+jenis+'/'+ctglttd+'/'+ttd+'/'+spj; 
				}else{
				var url    = '<?php echo site_url(); ?>/lamp_perda/cetak_angreal_prog_giat_all_sp2d/'+bulan+'/'+pilih+'/'+anggaran+'/'+skpd+'/'+jenis+'/'+spj; 
				}	
			}
			
			
			window.open(url+'/LAP_SEMESTER', '_blank');
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


<h3>CETAK LAP. RINCIAN SEMESTER</h3>
<div id="accordion">
    <p align="center">         
        <table id="sp2d" title="Cetak Perda Lamp. 1" width="100%" border="0">
		<tr><td colspan="2"><input type="radio" name="status" value="1" onclick="opt(this.value)" /><b>Keseluruhan</b></td></tr>
   <tr><td colspan="2"><input type="radio" name="status" value="2" id="status" onclick="opt(this.value)" /><b>Per Unit</b>
                    <div id="kode_skpd">
                        <table style="width:100%;" border="0">
                            <tr >
                    			<td width="22px" height="40%" ><B>Unit&nbsp;&nbsp;</B></td>
                    			<td width="900px"><input id="sskpd" name="sskpd" style="width: 100px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 500px; border:0;" /></td>
                    		</tr>
                        </table> 
                    </div>
        </td>
        </tr>		
		
	<tr>
	<td width="20%"> Periode</td> 
	<td width="70%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><select  name="bulan" id="bulan" >
     <option value="1">Januari</option>
     <option value="2">Februari</option>
     <option value="3">TRIWULAN 1</option>
     <option value="4">April </option>
     <option value="5">Mei </option>
     <option value="6">SEMESTER 1 </option>
     <option value="7">Juli </option>
     <option value="8">Agustus </option>
     <option value="9">TRIWULAN 3 </option>
     <option value="10">Oktober </option>
     <option value="11">Nopember </option>
     <option value="12">SEMESTER 2 </option>
   </select></td> 
   </tr>
    <tr>
   <td> Jenis</td> 
	<td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><select  name="jenis" id="jenis" >
     <option value="3">Program Kegiatan</option>
   </select></td>
	</tr>
	<tr>   
    <td> Pilih</td> 
	<td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><select  name="spj" id="spj" >
     <option value="1">SPJ</option>
     <option value="2">SP2D TERBIT</option>
     <option value="3">SP2D LUNAS</option>
     <option value="4">SP2D ADVICE</option>
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