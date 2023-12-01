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
			$("#perskpd_perperiode").hide();
			$("#perbulan").hide();
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
			
			$('#skpd1').combogrid({  
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
					$("#nmskpd1").attr("value",rowData.nm_skpd);
					$("#skpd1").attr("value",rowData.kd_skpd);
				   
				}  
			});
			
			$('#sskpd').combogrid({  
				panelWidth:630,  
				idField:'kd_skpd',  
				textField:'kd_skpd',  
				mode:'remote',
				url:'<?php echo base_url(); ?>index.php/tukd/kode_skpd',  
				columns:[[  
					{field:'kd_skpd',title:'Kode SKPD',width:100},  
					{field:'nm_skpd',title:'Nama SKPD',width:500}    
				]],
				onSelect:function(rowIndex,rowData){
					kdskpd = rowData.kd_skpd;
					$("#nm_skpd").attr("value",rowData.nm_skpd);
					$("#sskpd").attr("value",rowData.kd_skpd);
				   
				}  
			});
				
    });
		
	function opt(val){        
        ctk = val; 
        if (ctk=='0'){
			var elem = document.getElementById("bln");
			elem.style.display = "";
			$("#perskpd").hide();
			$("#perskpd_perperiode").hide();
			$("#perbulan").show();
        }else if (ctk=='1'){
			var elem = document.getElementById("bln");
			elem.style.display = "";
            $("#perskpd").show();
			$("#perskpd_perperiode").hide();
			$("#perbulan").show();
        } else if (ctk=='2'){
			var elem = document.getElementById("bln");
			elem.style.display = "";
            $("#perskpd").hide();
			$("#perskpd_perperiode").hide();
			$("#perbulan").show();
        } else if (ctk=='3'){
			var elem = document.getElementById("bln");
			elem.style.display = "";
			$("#perskpd").hide();
			$("#perskpd_perperiode").show();
			$("#perbulan").show();
		}else if (ctk=='4'){
			var elem = document.getElementById("bln");
			elem.style.display = "";
            $("#perskpd").hide();
			$("#perskpd_perperiode").hide();
			$("#perbulan").show();
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
    
		
        function cetak(ctak)
        {
			var cetk       = ctk;
			var no_halaman = document.getElementById('no_halaman').value;
			var jns        = document.getElementById('jenis').value;
			var bulan      = document.getElementById('bulan').value;
			var spasi      = document.getElementById('spasi').value; 
			var ctglttd    = $('#tgl_ttd').datebox('getValue');
			var ctglttd1   = $('#tgl_ttd1').datebox('getValue');
			var ttd        = $('#ttd').combogrid('getValue');
		        ttd        = ttd.split(" ").join("123456789");
			//var skpd       = document.getElementById('skpd').value;
			//var sskpd       = document.getElementById('sskpd').value;
			//var skpd1      = document.getElementById('skpd1').value;  
			
			if(ctglttd==''){
				alert('Pilih Tanggal dulu')
				exit()
			}
			
			if(cetk=='0' || cetk=='2'){
				var url        = "<?php echo site_url(); ?>tukd/ctk_daftar_pengeluaran";
				window.open(url+'/'+jns+'/'+bulan+'/'+ctak+'/'+ttd+'/'+ctglttd+'/'+no_halaman+'/'+cetk+'/'+ctglttd1+'/'+spasi, '_blank');
				window.focus();
			}else if(cetk=='1'){
				var skpd       = document.getElementById('skpd').value;
				var url        = "<?php echo site_url(); ?>tukd/ctk_daftar_pengeluaran";
				window.open(url+'/'+jns+'/'+bulan+'/'+ctak+'/'+ttd+'/'+ctglttd+'/'+no_halaman+'/'+cetk+'/'+ctglttd1+'/'+spasi+'/'+skpd, '_blank');
				window.focus();
			}else if(cetk=='3'){
				var skpd       = document.getElementById('skpd1').value;
				var url        = "<?php echo site_url(); ?>tukd/ctk_daftar_pengeluaran";
				window.open(url+'/'+jns+'/'+bulan+'/'+ctak+'/'+ttd+'/'+ctglttd+'/'+no_halaman+'/'+cetk+'/'+ctglttd1+'/'+spasi+'/'+skpd, '_blank');
				window.focus();
			}else{
				var skpd       = document.getElementById('sskpd').value;
				var url        = "<?php echo site_url(); ?>tukd/ctk_daftar_pengeluaran";
				window.open(url+'/'+jns+'/'+bulan+'/'+ctak+'/'+ttd+'/'+ctglttd+'/'+no_halaman+'/'+cetk+'/'+ctglttd1+'/'+spasi+'/'+skpd, '_blank');
				window.focus();
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



<h3>DAFTAR PENGELUARAN</h3>
<div id="accordion">
    
    <p align="right">         
        <table border="0" id="sp2d" title="Cetak Daftar Pengeluaran" style="width:922px;height:200px;" >
		<tr>
			<td colspan="0">
				<table style="width:100%;" border="0">
					<tr>
					<td><input type="radio" name="cetak" value="0" onclick="opt(this.value)" /><b>Semua</b></td>
					</tr>
					<tr>
					<td><input type="radio" name="cetak" value="1" id="status" onclick="opt(this.value)" /><b>Per SKPD</b>
						<div id="perskpd">
                        <table>
                            <tr >
                    			<td ><B>SKPD</B></td>
                    			<td ><input id="skpd" name="skpd" style="width: 100px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 300px; border:0;" /></td>
                    		</tr>
                        </table> 
						</div>
					</td>
					</tr>
					<tr>
					<td ><input type="radio" name="cetak" value="2" onclick="opt(this.value)" /><b>Per Periode</b></td>
					</tr>
					<tr>
					<td ><input type="radio" name="cetak" value="3" id="periode" onclick="opt(this.value)" /><b>Per SKPD Per Periode</b>
						<div id="perskpd_perperiode">
                        <table >
                            <tr >
                    			<td ><B>SKPD</B></td>
                    			<td ><input id="skpd1" name="skpd1" style="width: 100px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd1" name="nmskpd1" style="width: 300px; border:0;" /></td>
                    		</tr>
                        </table> 
						</div>
					</td>
					</tr>
					<tr>
					<td ><input type="radio" name="cetak" value="4" onclick="opt(this.value)" /><b>Per Unit</b>
						<div id="perbulan">
                        <table >
                            <tr >
                    			<td ><B>Unit</B></td>
                    			<td ><input id="sskpd" name="sskpd" style="width: 100px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nm_skpd" name="nm_skpd" style="width: 300px; border:0;" /></td>
                    		</tr>
                        </table> 
						</div>
					</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="4">
				<table style="width:100%;" border="0">
					<td width="20%">Jenis Beban</td>
					<td >
						<select name="jenis" id="jenis">    
						 <option value="0"> GAJI </option> 
						 <option value="1"> LS </option>
						 <option value="2"> UP </option>
						 <option value="3"> TU </option>
						 <option value="4"> GU </option>
					</td>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="4">
				<table style="width:100%;" border="0">
					<td width="20%">Bulan</td>
					<td id="bln">
						<select name="bulan" id="bulan">    
						 <option value="1"> Januari </option> 
						 <option value="2"> Februari </option>
						 <option value="3"> Maret </option>
						 <option value="4"> April </option> 
						 <option value="5"> Mei </option>
						 <option value="6"> Juni </option>
						 <option value="7"> Juli </option> 
						 <option value="8"> Agustus </option>
						 <option value="9"> September </option>
						 <option value="10"> Oktober </option> 
						 <option value="11"> November </option>
						 <option value="12"> Desember </option> 
					</td>
				</table>
			</td>
		</tr>
        <tr >
			<td colspan="4">
                <div id="div_tgl">
                        <table style="width:100%;" border="0">
                            <td width="20%">Tanggal</td>
                            <td><input type="text" id="tgl_ttd" style="width: 100px;" /></td>
                        </table>
                </div>
            </td> 
		</tr>
		<tr>
		<td colspan="4">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
							<td width="20%">Kuasa BUD</td>
                            <td><input type="text" id="ttd" style="width: 200px;" /> &nbsp;&nbsp;
							<input type="nama" id="nama" readonly="true" style="width: 200px;border:0" /> 
							
                            </td> 
                        </table>
                </div>
        </td> 
		</tr>
		<tr >
			<td colspan="4">
                <div id="div_tgl">
                        <table style="width:100%;" border="0">
                            <td width="20%">Tanggal Cetak</td>
                            <td><input type="text" id="tgl_ttd1" style="width: 100px;" /></td>
                        </table>
                </div>
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
		<tr>
			<td>
				<table style="width:100%;" border="0">
					<td width="20%">Spasi</td>
					<td><input type="number" id="spasi" style="width: 100px;" value="1"/></td>                       
                </table>
			</td>
		</tr>
		<tr >
			<td colspan="2" align="center">
			<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak(0);">Cetak</a>
			<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak(1);">Cetak Pdf</a>
			</td>
		</tr>
		
        </table>                      
    </p> 
    

</div>
</div>

 	
</body>

</html>