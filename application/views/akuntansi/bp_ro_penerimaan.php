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
      //get_skpd();                                                            
    }); 
    
	$(function(){
	$('#sskpd').combogrid({  
		panelWidth:630,  
		idField:'kd_skpd',  
		textField:'kd_skpd',  
		mode:'remote',
		url:'<?php echo base_url(); ?>index.php/tukd/ppkd__pend',  
		columns:[[  
			{field:'kd_skpd',title:'Kode SKPD',width:100},  
			{field:'nm_skpd',title:'Nama SKPD',width:500}    
		]],
		onSelect:function(rowIndex,rowData){
			kdskpd = rowData.kd_skpd;
			$('#kdrek5').combogrid({url:'<?php echo base_url(); ?>index.php/akuntansi/rekening_kasda/', queryParams:({kd_skpd:kdskpd})});
			$("#nmskpd").attr("value",rowData.nm_skpd);
			$("#skpd").attr("value",rowData.kd_skpd);
			//validate_giat();
		}  
		});
        
        $('#kdrek5').combogrid({  
		panelWidth:630,  
		idField:'kd_rek6',  
		textField:'kd_rek6',  
		mode:'remote',
		url:'<?php echo base_url(); ?>index.php/akuntansi/rekening_kasda/'+kdskpd,  
		columns:[[  
			{field:'kd_rek6',title:'Kode Rekening',width:100},  
			{field:'nm_rek6',title:'Nama Rekening',width:500}    
		]],
		onSelect:function(rowIndex,rowData){
			rekening = rowData.kd_rek6;
			$("#kdrek5").attr("value",rowData.kd_rek6);
			$("#nmrek5").attr("value",rowData.nm_rek6);
		}  
		}); 
        
        $('#dcetak').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        }); 
        
        $('#dcetak2').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
		
		$('#tgl_ctk').datebox({  
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
		url: '<?php echo base_url(); ?>/index.php/tukd/list_ttd_ppkd/PPKD',  
			idField:'nip',                    
			textField:'nama',
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
		
		
		 $('#ttd2').combogrid({  
			panelWidth:500,  
			url: '<?php echo base_url(); ?>/index.php/tukd/load_ttd/bp',  
			idField:'nip',                    
			textField:'nama',
			mode:'remote',  
			fitColumns:true,  
			columns:[[  
				{field:'nip',title:'NIP',width:60},  
				{field:'nama',title:'NAMA',align:'left',width:100}								
			]],
			onSelect:function(rowIndex,rowData){
			nip2 = rowData.nip;
			
			}   
		});
		
	});
	
    
    /* function get_skpd()
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
        } */



		function cetakbb($cetak)
        {
			var cetak =$cetak;           	
			var dcetak = $('#dcetak').datebox('getValue');      
			var dcetak2 = $('#dcetak2').datebox('getValue');      
			var tgl_ctk = $('#tgl_ctk').datebox('getValue');      
			var ttd    = nip;                           
            var ttd1 =ttd.split(" ").join("a");
			var ttd_2    = nip2;                           
            var ttd2 =ttd_2.split(" ").join("a");			
			var skpd   = kdskpd; 
			var rek5   = rekening.split(" ").join(""); 

			var url    = "<?php echo site_url(); ?>/akuntansi/cetak_bp_ro_penerimaan"; 
			window.open(url+'/'+dcetak+'/'+ttd1+'/'+skpd+'/'+rek5+'/'+dcetak2+'/'+cetak+'/'+ttd2+'/'+tgl_ctk, '_blank');
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

<h3>CETAK BUKU PEMBANTU PER RINCIAN OBYEK PENERIMAAN</h3>
    <div>
    <p align="right">         
        <table id="sp2d" title="Cetak Buku Besar" style="width:870px;height:300px;" >  
		<tr >
			<td width="20%" height="40" ><B>SKPD</B></td>
			<td width="80%"><input id="sskpd" name="sskpd" readonly="true" style="width: 150px;border: 0;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" readonly="true" style="width: 500px; border:0;" /></td>
		</tr>
		<tr >
			<td width="20%" height="40" ><B>REKENING</B></td>
			<td width="80%"><input id="kdrek5" name="kdrek5" style="width: 150px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmrek5" name="nmrek5" style="width: 500px; border:0;" /></td>
		</tr>

		<tr >
			<td width="20%" height="40" ><B>PERIODE</B></td>
			<td width="80%"><input id="dcetak" name="dcetak" type="text"  style="width:155px" />&nbsp;&nbsp;s/d&nbsp;&nbsp;<input id="dcetak2" name="dcetak2" type="text"  style="width:155px" /></td>
		</tr>
		<tr >
			<td width="20%" height="40" ><B>Tanggal Cetak</B></td>
			<td width="80%"><input id="tgl_ctk" name="tgl_ctk" type="text"  style="width:155px"></td>
		</tr>
		<tr >
			<td width="20%" height="40" ><B>PA</B></td>
			<td width="80%"><input id="ttd" name="ttd" type="text"  style="width:230px" /></td>
		</tr>
		<tr >
			<td width="20%" height="40" ><B>Bend. Penerima</B></td>
			<td width="80%"><input id="ttd2" name="ttd2" type="text"  style="width:230px" /></td>
		</tr>
		<tr >
			<td width="20%" height="40" >&nbsp;</td>
			<td width="80%"> <INPUT TYPE="button" VALUE="Cetak Layar" ONCLICK="cetakbb(1)" style="height:40px;width:100px" > &nbsp;&nbsp;&nbsp;&nbsp; <INPUT TYPE="button" VALUE="Cetak PDF" ONCLICK="cetakbb(2)" style="height:40px;width:100px" >

			</td>
		</tr>
		<tr >
			<td >&nbsp</td>
			<td >&nbsp</td>
		</tr>
        </table>                      
    </p> 
    </div>
</div>
</div>

 	
</body>

</html>