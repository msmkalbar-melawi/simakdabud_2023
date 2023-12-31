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
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 400,
                width: 800            
            });     
        });   
    
	$(function(){
	 $('#ttd1').combogrid({  
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/xtukd_ppkd/load_ttd_spj/BK',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},
                    {field:'nama',title:'Nama',width:400}
                ]],  
           onSelect:function(rowIndex,rowData){
               $("#nm_ttd1").attr("value",rowData.nama);
           } 
            });

		$('#tgl_ttd').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        }); 
			
         $('#tanggal1').datebox({  
        required:true,
        formatter :function(date){
      	var y = date.getFullYear();
       	var m = date.getMonth()+1;
       	var d = date.getDate();    
       	return y+'-'+m+'-'+d;
        }
     });
	 
	 $('#tanggal2').datebox({  
        required:true,
        formatter :function(date){
      	var y = date.getFullYear();
       	var m = date.getMonth()+1;
       	var d = date.getDate();    
       	return y+'-'+m+'-'+d;
        }
     });
            $('#ttd2').combogrid({  
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/xtukd_ppkd/load_ttd_spj/PA',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},
                    {field:'nama',title:'Nama',width:400}
                ]],  
           onSelect:function(rowIndex,rowData){
               $("#nm_ttd2").attr("value",rowData.nama);
           } 
            }); 
	});
	
    function validate1(){
        var bln1 = document.getElementById('bulan1').value;
        
    }
    
	$(function(){
	$('#sskpd').combogrid({  
		panelWidth:630,  
		idField:'kd_skpd',  
		textField:'kd_skpd',  
		mode:'remote',
		url:'<?php echo base_url(); ?>index.php/akuntansi/skpd_akt',  
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
	
		function cetak(ctk){
			var ctgl1    = $('#tanggal1').datebox('getValue');
			var ctgl2    = $('#tanggal2').datebox('getValue');
			var anggaran = document.getElementById('anggaran').value; 
			var url    = "<?php echo site_url(); ?>lamp_pmk/cetak_real_sp2d";  
			window.open(url+'/'+ctgl1+'/'+ctgl2+'/'+anggaran+'/'+ctk, '_blank');
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



<h3>CETAK REALISASI SP2D-SPJ</h3>
<div id="accordion">
    
    <p align="right">         
        <table id="sp2d" title="Cetak Realisasi SP2D-SPJ" style="width:922px;height:200px;" >  
        <tr >
			<td width="20%" height="40" ><B>Tanggal</B></td>
			<td><input type="text" id="tanggal1" style="width: 140px;" /> s/d <input type="text" id="tanggal2" style="width: 140px;" /> </td>
		</tr>
		<tr>
			<td width="20%" height="40" ><B>ANGGARAN</B></td>
			<td width="80%">			
				<select name="anggaran" id="anggaran" style="width:200px;">
				 <option value="1">MURNI</option>
				 <option value="2">PENYEMPURNAAN</option>
				 <option value="3">PERUBAHAN</option>
				 </select>
		</td>
		</tr>
		<tr >
			<td colspan="2">
			<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak(0);">Cetak</a>
			<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak(1);">Cetak</a>
			<a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak(2);">Cetak</a>
			</td>
		</tr>
		
        </table>                      
    </p> 
    

</div>
</div>

 	
</body>

</html>