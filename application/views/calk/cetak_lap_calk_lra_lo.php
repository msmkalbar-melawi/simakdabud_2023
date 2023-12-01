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
	   $('#rek3').combogrid({  
		panelWidth:630,  
		idField:'kd_rek3',  
		textField:'kd_rek3',  
		mode:'remote',
		url:'<?php echo base_url(); ?>index.php/tukd/load_rek3_lamp_aset/',  
		columns:[[  
			{field:'kd_rek3',title:'Kode Rekening',width:100},  
			{field:'nm_rek3',title:'Nama Rekening',width:500}    
		]],
		onSelect:function(rowIndex,rowData){
			rekening = rowData.kd_rek3;
			$("#nmrek3").attr("value",rowData.nm_rek3);
		}  
		}); 
	});
    
    $(function(){
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
	
 function validate_ttd(){
   $(function(){
            $('#ttd').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/pilih_ttd/'+kdskpd,  
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
           });              
     }  


		function cetak(ctk)
        {
			var judul = document.getElementById('judul').value;
			
			if(judul=='1'){
				var url    = "<?php echo site_url(); ?>/calk/cetak_lap_calk_lo_811";  
			}else if(judul=='2'){
				var url    = "<?php echo site_url(); ?>/calk/cetak_lap_calk_lo_812";  
			}else if(judul=='3'){
				var url    = "<?php echo site_url(); ?>/calk/cetak_lap_calk_lo_814";
			}else if(judul=='4'){
				var url    = "<?php echo site_url(); ?>/calk/cetak_lap_calk_beban_911";
			}else if(judul=='5'){
				var url    = "<?php echo site_url(); ?>/calk/cetak_lap_calk_beban_912";
			}else if(judul=='6'){
				var url    = "<?php echo site_url(); ?>/calk/cetak_lap_calk_beban_912_2";
			}else if(judul=='7'){
				var url    = "<?php echo site_url(); ?>/calk/cetak_lap_calk_beban_912_3";
			}else if(judul=='8'){
				var url    = "<?php echo site_url(); ?>/calk/cetak_lap_calk_beban_912_4";
			}else{
				alert("Pilih Jenis");
				exit();
			}
			
			window.open(url+'/'+ctk, '_blank');
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


<h3>CETAK LAP. CALK LO BEBAN</h3>
<div id="accordion">

    
    <p align="right">         
        <table id="sp2d" title="CETAK LAP. CALK LO BEBAN" style="width:922px;height:200px;" >  
		<tr>
			<td colspan="2" width="80%">
				<select name="judul" id="judul" style="height: 27px; width: 120px;">
							 <option value="">--Pilih Jenis--</option>
							 <option value="1">Pajak - LO</option>
							 <option value="2">Retribusi - LO</option>
							 <option value="3">Lain-lain PAD yang Sah - LO</option>
							 <option value="4">Beban Pegawai</option>
							 <option value="5">Beban Barang Jasa</option>
							 <option value="6">Beban Persediaan</option>
							 <option value="7">Beban Pemeliharaan</option>
							 <option value="8">Beban Perjalanan Dinas</option>
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