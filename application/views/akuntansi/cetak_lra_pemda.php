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
        } else {
			$("#kode_skpd").hide();
			$("#kode_org").show();        }          
       // $('#frm_ctk').attr('action',urll);                        
    } 
	
    function cetak($pilih){
			var pilih =$pilih;
			cbulan = $('#bulan').combogrid('getValue');
			if(ctk==1){
				urll ='<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_pemda/'+cbulan;
				if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}else if(ctk==2){
				urll ='<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_pemda_unit/'+cbulan+'/'+kdskpd;
				if (kdskpd==''){
				alert("Pilih Unit dulu");
				exit();
				}if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}else if(ctk==3){
				var js   = document.getElementById("js").value;
				urll ='<?php echo base_url(); ?>index.php/akuntansi_rekon/cetak_lra_pemda_org/'+cbulan+'/'+kd_org+'/'+pilih+'/'+js;
				if (kd_org==''){
				alert("Pilih SKPD dulu");
				exit();
				}if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}
					
    			//var url    = "<?php echo site_url(); ?>/akuntansi/cetak_lra_lo";	  
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



<h3>CETAK LAPORAN REALISASI ANGGARAN KONSOLIDASI</h3>       
<div id="accordion">
    
    <p align="right">         
        <table id="sp2d" title="Cetak" style="width:922px;height:200px;" >          
        <tr><td width="922px" colspan="2"><input type="radio" name="cetak" value="1" onclick="opt(this.value)" /><b>Keseluruhan</b></td></tr>
        <tr><td width="922px" colspan="2"><input type="radio" name="cetak" value="3" id="status" onclick="opt(this.value)" /><b>Per SKPD</b>
                    <div id="kode_org">
                        <table style="width:100%;" border="0">
                            <tr >
                    			<td width="22px" height="40%" ><B>Unit&nbsp;&nbsp;</B></td>
                    			<td width="900px"><input id="org" name="org" style="width: 100px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nm_org" name="nm_org" style="width: 670px; border:0;" /></td>
                    		</tr>
							<tr >
                    			<td width="22px" height="40%" ><B>Jenis</B></td>
                    			<td width="900px"><select  name="js" id="js" >
								 <option value="1">skpd</option>
								 <option value="2">org</option>
							   </select></td>
                    		</tr>
                        </table> 
                    </div>
        </td>
        </tr>
		<tr><td width="922px" colspan="2"><input type="radio" name="cetak" value="2" id="status" onclick="opt(this.value)" /><b>Per Unit</b>
                    <div id="kode_skpd">
                        <table style="width:100%;" border="0">
                            <tr >
                    			<td width="22px" height="40%" ><B>Unit&nbsp;&nbsp;</B></td>
                    			<td width="900px"><input id="sskpd" name="sskpd" style="width: 100px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 670px; border:0;" /></td>
                    		</tr>
                        </table> 
                    </div>
        </td>
        </tr>       
        <tr>
                <td colspan="2">
                <div id="div_periode">
                        <table style="width:100%;" border="0">
                            <td width="22px" height="40%"><B>Bulan</B></td>
                            <td width="900px"><input type="text" id="bulan" style="width: 100px;" /> 
                            </td>
                        </table>
                </div>
                </td>
            </tr>
        
		<tr >
			<td colspan="2"><a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak(1);">Cetak</a>
            <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak(2);">Cetak excel</a>
            <a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:cetak(3);">Cetak word</a></td>
		</tr>
		
        </table>                      
    </p> 
    

</div>

</div>

 	
</body>

</html>