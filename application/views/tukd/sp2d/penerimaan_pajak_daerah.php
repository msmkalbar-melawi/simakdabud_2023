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
			$("#tagih").hide();
			$("#pertanggal").hide();
			$("#perpengirim").hide();
			$("#perwilayah").hide();
			$("#rekap").hide();
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
			
			$('#tgl_ttd_pengirim').datebox({  
				required:true,
				formatter :function(date){
					var y = date.getFullYear();
					var m = date.getMonth()+1;
					var d = date.getDate();
					return y+'-'+m+'-'+d;
				}
			});
			
			$('#tgl_ttd1_pengirim').datebox({  
				required:true,
				formatter :function(date){
					var y = date.getFullYear();
					var m = date.getMonth()+1;
					var d = date.getDate();
					return y+'-'+m+'-'+d;
				}
			});
			
			$('#tgl_ttd_wilayah').datebox({  
				required:true,
				formatter :function(date){
					var y = date.getFullYear();
					var m = date.getMonth()+1;
					var d = date.getDate();
					return y+'-'+m+'-'+d;
				}
			});
			
			$('#tgl_ttd1_wilayah').datebox({  
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
			
			$('#id_pengirim').combogrid({
			   panelWidth:700,  
			   idField:'kd_pengirim',  
			   textField:'kd_pengirim',  
			   mode:'remote',
			   url:'<?php echo base_url(); ?>index.php/tukd/load_pengirim',             
			   columns:[[  
				   {field:'kd_pengirim',title:'Kode Pengirim',width:140},  
				   {field:'nm_pengirim',title:'Nama Pengirim',width:700}
			   ]],  
			   onSelect:function(rowIndex,rowData){
				   kd_pengirim = rowData.kd_pengirim;
				   $("#nm_pengirim").attr("value",rowData.nm_pengirim);                                      
			   }
			});
			
			$('#id_wilayah').combogrid({
			   panelWidth:700,  
			   idField:'kd_wilayah',  
			   textField:'kd_wilayah',  
			   mode:'remote',
			   url:'<?php echo base_url(); ?>index.php/tukd/load_wilayah',             
			   columns:[[  
				   {field:'kd_wilayah',title:'Kode Wilayah',width:140},  
				   {field:'nm_wilayah',title:'Nama Wilayah',width:700}
			   ]],  
			   onSelect:function(rowIndex,rowData){
				   kd_wilayah = rowData.kd_wilayah;
				   $("#nm_wilayah").attr("value",rowData.nm_wilayah);                                      
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
			
        if (ctk=='1'){
            $("#tagih").show();
			$("#pertanggal").hide();
			$("#perpengirim").hide();
			$("#perwilayah").hide();
			$("#pertanggal_pengirim").hide();
			$("#pertanggal_wilayah").hide();
			$("#perbulan_pengirim").hide();
			$("#perbulan_wilayah").hide();
			$("#rekap").hide();
        }else if(ctk=='2'){
            $("#tagih").hide();
			$("#pertanggal").show();
			$("#perpengirim").hide();
			$("#perwilayah").hide();
			$("#pertanggal_pengirim").hide();
			$("#pertanggal_wilayah").hide();
			$("#perbulan_pengirim").hide();
			$("#perbulan_wilayah").hide();
			$("#rekap").hide();
        }else if(ctk=='3'){
			$("#tagih").hide();
			$("#pertanggal").hide();
			$("#perpengirim").show();
			$("#pertanggal_pengirim").hide();
			$("#perbulan_pengirim").hide();
			$("#perwilayah").hide();
			$("#pertanggal_wilayah").hide();
			$("#perbulan_wilayah").hide();
			$("#rekap").hide();
		}else if(ctk=='31'){
			$("#tagih").hide();
			$("#pertanggal").hide();
			$("#perpengirim").show();
			$("#pertanggal_pengirim").show();
			$("#perbulan_pengirim").hide();
			$("#perwilayah").hide();
			$("#pertanggal_wilayah").hide();
			$("#perbulan_wilayah").hide();
			$("#rekap").hide();
		}else if(ctk=='32'){
			$("#tagih").hide();
			$("#pertanggal").hide();
			$("#perpengirim").show();
			$("#pertanggal_pengirim").hide();
			$("#perbulan_pengirim").show();
			$("#perwilayah").hide();
			$("#pertanggal_wilayah").hide();
			$("#perbulan_wilayah").hide();
			$("#rekap").hide();
		}else if(ctk=='4'){
			$("#tagih").hide();
			$("#pertanggal").hide();
			$("#perpengirim").hide();
			$("#pertanggal_pengirim").hide();
			$("#perwilayah").hide();
			$("#pertanggal_wilayah").hide();
			$("#perbulan_wilayah").hide();
			$("#rekap").show();
		}else if(ctk=='5'){
			$("#tagih").hide();
			$("#pertanggal").hide();
			$("#perpengirim").hide();
			$("#pertanggal_pengirim").hide();
			$("#perbulan_pengirim").hide();
			$("#perwilayah").show();
			$("#pertanggal_wilayah").hide();
			$("#perbulan_wilayah").hide();
			$("#rekap").hide();
		}else if(ctk=='51'){
			$("#tagih").hide();
			$("#pertanggal").hide();
			$("#perpengirim").hide();
			$("#pertanggal_pengirim").hide();
			$("#perbulan_pengirim").hide();
			$("#perwilayah").show();
			$("#pertanggal_wilayah").show();
			$("#perbulan_wilayah").hide();
			$("#rekap").hide();
		}else if(ctk=='52'){
			$("#tagih").hide();
			$("#pertanggal").hide();
			$("#perpengirim").hide();
			$("#pertanggal_pengirim").hide();
			$("#perbulan_pengirim").hide();
			$("#perwilayah").show();
			$("#pertanggal_wilayah").hide();
			$("#perbulan_wilayah").show();
			$("#rekap").hide();
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
			//pdf atau layar
			var cetk            = ctak;
			
			//pilihan radio button
			var cetak1          = ctk;
			
			
			var no_halaman      = document.getElementById('no_halaman').value;
			
			//pilihan perbulan
			var bulan           = document.getElementById('bulan').value;
			
			//pertanggal
			var tgl_ttd         = $('#tgl_ttd').datebox('getValue');
			var tgl_ttd1        = $('#tgl_ttd1').datebox('getValue');
			
			
			//perpengirim
			var id_pengirim     = $('#id_pengirim').combogrid('getValue'); 		
			var tgl_ttd_pengirim  = $('#tgl_ttd_pengirim').datebox('getValue');
			var tgl_ttd1_pengirim = $('#tgl_ttd1_pengirim').datebox('getValue');
			var pengirim_bulan  = document.getElementById('pengirim_bulan').value;
			var pengirim_bulan1 = document.getElementById('pengirim_bulan1').value;
			
			//perwilayah
			var id_wilayah     = $('#id_wilayah').combogrid('getValue'); 		
			var tgl_ttd_wilayah  = $('#tgl_ttd_wilayah').datebox('getValue');
			var tgl_ttd1_wilayah = $('#tgl_ttd1_wilayah').datebox('getValue');
			var wilayah_bulan  = document.getElementById('wilayah_bulan').value;
			var wilayah_bulan1 = document.getElementById('wilayah_bulan1').value;
			
			//rekap
			var rekap_bulan     = document.getElementById('rekap_bulan').value;
			var rekap_bulan1    = document.getElementById('rekap_bulan1').value;
					
			if(cetak1=='1'){
				var url        = "<?php echo site_url(); ?>tukd/ctk_penerimaan_pajak_daerah";
				window.open(url+'/'+bulan+'/'+no_halaman+'/'+cetk+'/'+cetak1+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-', '_blank');
				window.focus();
			}else if(cetak1=='2'){
				var url        = "<?php echo site_url(); ?>tukd/ctk_penerimaan_pajak_daerah";
				window.open(url+'/'+'-'+'/'+no_halaman+'/'+cetk+'/'+cetak1+'/'+tgl_ttd+'/'+tgl_ttd1+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-', '_blank');
				window.focus();
			}else if(cetak1=='31'){
				var url        = "<?php echo site_url(); ?>tukd/ctk_penerimaan_pajak_daerah";
				window.open(url+'/'+'-'+'/'+no_halaman+'/'+cetk+'/'+cetak1+'/'+'-'+'/'+'-'+'/'+id_pengirim+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+tgl_ttd_pengirim+'/'+tgl_ttd1_pengirim+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-', '_blank');
				window.focus();
			}else if(cetak1=='32'){
				var url        = "<?php echo site_url(); ?>tukd/ctk_penerimaan_pajak_daerah";
				window.open(url+'/'+'-'+'/'+no_halaman+'/'+cetk+'/'+cetak1+'/'+'-'+'/'+'-'+'/'+id_pengirim+'/'+pengirim_bulan+'/'+pengirim_bulan1+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-', '_blank');
				window.focus();
			}else if(cetak1=='51'){
				var url        = "<?php echo site_url(); ?>tukd/ctk_penerimaan_pajak_daerah";
				window.open(url+'/'+'-'+'/'+no_halaman+'/'+cetk+'/'+cetak1+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+id_wilayah+'/'+tgl_ttd_wilayah+'/'+tgl_ttd1_wilayah+'/'+'-'+'/'+'-', '_blank');
				window.focus();
			}else if(cetak1=='52'){
				var url        = "<?php echo site_url(); ?>tukd/ctk_penerimaan_pajak_daerah";
				window.open(url+'/'+'-'+'/'+no_halaman+'/'+cetk+'/'+cetak1+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+id_wilayah+'/'+'-'+'/'+'-'+'/'+wilayah_bulan+'/'+wilayah_bulan1, '_blank');
				window.focus();
			}else{
				var url        = "<?php echo site_url(); ?>tukd/ctk_penerimaan_pajak_daerah";
				window.open(url+'/'+'-'+'/'+no_halaman+'/'+cetk+'/'+cetak1+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+rekap_bulan+'/'+rekap_bulan1+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-'+'/'+'-', '_blank');
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



<h3>PENERIMAAN PAJAK DAERAH</h3>
<div id="accordion">
    
    <p align="right">         
        <table border="0" id="sp2d" title="Cetak Daftar Potongan Pajak" style="width:922px;height:200px;" >
		<tr>
			<td colspan="4">
				<table style="width:100%;" border="0">
					<tr>
					<td ><input type="radio" name="cetak" value="1" id="status" onclick="opt(this.value)" /><b>Per Bulan</b>
						<div id="tagih">
                        <table >
                            <tr >
                    			<td ><B>Bulan</B></td>
                    			<td ><select name="bulan" id="bulan" onchange="klik()">    
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
                    		</tr>
                        </table> 
						</div>
					</td>
					</tr>
					<tr>
					<td ><input type="radio" name="cetak" value="2" id="status" onclick="opt(this.value)" /><b>Per Tanggal</b>
						<div id="pertanggal">
                        <table >
                            <tr >
                    			<td width="20%">Tanggal Kas</td>
								<td><input type="text" id="tgl_ttd" style="width: 100px;" /></td>
								<td width="10%" align="center">Tanggal Kas Sebelumnya</td>
								<td><input type="text" id="tgl_ttd1" style="width: 100px;" /></td>
								<td width="20%"></td>
                    		</tr>
                        </table> 
						</div>
					</td>
					</tr>
					<tr>
					<td ><input type="radio" name="cetak" value="3" id="status" onclick="opt(this.value)" /><b>Per Pengirim</b>
						<div id="perpengirim">
                        <table >
							<tr>
								<td >Nama Pengirim</td>
                    			<td ><input id="id_pengirim" name="id_pengirim" /><input id="nm_pengirim" name="nm_pengirim" style="width: 150px;border:0;" /></td>
                    		</tr>
							<tr>
								<td >&nbsp;&nbsp;&nbsp;<input type="radio" name="cetak" value="31" id="status" onclick="opt(this.value)" /><b>Per Tanggal</b>
									<div id="pertanggal_pengirim">
									<table >
										<tr >
											<td width="20%">Tanggal Kas</td>
											<td><input type="text" id="tgl_ttd_pengirim" style="width: 100px;" /></td>
											<td width="10%" align="center">Tanggal Kas Sebelumnya</td>
											<td><input type="text" id="tgl_ttd1_pengirim" style="width: 100px;" /></td>
											<td width="20%"></td>
										</tr>
									</table> 
									</div>
								</td>
							</tr>
							<tr>
								<td >&nbsp;&nbsp;&nbsp;<input type="radio" name="cetak" value="32" id="status" onclick="opt(this.value)" /><b>Per Bulan</b>
									<div id="perbulan_pengirim">
									<table >
										<tr >
										<td ><select name="pengirim_bulan" id="pengirim_bulan" onchange="klik()">    
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
										<td >s/d</td>
										<td ><select name="pengirim_bulan1" id="pengirim_bulan1" onchange="klik()">    
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
										</tr>
									</table> 
									</div>
								</td>
							</tr>
							
                        </table> 
						</div>
					</td>
					</tr>
					<tr>
					<td ><input type="radio" name="cetak" value="5" id="status" onclick="opt(this.value)" /><b>Per Wilayah</b>
						<div id="perwilayah">
                        <table >
							<tr>
								<td >Nama Wilayah</td>
                    			<td ><input id="id_wilayah" name="id_wilayah" /><input id="nm_wilayah" name="nm_wilayah" style="width: 150px;border:0;" /></td>
                    		</tr>
							<tr>
								<td >&nbsp;&nbsp;&nbsp;<input type="radio" name="cetak" value="51" id="status" onclick="opt(this.value)" /><b>Per Tanggal</b>
									<div id="pertanggal_wilayah">
									<table >
										<tr >
											<td width="20%">Tanggal Kas</td>
											<td><input type="text" id="tgl_ttd_wilayah" style="width: 100px;" /></td>
											<td width="10%" align="center">Tanggal Kas Sebelumnya</td>
											<td><input type="text" id="tgl_ttd1_wilayah" style="width: 100px;" /></td>
											<td width="20%"></td>
										</tr>
									</table> 
									</div>
								</td>
							</tr>
							<tr>
								<td >&nbsp;&nbsp;&nbsp;<input type="radio" name="cetak" value="52" id="status" onclick="opt(this.value)" /><b>Per Bulan</b>
									<div id="perbulan_wilayah">
									<table >
										<tr >
										<td ><select name="wilayah_bulan" id="wilayah_bulan" onchange="klik()">    
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
										<td >s/d</td>
										<td ><select name="wilayah_bulan1" id="wilayah_bulan1" onchange="klik()">    
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
										</tr>
									</table> 
									</div>
								</td>
							</tr>
							
                        </table> 
						</div>
					</td>
					</tr>
					<tr>
					<td ><input type="radio" name="cetak" value="4" id="status" onclick="opt(this.value)" /><b>Rekap</b>
						<div id="rekap">
                        <table >
							<tr>
								<td >Bulan</td>
                    			<td ><select name="rekap_bulan" id="rekap_bulan" onchange="klik()">    
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
								<td >s/d</td>
                    			<td ><select name="rekap_bulan1" id="rekap_bulan1" onchange="klik()">    
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
                    		</tr>
                        </table> 
						</div>
					</td>
					</tr>
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