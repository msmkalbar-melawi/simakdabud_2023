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
  <style>    
    #tagih {
        position: relative;
        width: 922px;
        height: 100px;
        padding: 0.4em;
    }  
    </style>
    <script type="text/javascript"> 
    var nip='';
	var kdskpd='';
	var kdrek5='';
	var bulan='';
    
     
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
		url:'<?php echo base_url(); ?>index.php/rka/cari_skpd',  
		columns:[[  
			{field:'kd_skpd',title:'Kode Unit',width:100},  
			{field:'nm_skpd',title:'Nama Unit',width:500}    
		]],
		onSelect:function(rowIndex,rowData){
			kdskpd = rowData.kd_skpd;
			$("#nmskpd").attr("value",rowData.nm_skpd);
			$("#skpd").attr("value",rowData.kd_skpd);
           
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
	
        
     
    function submit(){
        if (ctk==''){
            alert('Pilih Jenis Cetakan');
            exit();
        }
        document.getElementById("frm_ctk").submit();    
    }
        
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
   	     $('#dcetak2').datebox({  
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
   	    //$("#status").attr("option",false);
        $("#kode_skpd").hide();
        $("#kode_org").hide();
   	});   
     /*   
    function opt(val){        
        ctk = val; 
        if (ctk=='1'){
            $("#tagih").hide();
            $("#dcetak").datebox("setValue",'');
            $("#dcetak2").datebox("setValue",'');
        } else if (ctk=='2'){
           $("#tagih").show();
           } else {
            exit();
        } 
    } 
*/	

	function opt(val){        
        ctk = val; 
        if (ctk=='1'){
			$("#kode_skpd").hide();
			$("#kode_org").hide();
           // urll ='<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_lo';
        } else if (ctk=='2'){
			$("#kode_skpd").show();
			$("#kode_org").hide();

           // urll ='<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_lo_unit/'+kdskpd+'/'+ctk;
        } else if (ctk=='3'){
			$("#kode_org").show();
			$("#kode_skpd").hide();
           // urll ='<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_lo_unit/'+kdskpd+'/'+ctk;
        } else {
            exit();
        }          
       // $('#frm_ctk').attr('action',urll);                        
    } 
    /*function cetak($pilih){
			var pilih =$pilih;
			cbulan = $('#bulan').combogrid('getValue');
			if(ctk==1){
				urll ='<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_pemda/'+cbulan;
				if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}else if(ctk==2){
				urll ='<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_pemda_unit/'+cbulan+'/'+kdskpd;
				if (kdskpd==''){
				alert("Pilih Unit dulu");
				exit();
				}if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}else if(ctk==3){
				urll ='<?php echo base_url(); ?>index.php/akuntansi_rekon/rpt_neraca_pemda_org/'+cbulan+'/'+kd_org;
				if (kd_org==''){
				alert("Pilih SKPD dulu");
				exit();
				}if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}
					
    			//var url    = "<?php echo site_url(); ?>akuntansi/cetak_lra_lo";	  
    			window.open(urll+'/'+pilih, '_blank');
    			window.focus();
            
        }
	
	function cetak_obyek($pilih){
			var pilih =$pilih;
			cbulan = $('#bulan').combogrid('getValue');
			if(ctk==1){
				urll ='<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_pemda_obyek/'+cbulan;
				if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}else if(ctk==2){
				urll ='<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_pemda_unit_obyek/'+cbulan+'/'+kdskpd;
				if (kdskpd==''){
				alert("Pilih Unit dulu");
				exit();
				}if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}else if(ctk==3){
				urll ='<?php echo base_url(); ?>index.php/akuntansi_rekon/rpt_neraca_pemda_org_obyek/'+cbulan+'/'+kd_org;
				if (kd_org==''){
				alert("Pilih SKPD dulu");
				exit();
				}if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}
			
					
    			//var url    = "<?php echo site_url(); ?>akuntansi/cetak_lra_lo";	  
    			window.open(urll+'/'+pilih, '_blank');
    			window.focus();
            
        }*/

         function cetak($pilih){
			var pilih =$pilih;
			cbulan = $('#bulan').combogrid('getValue');
			if(ctk==1){
				urll ='<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_pemda/'+cbulan;
				if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}else if(ctk==2){
				urll ='<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_pemda_rinci/'+cbulan+'/'+kdskpd;
				if (kdskpd==''){
				alert("Pilih Unit dulu");
				exit();
				}if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}else if(ctk==3){
				urll ='<?php echo base_url(); ?>index.php/akuntansi_rekon/rpt_neraca_pemda_org/'+cbulan+'/'+kd_org;
				if (kd_org==''){
				alert("Pilih SKPD dulu");
				exit();
				}if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}
					
    			//var url    = "<?php echo site_url(); ?>akuntansi/cetak_lra_lo";	  
    			window.open(urll+'/'+pilih, '_blank');
    			window.focus();
            
        }
	
	function cetak_obyek($pilih){
			var pilih =$pilih;
			cbulan = $('#bulan').combogrid('getValue');
			if(ctk==1){
				urll ='<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_pemda_obyek_skpd/'+cbulan;
				if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}else if(ctk==2){
				urll ='<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_pemda_obyek_skpd_rinci/'+cbulan+'/'+kdskpd;
				if (kdskpd==''){
				alert("Pilih Unit dulu");
				exit();
				}if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}else if(ctk==3){
				urll ='<?php echo base_url(); ?>index.php/akuntansi_rekon/rpt_neraca_pemda_org_obyek/'+cbulan+'/'+kd_org;
				if (kd_org==''){
				alert("Pilih SKPD dulu");
				exit();
				}if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}
			
					
    			//var url    = "<?php echo site_url(); ?>akuntansi/cetak_lra_lo";	  
    			window.open(urll+'/'+pilih, '_blank');
    			window.focus();
            
        }


        function cetak_aset_tetap($pilih){
			var pilih =$pilih;
			cbulan = $('#bulan').combogrid('getValue');
			if(ctk==1){
				urll ='<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_pemda_obyek_aset_tetap/'+cbulan;
				if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}else if(ctk==2){
				urll ='<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_pemda_obyek_aset_tetap_unit/'+cbulan+'/'+kdskpd;
				if (kdskpd==''){
				alert("Pilih Unit dulu");
				exit();
				}if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}else if(ctk==3){
				urll ='<?php echo base_url(); ?>index.php/akuntansi_rekon/rpt_neraca_pemda_obyek_aset_tetap_org/'+cbulan+'/'+kd_org;
				if (kd_org==''){
				alert("Pilih SKPD dulu");
				exit();
				}if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}
			
					
    			//var url    = "<?php echo site_url(); ?>akuntansi/cetak_lra_lo";	  
    			window.open(urll+'/'+pilih, '_blank');
    			window.focus();
            
        }



         function cetak_rinci_obyek($pilih){
			var pilih =$pilih;
			cbulan = $('#bulan').combogrid('getValue');
			if(ctk==1){
				urll ='<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_pemda_rinci_obyek/'+cbulan;
				if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}else if(ctk==2){
				urll ='<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_pemda_unit_rinci_obyek/'+cbulan+'/'+kdskpd;
				if (kdskpd==''){
				alert("Pilih Unit dulu");
				exit();
				}if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}else if(ctk==3){
				urll ='<?php echo base_url(); ?>index.php/akuntansi_rekon/rpt_neraca_pemda_org_rinci_obyek/'+cbulan+'/'+kd_org;
				if (kd_org==''){
				alert("Pilih SKPD dulu");
				exit();
				}if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}
			
					
    			//var url    = "<?php echo site_url(); ?>akuntansi/cetak_lra_lo";	  
    			window.open(urll+'/'+pilih, '_blank');
    			window.focus();
            
        }
	
	
	function ctk_awal($pilih){
			var pilih =$pilih;
			cbulan = $('#bulan').combogrid('getValue');
			if(ctk==1){
				urll ='<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_awal_pemda/'+cbulan;
				if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}else if (ctk==2){
				urll ='<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_awal_pemda_unit/'+cbulan+'/'+kdskpd;
				if (kdskpd==''){
				alert("Pilih Unit dulu");
				exit();
				}if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}else if(ctk==3){
				urll ='<?php echo base_url(); ?>index.php/akuntansi_rekon/rpt_neraca_awal_pemda_org/'+cbulan+'/'+kd_org;
				if (kd_org==''){
				alert("Pilih SKPD dulu");
				exit();
				}if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}
					
    			//var url    = "<?php echo site_url(); ?>akuntansi/cetak_lra_lo";	  
    			window.open(urll+'/'+pilih, '_blank');
    			window.focus();
            
        }
	
	function ctk_awal_obyek($pilih){
			var pilih =$pilih;
			cbulan = $('#bulan').combogrid('getValue');
			if(ctk==1){
				urll ='<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_awal_pemda_obyek/'+cbulan;
				if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}else if(ctk==2){
				urll ='<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_awal_pemda_unit_obyek/'+cbulan+'/'+kdskpd;
				if (kdskpd==''){
				alert("Pilih Unit dulu");
				exit();
				}if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}else if(ctk==3){
				urll ='<?php echo base_url(); ?>index.php/akuntansi_rekon/rpt_neraca_awal_pemda_org_obyek/'+cbulan+'/'+kd_org;
				if (kd_org==''){
				alert("Pilih SKPD dulu");
				exit();
				}if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}
					
    			//var url    = "<?php echo site_url(); ?>akuntansi/cetak_lra_lo";	  
    			window.open(urll+'/'+pilih, '_blank');
    			window.focus();
            
        }
	
	$(function(){ 
		           $('#bulan').combogrid({  
                   panelWidth:120,
                   panelHeight:300,  
                   idField:'bln',  
                   textField:'nm_bulan',  
                   mode:'remote',
                   url:'<?php echo base_url(); ?>index.php/rka/bulan',  
                   columns:[[ 
                       {field:'nm_bulan',title:'Nama Bulan',width:700}    
                   ]],
					onSelect:function(rowIndex,rowData){
						bulan = rowData.nm_bulan;
						$("#bulan").attr("value",rowData.nm_bulan);
					}
               }); 
		  });
    
		function runEffect() {
        var selectedEffect = 'blind';            
        var options = {};                      
        $( "#tagih" ).toggle( selectedEffect, options, 500 );
        };
        
      function pilih() {
       op = '1';       
      };   
     
        
    
    </script>

    <STYLE TYPE="text/css"> 
		 input.right{ 
         text-align:right; 
         } 
	</STYLE> 

</head>
<body>

     
<div id="content">
		<h3>CETAK NERACA KONSOLIDASI</h3>

		<p align="right">
		<table id="sp2d" title="Cetak" style="width:922px;height:200px;">
			<tr>
				<td width="922px" colspan="2"><input type="radio" name="cetak" value="1" onclick="opt(this.value)" /><b>Keseluruhan</b></td>
			</tr>
			<tr hidden>
				<td width="922px" colspan="2"><input type="radio" name="cetak" value="3" id="status" onclick="opt(this.value)" /><b>Per SKPD</b>
					<div id="kode_org">
						<table style="width:218%;" border="0">
							<tr>
								<td width="22px" height="40%"><B>SKPD&nbsp;&nbsp;</B></td>
								<td width="900px"><input id="org" name="org" style="width: 120px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nm_org" name="nm_org" style="width: 570px; border:0;" /></td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td width="922px" colspan="2"><input type="radio" name="cetak" value="2" id="status" onclick="opt(this.value)" /><b>Per Unit</b>
					<div id="kode_skpd">
						<table style="width:100%;" border="0">
							<tr>
								<td width="175px" height="40%"><B>SKPD&nbsp;&nbsp;</B></td>
								<td width="900px"><input id="sskpd" name="sskpd" style="width: 160px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 400px; border:0;" /></td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<div id="div_periode">
						<table style="width:100%;" border="0">
							<td width="160px" height="40%"><B>Bulan</B></td>
							<td width="900px"><input type="text" id="bulan" style="width: 160px;" />
							</td>
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<div id="div_periode">
						<table style="width:100%;" border="0">
							<td width="150" height="40%"><B>Penandatangan</B></td>
							<td width="900px"><input type="text" id="ttd" style="width: 160px;" />
							</td>
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<div id="div_periode">
						<table style="width:100%;" border="0">
							<td width="160px" height="40%"><B>Tanggal TTD</B></td>
							<td width="900px"><input type="text" id="tgl_ttd" style="width: 160px;" />
							</td>
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<div id="div_neraca">
						<table style="height:40%;">
							<td width="160px" height="40%"><B>Neraca</B></td>
							<td><button class="button" plain="true" onclick="javascript:cetak(1);"><i class="fa fa-print"></i> Cetak Layar</button></td>
							<td><button class="button" plain="true" onclick="javascript:cetak(0);"><i class="fa fa-file-pdf-o"></i> Cetak PDF</button></td>
							<td><button class="button" plain="true" onclick="javascript:cetak(2);"><i class="fa fa-excel"></i> Cetak Excel</button></td>
							<td><button class="button" plain="true" onclick="javascript:cetak(3);"><i class="fa fa-file-word-o"></i> Cetak Word</button></td>
						</table>
			</tr>
			<tr>
				<td colspan="3">
					<div id="div_neraca">
						<table style="height:40%;">
							<td width="160px" height="40%"><B>Neraca Rinci</B></td>
							<td><button class="button" plain="true" onclick="javascript:cetak2(1);"><i class="fa fa-print"></i> Cetak Layar</button></td>
							<td><button class="button" plain="true" onclick="javascript:cetak2(0);"><i class="fa fa-file-pdf-o"></i> Cetak PDF</button></td>
							<td><button class="button" plain="true" onclick="javascript:cetak2(2);"><i class="fa fa-excel"></i> Cetak Excel</button></td>
							<td><button class="button" plain="true" onclick="javascript:cetak2(3);"><i class="fa fa-file-word-o"></i> Cetak Word</button></td>
						</table>
			</tr>
			<!-- <tr>
		<td colspan="3">
        <div id="div_neraca">
			<table style="width:100%;height:40%;" >          
				<td width="5px" height="40%"><a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_obyek(1);">Cetak Per Obyek</a></td>
				<td width="5px" height="40%"><a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak_obyek(2);">Cetak Per Obyek Excel</a></td>
				<td width="5px" height="40%"><a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:cetak_obyek(3);">Cetak Per Obyek Word</a></td>
				<td width="5px" height="40%"><a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_obyek(4);">Cetak Per Obyek PDF</a></td>
			</table>
		</tr>
		<tr>
		<td colspan="3">
        <div id="div_neraca">
			<table style="width:100%;height:40%;" >          
				<td width="5px" height="40%"><a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_rinci_obyek(1);">Cetak Per Rinci Obyek</a></td>
				<td width="5px" height="40%"><a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak_rinci_obyek(2);">Cetak Per Rinci Obyek Excel</a></td>
				<td width="5px" height="40%"><a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:cetak_rinci_obyek(3);">Cetak Per Rinci Obyek Word</a></td>
				<td width="5px" height="40%"><a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_rinci_obyek(4);">Cetak Per Rinci Obyek PDF</a></td>
			</table>
		</tr> -->





			<!-- 	<tr>
		<td colspan="3">
        <div id="div_neraca">
			<table style="width:100%;height:40%;" >          
				<td width="5px" height="40%"><b><a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:ctk_awal(1);">Cetak Neraca Konsol Awal</a></b></td>
				<td width="5px" height="40%"><b><a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:ctk_awal(2);">Cetak Excel Neraca Konsol Awal</a></b></td>
				<td width="5px" height="40%"><b><a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:ctk_awal(3);">Cetak Word Neraca Konsol Awal</a></b></td>
			</table>
		</tr>
		<tr>
		<td colspan="3">
        <div id="div_neraca">
			<table style="width:100%;height:40%;" >          
				<td width="5px" height="40%"><b><a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:ctk_awal_obyek(1);">Neraca Konsol Awal Per Obyek</a></b></td>
				<td width="5px" height="40%"><b><a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:ctk_awal_obyek(2);">Neraca Konsol Awal Per Obyek</a></b></td>
				<td width="5px" height="40%"><b><a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:ctk_awal_obyek(3);">Neraca Konsol Awal Per Obyek</a></b></td>
			</table>
		</tr> -->
		</table>
		</p>



	</div>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
	<script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>

	<script type="text/javascript">
		var nip = '';
		var kdskpd = '';
		var kdrek5 = '';
		var bulan = '';


		$(document).ready(function() {
			$("#accordion").accordion();
			$("#dialog-modal").dialog({
				height: 100,
				width: 922
			});

			$('#sskpd').combogrid({
				panelWidth: 630,
				idField: 'kd_skpd',
				textField: 'kd_skpd',
				mode: 'remote',
				url: '<?php echo base_url(); ?>index.php/rka/cari_skpd',
				columns: [
					[{
							field: 'kd_skpd',
							title: 'Kode Unit',
							width: 160
						},
						{
							field: 'nm_skpd',
							title: 'Nama Unit',
							width: 500
						}
					]
				],
				onSelect: function(rowIndex, rowData) {
					kdskpd = rowData.kd_skpd;
					$("#nmskpd").attr("value", rowData.nm_skpd);
					$("#skpd").attr("value", rowData.kd_skpd);

				}
			});

			$('#tgl_ttd').datebox({
				required: true,
				formatter: function(date) {
					var y = date.getFullYear();
					var m = date.getMonth() + 1;
					var d = date.getDate();
					return y + '-' + m + '-' + d;
				}
			});

			$('#ttd').combogrid({
				panelWidth: 200,
				url: '<?php echo base_url(); ?>/index.php/lamp_perda/load_ttd_perda',
				idField: 'nip',
				textField: 'nama',
				mode: 'remote',
				fitColumns: true,
				columns: [
					[{
						field: 'nama',
						title: 'NAMA',
						align: 'left',
						width: 170
					}]
				],
				onSelect: function(rowIndex, rowData) {
					nip = rowData.nip;
				}
			});
			$('#org').combogrid({
				panelWidth: 630,
				idField: 'kd_org',
				textField: 'kd_org',
				mode: 'remote',
				url: '<?php echo base_url(); ?>index.php/akuntansi/list_org',
				columns: [
					[{
							field: 'kd_org',
							title: 'Kode SKPD',
							width: 100
						},
						{
							field: 'nm_org',
							title: 'Nama SKPD',
							width: 500
						}
					]
				],
				onSelect: function(rowIndex, rowData) {
					kd_org = rowData.kd_org;
					$("#nm_org").attr("value", rowData.nm_org);
					$("#org").attr("value", rowData.kd_org);

				}
			});

			$('#dcetak').datebox({
				required: true,
				formatter: function(date) {
					var y = date.getFullYear();
					var m = date.getMonth() + 1;
					var d = date.getDate();
					return y + '-' + m + '-' + d;
				}
			});

			$('#dcetak2').datebox({
				required: true,
				formatter: function(date) {
					var y = date.getFullYear();
					var m = date.getMonth() + 1;
					var d = date.getDate();
					return y + '-' + m + '-' + d;
				}
			});

			$("#kode_skpd").hide();
			$("#kode_org").hide();
		});

		function submit() {
			if (ctk == '') {
				alert('Pilih Jenis Cetakan');
				exit();
			}
			document.getElementById("frm_ctk").submit();
		}

		function opt(val) {
			ctk = val;
			if (ctk == '1') {
				$("#kode_skpd").hide();
				$("#kode_org").hide();
				// urll ='<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_lo';
			} else if (ctk == '2') {
				$("#kode_skpd").show();
				$("#kode_org").hide();

				// urll ='<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_lo_unit/'+kdskpd+'/'+ctk;
			} else if (ctk == '3') {
				$("#kode_org").show();
				$("#kode_skpd").hide();
				// urll ='<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_lo_unit/'+kdskpd+'/'+ctk;
			} else {
				exit();
			}
			// $('#frm_ctk').attr('action',urll);                        
		}

		function cetak($pilih) {
			var pilih = $pilih;
			cbulan = $('#bulan').combogrid('getValue');
			var tandatgn = $('#ttd').combogrid('getValue');
			var ttd1 = tandatgn.split(" ").join("n");
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			if (ctk == 1) {
				urll = '<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_pemda/' + cbulan;
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			} else if (ctk == 2) {
				urll = '<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_pemda_unit_obyek_skpd/' + cbulan + '/' + kdskpd;
				if (kdskpd == '') {
					alert("Pilih Unit dulu");
					exit();
				}
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			} else if (ctk == 3) {
				urll = '<?php echo base_url(); ?>index.php/akuntansi_rekon/rpt_neraca_pemda_org/' + cbulan + '/' + kd_org;
				if (kd_org == '') {
					alert("Pilih SKPD dulu");
					exit();
				}
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			}

			//var url    = "<?php echo site_url(); ?>/akuntansi/cetak_lra_lo";	  
			window.open(urll + '/' + pilih + '/' + ttd1 + '/' + ctglttd, '_blank');
			window.focus();

		}

		function cetak2($pilih) {
			var pilih = $pilih;
			cbulan = $('#bulan').combogrid('getValue');
			var tandatgn = $('#ttd').combogrid('getValue');
			var ttd1 = tandatgn.split(" ").join("n");
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			if (ctk == 1) {
				urll = '<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_pemda_rinci/' + cbulan;
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			} else if (ctk == 2) {
				urll = '<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_pemda_unit_obyek_skpd_rinci/' + cbulan + '/' + kdskpd;
				if (kdskpd == '') {
					alert("Pilih Unit dulu");
					exit();
				}
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			} else if (ctk == 3) {
				urll = '<?php echo base_url(); ?>index.php/akuntansi_rekon/rpt_neraca_pemda_org_rinci/' + cbulan + '/' + kd_org;
				if (kd_org == '') {
					alert("Pilih SKPD dulu");
					exit();
				}
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			}
			// if (ctk == '1') {
			// 	$("#kode_skpd").hide();
			// 	$("#kode_org").hide();
			// } else if (ctk == '2') {
			// 	$("#kode_skpd").show();
			// 	$("#kode_org").hide();

			// } else if (ctk == '3') {
			// 	$("#kode_org").show();
			// 	$("#kode_skpd").hide();
			// }
			//var url    = "<?php echo site_url(); ?>/akuntansi/cetak_lra_lo";	  
			window.open(urll + '/' + pilih + '/' + ttd1 + '/' + ctglttd, '_blank');
			window.focus();

		}

		function cetak_obyek($pilih) {
			var pilih = $pilih;
			cbulan = $('#bulan').combogrid('getValue');
			if (ctk == 1) {
				urll = '<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_pemda_obyek/' + cbulan;
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			} else if (ctk == 2) {
				urll = '<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_pemda_unit_obyek/' + cbulan + '/' + kdskpd;
				if (kdskpd == '') {
					alert("Pilih Unit dulu");
					exit();
				}
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			} else if (ctk == 3) {
				urll = '<?php echo base_url(); ?>index.php/akuntansi_rekon/rpt_neraca_pemda_org_obyek/' + cbulan + '/' + kd_org;
				if (kd_org == '') {
					alert("Pilih SKPD dulu");
					exit();
				}
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			}


			//var url    = "<?php echo site_url(); ?>/akuntansi/cetak_lra_lo";	  
			window.open(urll + '/' + pilih, '_blank');
			window.focus();

		}

		function cetak_rinci_obyek($pilih) {
			var pilih = $pilih;
			cbulan = $('#bulan').combogrid('getValue');
			if (ctk == 1) {
				urll = '<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_pemda_rinci_obyek/' + cbulan;
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			} else if (ctk == 2) {
				urll = '<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_pemda_unit_rinci_obyek/' + cbulan + '/' + kdskpd;
				if (kdskpd == '') {
					alert("Pilih Unit dulu");
					exit();
				}
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			} else if (ctk == 3) {
				urll = '<?php echo base_url(); ?>index.php/akuntansi_rekon/rpt_neraca_pemda_org_rinci_obyek/' + cbulan + '/' + kd_org;
				if (kd_org == '') {
					alert("Pilih SKPD dulu");
					exit();
				}
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			}


			//var url    = "<?php echo site_url(); ?>/akuntansi/cetak_lra_lo";	  
			window.open(urll + '/' + pilih, '_blank');
			window.focus();

		}


		function ctk_awal($pilih) {
			var pilih = $pilih;
			cbulan = $('#bulan').combogrid('getValue');
			if (ctk == 1) {
				urll = '<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_awal_pemda/' + cbulan;
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			} else if (ctk == 2) {
				urll = '<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_awal_pemda_unit/' + cbulan + '/' + kdskpd;
				if (kdskpd == '') {
					alert("Pilih Unit dulu");
					exit();
				}
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			} else if (ctk == 3) {
				urll = '<?php echo base_url(); ?>index.php/akuntansi_rekon/rpt_neraca_awal_pemda_org/' + cbulan + '/' + kd_org;
				if (kd_org == '') {
					alert("Pilih SKPD dulu");
					exit();
				}
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			}

			//var url    = "<?php echo site_url(); ?>/akuntansi/cetak_lra_lo";	  
			window.open(urll + '/' + pilih, '_blank');
			window.focus();

		}

		function ctk_awal_obyek($pilih) {
			var pilih = $pilih;
			cbulan = $('#bulan').combogrid('getValue');
			if (ctk == 1) {
				urll = '<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_awal_pemda_obyek/' + cbulan;
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			} else if (ctk == 2) {
				urll = '<?php echo base_url(); ?>index.php/akuntansi/rpt_neraca_awal_pemda_unit_obyek/' + cbulan + '/' + kdskpd;
				if (kdskpd == '') {
					alert("Pilih Unit dulu");
					exit();
				}
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			} else if (ctk == 3) {
				urll = '<?php echo base_url(); ?>index.php/akuntansi_rekon/rpt_neraca_awal_pemda_org_obyek/' + cbulan + '/' + kd_org;
				if (kd_org == '') {
					alert("Pilih SKPD dulu");
					exit();
				}
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			}

			//var url    = "<?php echo site_url(); ?>/akuntansi/cetak_lra_lo";	  
			window.open(urll + '/' + pilih, '_blank');
			window.focus();

		}

		$(function() {
			$('#bulan').combogrid({
				panelWidth: 120,
				panelHeight: 300,
				idField: 'bln',
				textField: 'nm_bulan',
				mode: 'remote',
				url: '<?php echo base_url(); ?>index.php/rka/bulan',
				columns: [
					[{
						field: 'nm_bulan',
						title: 'Nama Bulan',
						width: 700
					}]
				],
				onSelect: function(rowIndex, rowData) {
					bulan = rowData.nm_bulan;
					$("#bulan").attr("value", rowData.nm_bulan);
				}
			});
		});

		function runEffect() {
			var selectedEffect = 'blind';
			var options = {};
			$("#tagih").toggle(selectedEffect, options, 500);
		};

		function pilih() {
			op = '1';
		};
	</script>

 	
</body>

</html>