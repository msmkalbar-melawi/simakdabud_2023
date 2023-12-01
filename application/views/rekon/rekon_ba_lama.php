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
			isi1(kdskpd);
			isi2(kdskpd);
			isi3(kdskpd);
			isi4(kdskpd);
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
		
            $('#ttd1').combogrid({  
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/rekon_ba/load_ttd',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},  
                    {field:'nama',title:'Nama',width:400}    
                ]],
				onSelect:function(rowIndex,rowData){
					$("#nmak").attr("value",rowData.nama);
				}   
            });          
    }); 
		 
	$(function(){ 	  
            $('#ttd2').combogrid({  
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/rekon_ba/load_ttd',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},  
                    {field:'nama',title:'Nama',width:400}    
                ]],
				onSelect:function(rowIndex,rowData){
					$("#nmkas").attr("value",rowData.nama);
				}   
            });          
         
	});	 
	
	function isi1(kdskpd){
		$(function(){		  
					$('#ttd3').combogrid({  
						panelWidth:600,  
						idField:'nip',  
						textField:'nip',  
						mode:'remote',
						url:'<?php echo base_url(); ?>index.php/rekon_ba/load_ttd_neraca/'+kdskpd,  
						columns:[[  
							{field:'nip',title:'NIP',width:200},  
							{field:'nama',title:'Nama',width:400}    
						]],
						onSelect:function(rowIndex,rowData){
							$("#ppk").attr("value",rowData.nama);
						}   
					});          
		});		
	}
	 
	$(function(){	
            $('#ttd0').combogrid({  
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/rekon_ba/load_ttd',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},  
                    {field:'nama',title:'Nama',width:400}    
                ]],
				onSelect:function(rowIndex,rowData){
					$("#nmkba").attr("value",rowData.nama);
				}   
            });          
    });	     
	
	function isi4(kdskpd){
	$(function(){	   
            $('#ttd4').combogrid({  
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/rekon_ba/load_ttd_neraca/'+kdskpd,  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},  
                    {field:'nama',title:'Nama',width:400}    
                ]],
				onSelect:function(rowIndex,rowData){
					$("#pns").attr("value",rowData.nama);
				}   
            });          
    });	    
	}

	function isi2(kdskpd){
		$(function(){	  
				$('#ttd5').combogrid({  
					panelWidth:600,  
					idField:'nip',  
					textField:'nip',  
					mode:'remote',
					url:'<?php echo base_url(); ?>index.php/rekon_ba/load_ttd_terima/'+kdskpd,  
					columns:[[  
						{field:'nip',title:'NIP',width:200},  
						{field:'nama',title:'Nama',width:400}    
					]],
					onSelect:function(rowIndex,rowData){
						$("#bpen").attr("value",rowData.nama);
					}   
					   
			 });
		});	 
	}
	
	function isi3(kdskpd){
		$(function(){
				$('#ttd6').combogrid({  
					panelWidth:600,  
					idField:'nip',  
					textField:'nip',  
					mode:'remote',
					url:'<?php echo base_url(); ?>index.php/rekon_ba/load_ttd_keluar/'+kdskpd,  
					columns:[[  
						{field:'nip',title:'NIP',width:200},  
						{field:'nama',title:'Nama',width:400}    
					]],
					onSelect:function(rowIndex,rowData){
						$("#bpeng").attr("value",rowData.nama);
					}   
				});          
		});	   
	}
	
	//cdate = '<?php echo date("Y-m-d"); ?>';
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

		function cetak0(pilih){
			var skpd    = kdskpd;
			var tgl_ttd =  $('#dcetak').datebox('getValue')
			var ttd0   = $('#ttd0').combogrid('getValue'); // ka bid akun
			var ttd1   = $('#ttd1').combogrid('getValue'); // staf akun
			var ttd2   = $('#ttd2').combogrid('getValue'); //kasubid
			var ttd3   = $('#ttd3').combogrid('getValue'); //ppk
			var ttd4   = $('#ttd4').combogrid('getValue'); //penyusun
			var ttd5   = $('#ttd5').combogrid('getValue'); //terima
			var ttd6   = $('#ttd6').combogrid('getValue'); //keluar
			
			
			if(skpd==''){
				alert("Pilih SKPD dahulu");
				exit();
			}
			
			if(ttd0==''){ 
				var ttd0   = "-"; // ka bid akun
			}else if(ttd1==''){	
				var ttd1   = "-"; // staf akun
			}else if(ttd2==''){	
				var ttd2   = "-"; //kasubid
			}else if(ttd3==''){		
				var ttd3   = "-"; //ppk
			}else if(ttd4==''){
				var ttd4   = "-"; //penyusun
			}else if(ttd5==''){
				var ttd5   = "-"; //terima
			}else if(ttd6==''){	
				var ttd6   = "-"; //keluar
			}
			
			if(pilih=='3'){
				var url    = "<?php echo site_url(); ?>rekon_ba/cetak_ringkasan_penerimaan"; 
				window.open(url+'/'+skpd+'/'+pilih+'/'+ttd0+'/'+ttd1+'/'+ttd2+'/'+ttd3+'/'+tgl_ttd+'/'+ttd4+'/'+ttd5+'/'+ttd6, '_blank');
				window.focus();
			}else{
			
				var url    = "<?php echo site_url(); ?>rekon_ba/cetak_ba_penerimaan"; 
				window.open(url+'/'+skpd+'/'+pilih+'/'+ttd0+'/'+ttd1+'/'+ttd2+'/'+ttd3+'/'+tgl_ttd+'/'+ttd4+'/'+ttd5+'/'+ttd6, '_blank');
				window.focus();
			}
        }
	
		function cetak(pilih){
			var skpd    = kdskpd;
			var tgl_ttd =  $('#dcetak').datebox('getValue')
			var ttd0   = $('#ttd0').combogrid('getValue'); // ka bid akun
			var ttd1   = $('#ttd1').combogrid('getValue'); // staf akun
			var ttd2   = $('#ttd2').combogrid('getValue'); //kasubid
			var ttd3   = $('#ttd3').combogrid('getValue'); //ppk
			var ttd4   = $('#ttd4').combogrid('getValue'); //penyusun
			var ttd5   = $('#ttd5').combogrid('getValue'); //terima
			var ttd6   = $('#ttd6').combogrid('getValue'); //keluar
			
			if(skpd==''){
				alert("Pilih SKPD dahulu");
				exit();
			}
			
			if(ttd0==''){ 
				var ttd0   = "-"; // ka bid akun
			}else if(ttd1==''){	
				var ttd1   = "-"; // staf akun
			}else if(ttd2==''){	
				var ttd2   = "-"; //kasubid
			}else if(ttd3==''){		
				var ttd3   = "-"; //ppk
			}else if(ttd4==''){
				var ttd4   = "-"; //penyusun
			}else if(ttd5==''){
				var ttd5   = "-"; //terima
			}else if(ttd6==''){	
				var ttd6   = "-"; //keluar
			}
			
			var url    = "<?php echo site_url(); ?>rekon_ba/cetak_ba_neraca"; 
			window.open(url+'/'+skpd+'/'+pilih+'/'+ttd0+'/'+ttd1+'/'+ttd2+'/'+ttd3+'/'+tgl_ttd+'/'+ttd4+'/'+ttd5+'/'+ttd6, '_blank');
			window.focus();
        }
	
		function cetak1(pilih){
			var skpd   = kdskpd;
			var tgl_ttd =  $('#dcetak').datebox('getValue')
			var ttd0   = $('#ttd0').combogrid('getValue');
			var ttd1   = $('#ttd1').combogrid('getValue');
			var ttd2   = $('#ttd2').combogrid('getValue');
			var ttd3   = $('#ttd3').combogrid('getValue');
			var ttd4   = $('#ttd4').combogrid('getValue');
			var ttd5   = $('#ttd5').combogrid('getValue');
			var ttd6   = $('#ttd6').combogrid('getValue');
			
			if(skpd==''){
				alert("Pilih SKPD dahulu");
				exit();
			}
			
			if(ttd0==''){ 
				var ttd0   = "-"; // ka bid akun
			}else if(ttd1==''){	
				var ttd1   = "-"; // staf akun
			}else if(ttd2==''){	
				var ttd2   = "-"; //kasubid
			}else if(ttd3==''){		
				var ttd3   = "-"; //ppk
			}else if(ttd4==''){
				var ttd4   = "-"; //penyusun
			}else if(ttd5==''){
				var ttd5   = "-"; //terima
			}else if(ttd6==''){	
				var ttd6   = "-"; //keluar
			}
			
			if(pilih=='3'){
				var url    = "<?php echo site_url(); ?>rekon_ba/cetak_ringkasan_pengeluaran";
				window.open(url+'/'+skpd+'/'+pilih+'/'+ttd0+'/'+ttd1+'/'+ttd2+'/'+ttd3+'/'+tgl_ttd+'/'+ttd4+'/'+ttd5+'/'+ttd6, '_blank');
				window.focus();				
			}else{
				var url    = "<?php echo site_url(); ?>rekon_ba/cetak_ba_pengeluaran"; 
				window.open(url+'/'+skpd+'/'+pilih+'/'+ttd0+'/'+ttd1+'/'+ttd2+'/'+ttd3+'/'+tgl_ttd+'/'+ttd4+'/'+ttd5+'/'+ttd6, '_blank');
				window.focus();
			}
        }
		
		function cetak2(pilih){
			var skpd   = kdskpd; 
			var tgl_ttd =  $('#dcetak').datebox('getValue')
			var ttd0   = $('#ttd0').combogrid('getValue');
			var ttd1   = $('#ttd1').combogrid('getValue');
			var ttd2   = $('#ttd2').combogrid('getValue');
			var ttd3   = $('#ttd3').combogrid('getValue');
			var ttd4   = $('#ttd4').combogrid('getValue');
			var ttd5   = $('#ttd5').combogrid('getValue');
			var ttd6   = $('#ttd6').combogrid('getValue');
			
			
			if(skpd==''){
				alert("Pilih SKPD dahulu");
				exit();
			}
			
			if(ttd0==''){ 
				var ttd0   = "-"; // ka bid akun
			}else if(ttd1==''){	
				var ttd1   = "-"; // staf akun
			}else if(ttd2==''){	
				var ttd2   = "-"; //kasubid
			}else if(ttd3==''){		
				var ttd3   = "-"; //ppk
			}else if(ttd4==''){
				var ttd4   = "-"; //penyusun
			}else if(ttd5==''){
				var ttd5   = "-"; //terima
			}else if(ttd6==''){	
				var ttd6   = "-"; //keluar
			}
			
			var url    = "<?php echo site_url(); ?>rekon_ba/cetak_ba_lo"; 
			window.open(url+'/'+skpd+'/'+pilih+'/'+ttd0+'/'+ttd1+'/'+ttd2+'/'+ttd3+'/'+tgl_ttd+'/'+ttd4+'/'+ttd5+'/'+ttd6, '_blank');
			window.focus();
        }
		
		function cetak3(pilih){
			var skpd   = kdskpd;
			var tgl_ttd =  $('#dcetak').datebox('getValue')
			var ttd0   = $('#ttd0').combogrid('getValue');
			var ttd1   = $('#ttd1').combogrid('getValue');
			var ttd2   = $('#ttd2').combogrid('getValue');
			var ttd3   = $('#ttd3').combogrid('getValue');
			var ttd4   = $('#ttd4').combogrid('getValue');
			var ttd5   = $('#ttd5').combogrid('getValue');
			var ttd6   = $('#ttd6').combogrid('getValue');
			
			
			if(skpd==''){
				alert("Pilih SKPD dahulu");
				exit();
			}
			
			if(ttd0==''){ 
				var ttd0   = "-"; // ka bid akun
			}else if(ttd1==''){	
				var ttd1   = "-"; // staf akun
			}else if(ttd2==''){	
				var ttd2   = "-"; //kasubid
			}else if(ttd3==''){		
				var ttd3   = "-"; //ppk
			}else if(ttd4==''){
				var ttd4   = "-"; //penyusun
			}else if(ttd5==''){
				var ttd5   = "-"; //terima
			}else if(ttd6==''){	
				var ttd6   = "-"; //keluar
			}
			
			if(pilih=='3'){
				var url    = "<?php echo site_url(); ?>rekon_ba/cetak_ringkasan_lra"; 
				window.open(url+'/'+skpd+'/'+pilih+'/'+ttd0+'/'+ttd1+'/'+ttd2+'/'+ttd3+'/'+tgl_ttd+'/'+ttd4+'/'+ttd5+'/'+ttd6, '_blank');
				window.focus();
			}else{
				var url    = "<?php echo site_url(); ?>rekon_ba/cetak_ba_lra"; 
				window.open(url+'/'+skpd+'/'+pilih+'/'+ttd0+'/'+ttd1+'/'+ttd2+'/'+ttd3+'/'+tgl_ttd+'/'+ttd4+'/'+ttd5+'/'+ttd6, '_blank');
				window.focus();
			}
        }
		
		function cetak4(pilih){
			var skpd   = kdskpd;
			var tgl_ttd =  $('#dcetak').datebox('getValue');
			var ttd0   = $('#ttd0').combogrid('getValue');
			var ttd1   = $('#ttd1').combogrid('getValue');
			var ttd2   = $('#ttd2').combogrid('getValue');
			var ttd3   = $('#ttd3').combogrid('getValue');
			var ttd4   = $('#ttd4').combogrid('getValue');
			var ttd5   = $('#ttd5').combogrid('getValue');
			var ttd6   = $('#ttd6').combogrid('getValue');
			
			
			if(skpd==''){
				alert("Pilih SKPD dahulu");
				exit();
			}
			
			if(ttd0==''){ 
				var ttd0   = "-"; // ka bid akun
			}else if(ttd1==''){	
				var ttd1   = "-"; // staf akun
			}else if(ttd2==''){	
				var ttd2   = "-"; //kasubid
			}else if(ttd3==''){		
				var ttd3   = "-"; //ppk
			}else if(ttd4==''){
				var ttd4   = "-"; //penyusun
			}else if(ttd5==''){
				var ttd5   = "-"; //terima
			}else if(ttd6==''){	
				var ttd6   = "-"; //keluar
			}
			
			var url    = "<?php echo site_url(); ?>rekon_ba/cetak_ba_lpe"; 
			window.open(url+'/'+skpd+'/'+pilih+'/'+ttd0+'/'+ttd1+'/'+ttd2+'/'+ttd3+'/'+tgl_ttd+'/'+ttd4+'/'+ttd5+'/'+ttd6, '_blank');
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


<h3>CETAK BA</h3>
<div id="accordion">

    <p align="center">         
        <table>
		<tr >
			<td width="22px" height="40%" ><B>Unit&nbsp;&nbsp;</B></td>
			<td width="900px"><input id="sskpd" name="sskpd" style="width: 100px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 670px; border:0;" /></td>
		</tr>
		<tr>
			<td ><B>Tanggal Cetak</B></td>
            <td><input type="text" id="dcetak" style="width: 100px;" />
			</td> 
		</tr>
		<tr>
			<td ><B>Ka Bid Akuntansi</B></td>
            <td><input type="text" id="ttd0" style="width: 180px;" /><input id="nmkba" name="nmkba" style="width: 300px; border:0;" />
			</td> 
		</tr>
		<tr>
			<td ><B>Staf Akuntansi</B></td>
            <td><input type="text" id="ttd1" style="width: 180px;" /><input id="nmak" name="nmak" style="width: 300px; border:0;" />
			</td> 
		</tr>
		<tr>
			<td ><B>Kasubbid</B></td>
            <td><input type="text" id="ttd2" style="width: 180px;" /><input id="nmkas" name="nmkas" style="width: 300px; border:0;" />
			</td> 
		</tr>
		<tr>
			<td ><B>PPK-SKPD</B></td>
            <td><input type="text" id="ttd3" style="width: 180px;" /><input id="ppk" name="ppk" style="width: 300px; border:0;" />
			</td> 
		</tr>
		<tr>
			<td ><B>Ben. Penerimaan</B></td>
            <td><input type="text" id="ttd5" style="width: 180px;" /><input id="bpen" name="bpen" style="width: 300px; border:0;" />
			</td> 
		</tr>
		<tr>
			<td ><B>Bend. Pengeluaran</B></td>
            <td><input type="text" id="ttd6" style="width: 180px;" /><input id="bpeng" name="bpeng" style="width: 300px; border:0;" />
			</td> 
		</tr>
		<tr>
			<td ><B>Penyusun Neraca SKPD</B></td>
            <td><input type="text" id="ttd4" style="width: 180px;" /><input id="pns" name="pns" style="width: 300px; border:0;" />
			</td> 
		</tr>
		<tr>
			<td ></td>
            <td></td> 
		</tr>
		<tr >
			<td> PENGELUARAN</td> 
			<td align="left"> 
            <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak1(1);">Layar</a>
            <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak1(0);">PDF</a>
            <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak1(2);">excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>|</b>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			RINGKASAN
			&nbsp;&nbsp;&nbsp;
			<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak1(3);">Layar</a>
			</td>
		</tr>
		<tr >
			<td> PENERIMAAN</td> 
			<td align="left"> 
            <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak0(1);">Layar</a>
            <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak0(0);">PDF</a>
            <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak0(2);">excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>|</b>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			RINGKASAN
			&nbsp;&nbsp;&nbsp;
			<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak0(3);">Layar</a>
			</td>
		</tr>
		<tr >
			<td> LRA</td> 
			<td align="left"> 
            <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak3(1);">Layar</a>
            <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak3(0);">PDF</a>
            <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak3(2);">excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>|</b>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			RINGKASAN
			&nbsp;&nbsp;&nbsp;
			<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak3(3);">Layar</a>
			</td>
		</tr>
		<tr >
			<td> LO</td> 
			<td align="left"> 
            <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak2(1);">Layar</a>
            <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak2(0);">PDF</a>
            <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak2(2);">excel</a>
			</td>
		</tr>
		<tr >
			<td> NERACA</td> 
			<td align="left"> 
            <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak(1);">Layar</a>
            <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak(0);">PDF</a>
            <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak(2);">excel</a>
			</td>
		</tr>
		<tr >
			<td> LPE</td> 
			<td align="left"> 
            <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak4(1);">Layar</a>
            <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak4(0);">PDF</a>
            <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak4(2);">excel</a>
			</td>
		</tr>
        </table>                      
    </p> 
  </div> 
</div>

 	
</body>

</html>