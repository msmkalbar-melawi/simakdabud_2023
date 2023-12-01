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
             get_skpd();
			 $("#perskpd").hide();
			$("#perunit").hide();	
        });   
    
	$(function(){  
            $('#ttd').combogrid({  
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/BUD',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},
                    {field:'nama',title:'Nama',width:400}
                ]],  
			   onSelect:function(rowIndex,rowData){
				   $("#nama").attr("value",rowData.nama);
			   } 
            });

			$('#ttd2').combogrid({  
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/PA',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},
                    {field:'nama',title:'Nama',width:400}
                ]],  
			   onSelect:function(rowIndex,rowData){
				   $("#nama2").attr("value",rowData.nama);
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
			
			$('#tgl_ttd1').datebox({  
				required:true,
				formatter :function(date){
					var y = date.getFullYear();
					var m = date.getMonth()+1;
					var d = date.getDate();
					return y+'-'+m+'-'+d;
				}
			});
			
			$('#skpd').combogrid({  
				panelWidth:630,  
				idField:'kd_skpd',  
				textField:'kd_skpd',  
				mode:'remote',
				url:'<?php echo base_url(); ?>index.php/tukd/kode_organisasi',  
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
			
			$('#sskpd').combogrid({  
				panelWidth:800,  
				idField:'kd_skpd',  
				textField:'kd_skpd',  
				mode:'remote',
				url:'<?php echo base_url(); ?>index.php/tukd/kode_skpd',  
				columns:[[  
					{field:'kd_skpd',title:'Kode SKPD',width:100},  
					{field:'nm_skpd',title:'Nama SKPD',width:700}    
				]],
				onSelect:function(rowIndex,rowData){
					kdskpd = rowData.kd_skpd;
					$("#nm_skpd").attr("value",rowData.nm_skpd);
					$("#sskpd").attr("value",rowData.kd_skpd);
				   
				}  
			});
			
			$("#tagih").hide();
			$("#perunit").hide();
				
    });
    
	function klik(){
		if(document.getElementById('jenis').value == "0") {
			//document.getElementById('belanja').value == "1";
		}else{
			//document.getElementById('belanja').value == "1";
		}
	}
		
	function opt(val){        
        ctk = val; 
        if (ctk=='0'){
            $("#perskpd").hide();
			$("#perunit").hide();
        }else if (ctk=='1'){
           $("#perskpd").show();
		   $("#perunit").hide();
        } else if (ctk=='2'){
			$("#perskpd").hide();
			$("#perunit").show();
		}else if (ctk=='3'){
             $("#perskpd").hide();
			 $("#perunit").hide();
        }else{
			exit();
		}                 
    }
			
    function validate1(){
        var bln1 = document.getElementById('bulan1').value;
        
    }
    
    function get_skpd()
        {
        
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#sskpd").attr("value",data.kd_skpd);
        								$("#nmskpd").attr("value",data.nm_skpd);
                                       // $("#skpd").attr("value",rowData.kd_skpd);
        								kdskpd = data.kd_skpd;
                                        
        							  }                                     
        	});
             
        }
    
		
        function cetak(ctak){
			var cetk       = ctk;
			var no_halaman = document.getElementById('no_halaman').value;
			var  ttd = $('#ttd').combogrid('getValue');
		         //ttd = ttd.split(" ").join("123456789");
			var ctglttd    = $('#tgl_ttd').datebox('getValue');
			var ctglttd1   = $('#tgl_ttd1').datebox('getValue');
			
			
			if(cetk=='0'){
				
				var url        = "<?php echo site_url(); ?>tukd/cetak_register_cp_rekap";
				window.open(url+'/'+ctglttd+'/'+ctglttd1+'/'+no_halaman+'/'+ttd+'/'+ctak, '_blank');
				window.focus();
				//alert(cetk);
				
			}else if(cetk=='1'){
				
				var skpd       = document.getElementById('skpd').value;
				var url        = "<?php echo site_url(); ?>/tukd/cetak_register_cp";
				window.open(url+'/'+ctglttd+'/'+ctglttd1+'/'+no_halaman+'/'+ttd+'/'+skpd+'/'+ctak, '_blank');
				window.focus();
			}else if( cetk=='2'){
				var skpd       = document.getElementById('sskpd').value;
				var url        = "<?php echo site_url(); ?>/tukd/cetak_register_cp";
				window.open(url+'/'+ctglttd+'/'+ctglttd1+'/'+no_halaman+'/'+ttd+'/'+skpd+'/'+ctak, '_blank');
				window.focus();
			}else if( cetk=='3'){
				
				//var skpd       = document.getElementById('sskpd').value;
				var url        = "<?php echo site_url(); ?>/tukd/cetak_register_cp";
				window.open(url+'/'+ctglttd+'/'+ctglttd1+'/'+no_halaman+'/'+ttd+'/-/'+ctak, '_blank');
				window.focus();
				//alert(cetk);
			}else{
				alert("Maaf Data Tidak Tersedia");
			}
        }
        

		 function cetak2(ctak){
			var cetk       = ctk;
			var no_halaman = document.getElementById('no_halaman').value;
			var  ttd = $('#ttd').combogrid('getValue');
		         //ttd = ttd.split(" ").join("123456789");
			var ctglttd    = $('#tgl_ttd').datebox('getValue');
			var ctglttd1   = $('#tgl_ttd1').datebox('getValue');
			
			
			if(cetk=='0'){
				
				var url        = "<?php echo site_url(); ?>tukd/cetak_register_cp_rekap_rinci";
				window.open(url+'/'+ctglttd+'/'+ctglttd1+'/'+no_halaman+'/'+ttd+'/'+ctak, '_blank');
				window.focus();
				//alert(cetk);
				
			}else if(cetk=='1'){
				
				var skpd       = document.getElementById('skpd').value;
				var url        = "<?php echo site_url(); ?>/tukd/cetak_register_cp_rinci";
				window.open(url+'/'+ctglttd+'/'+ctglttd1+'/'+no_halaman+'/'+ttd+'/'+skpd+'/'+ctak, '_blank');
				window.focus();
			}else if( cetk=='2'){
				var skpd       = document.getElementById('sskpd').value;
				var url        = "<?php echo site_url(); ?>/tukd/cetak_register_cp_rinci";
				window.open(url+'/'+ctglttd+'/'+ctglttd1+'/'+no_halaman+'/'+ttd+'/'+skpd+'/'+ctak, '_blank');
				window.focus();
			}else if( cetk=='3'){
				
				//var skpd       = document.getElementById('sskpd').value;
				var url        = "<?php echo site_url(); ?>/tukd/cetak_register_cp_rinci";
				window.open(url+'/'+ctglttd+'/'+ctglttd1+'/'+no_halaman+'/'+ttd+'/-/'+ctak, '_blank');
				window.focus();
				//alert(cetk);
			}else{
				alert("Maaf Data Tidak Tersedia");
			}
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



<h3>REGISTER CP</h3>
<div id="accordion">
    
    <p align="right">         
        <table border="1" id="sp2d" title="Cetak Daftar Potongan Pajak" style="width:922px;height:200px;" >
		<tr>
			<td colspan="4">
				<table style="width:100%;" border="0">
					<tr>
					<td ><input type="radio" name="cetak" value="0" onclick="opt(this.value)" /><b>Rekap per SKPD</b></td>
					</tr>
					<tr>
					<td ><input type="radio" name="cetak" value="1" id="status" onclick="opt(this.value)" /><b>Per SKPD</b>
						<div id="perskpd">
                        <table >
                            <tr >
                    			<td ><B>SKPD</B></td>
                    			<td ><input id="skpd" name="skpd" style="width: 100px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 300px; border:0;" /></td>
                    		</tr>
                        </table> 
						</div>
					</td>
					</tr>
					<tr>
					<td ><input type="radio" name="cetak" value="2" id="status" onclick="opt(this.value)" /><b>Per Unit</b>
						<div id="perunit">
                        <table >
                            <tr >
                    			<td ><B>Unit</B></td>
                    			<td ><input id="sskpd" name="sskpd" style="width: 100px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nm_skpd" name="nm_skpd" style="width: 300px; border:0;" /></td>
                    		</tr>
                        </table> 
						</div>
					</td>
					</tr>
					<tr>
					<td ><input type="radio" name="cetak" value="3" onclick="opt(this.value)" /><b>Keseluruhan</b></td>
					</tr>
				</table>
			</td>
		</tr>
		 <tr>
			<td colspan="4">
                <div id="div_tgl">
                        <table style="width:100%;" border="0">
                            <td width="20%">Tanggal</td>
                            <td><input type="text" id="tgl_ttd" style="width: 100px;" /></td>
							<td width="10%" align="center">s/d</td>
							<td><input type="text" id="tgl_ttd1" style="width: 100px;" /></td>
							<td width="20%"></td>
                        </table>
                </div>
            </td> 
		</tr>
		<tr>
			<td>
				<table style="width:100%;" border="0">
					<td width="20%">Kuasa BUD</td>
                    <td>
						<input type="text" id="ttd" style="width: 200px;" /> &nbsp;&nbsp;
						<input type="nama" id="nama" readonly="true" style="width: 200px;border:0" /> 
                    </td> 
                </table>
			</td>
		</tr>	
		<tr>
			<td>
				<table style="width:100%;" border="0">
					<td width="20%">No. Halaman</td>
					<td><input type="number" id="no_halaman" style="width: 100px;" value="1"/></td>                       
                </table>
			</td>
		</tr>
		
		<tr >
			<td> <b>Cetak :</b> 
			<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak(0);">Layar</a>
			<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak(1);">PDF</a>
			</td>
		</tr>
		<tr >
			<td> <b>Rinci : </b>
			<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak2(0);">Layar</a>
			<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak2(1);">PDF</a>
			</td>
		</tr>
		
        </table>                      
    </p> 
    

</div>
</div>

 	
</body>

</html>