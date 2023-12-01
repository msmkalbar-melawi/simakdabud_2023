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
			//$("#kdrek5").attr("value",rowData.kd_rek5);
			$("#nmrek3").attr("value",rowData.nm_rek3);
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
		
		$('#ttdx1').combogrid({  
                panelWidth:180,  
                url: '<?php echo base_url(); ?>/index.php/lamp_perda/load_ttd_perda',  
                    idField:'nip',                    
                    textField:'nama',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'nama',title:'NAMA',align:'left',width:170}  
                    ]],
                    onSelect:function(rowIndex,rowData){
                    nip = rowData.nip; 
                    }
                       
                });
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


		function cetak(ctk)
        {
			var skpd   = kdskpd; 
			var bulan = document.getElementById("bulan").value;  
            var anggaran = document.getElementById("anggaran").value;  
            var ttd1 = $('#ttdx1').combogrid('getValue'); 
			var  ctglttd = $('#tgl_ttd').datebox('getValue');
			if(ctglttd==''){
				ctglttd="-";
			}
			if(ttd1==''){
				ttd="-";
			}else{
				ttd=ttd1.split(' ').join('n');
			}
			var url    = "<?php echo site_url(); ?>perda/cetak_perda_lampI_4";  
			window.open(url+'/'+bulan+'/'+anggaran+'/'+ctk+'/'+ctglttd+'/'+ttd, '_blank');
			window.focus();
        }

        function cetak1(ctk)
        {
			var skpd   = kdskpd; 
			var bulan = document.getElementById("bulan").value;  
            var anggaran = document.getElementById("anggaran").value;  
            var ttd1 = $('#ttdx1').combogrid('getValue'); 
			var  ctglttd = $('#tgl_ttd').datebox('getValue');
			if(ctglttd==''){
				ctglttd="-";
			}
			if(ttd1==''){
				ttd="-";
			}else{
				ttd=ttd1.split(' ').join('n');
			}
			var url    = "<?php echo site_url(); ?>perdase/cetak_perda_lampI_5_SE_2022";  
			window.open(url+'/'+bulan+'/'+anggaran+'/'+ctk+'/'+ctglttd+'/'+ttd, '_blank');
			window.focus();
        }

        function cetak2(ctk)
        {
			var skpd   = kdskpd; 
			var bulan = document.getElementById("bulan").value;  
            var anggaran = document.getElementById("anggaran").value;  
            var ttd1 = $('#ttdx1').combogrid('getValue'); 
			var  ctglttd = $('#tgl_ttd').datebox('getValue');
			if(ctglttd==''){
				ctglttd="-";
			}
			if(ttd1==''){
				ttd="-";
			}else{
				ttd=ttd1.split(' ').join('n');
			}
			var url    = "<?php echo site_url(); ?>perdase/cetak_perda_lampI_5_SE_BU_2022";  
			window.open(url+'/'+bulan+'/'+anggaran+'/'+ctk+'/'+ctglttd+'/'+ttd, '_blank');
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


<h3>CETAK PERDA LAMP. 1.5 </h3>
    <p align="right">         
        <table id="sp2d" title="Cetak Perda Lamp. 1" style="width:922px;height:200px;" >  
		    <tr>
                <td width="20%" > <b>Periode<b></td> 
                    <td width="70%">
                        <select  name="bulan" id="bulan" style="width: 180px;">
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April </option>
                            <option value="5">Mei </option>
                            <option value="6">Juni </option>
                            <option value="7">Juli </option>
                            <option value="8">Agustus </option>
                            <option value="9">September </option>
                            <option value="10">Oktober </option>
                            <option value="11">Nopember </option>
                            <option value="12">Desember </option>
                        </select>
                    </td> 
            </tr>
            <tr>
                 <td> <b>Anggaran<b></td> 
                    <td>
                    <select name="anggaran" id="anggaran" style="width: 180px;">
                        <option value="M">Penetapan</option>
                        <option value="P1">Penyempurnaan I</option>
                        <option value="P2">Penyempurnaan II</option>
                        <option value="P3">Penyempurnaan III</option>
                        <option value="P4">Penyempurnaan IV</option>
                        <option value="P5">Penyempurnaan V</option>
                        <option value="U1">Perubahan I</option>
                        <option value="U2">Perubahan II</option>
                    </select>
                    </td>
            </tr>
            <tr>
                <td><b>Tanggal TTD</b></td>
                <td>&nbsp;&nbsp;<input type="text" id="tgl_ttd" style="width: 180px;" /> </td> 
            </tr>
            <tr>
                <td><b>Penandatanganan</b></td>
                <td>&nbsp;&nbsp;<input type="text" id="ttdx1" style="width: 180px;" /> </td> 
            </tr>
            <tr >
                <td >&nbsp;&nbsp;</td>
                <td >&nbsp;&nbsp;</td>
            </tr>
                <tr hidden>
                    <td colspan="2" width="80%"> <b>Cetak PERDA</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="button" iconCls="icon-print" plain="true" onclick="javascript:cetak(0);"><i class="fa fa-print"></i> Cetak Layar</a>
                        <a class="button" iconCls="icon-pdf" plain="true" onclick="javascript:cetak(1);"><i class="fa fa-pdf"></i> Cetak PDF</a>
                        <a class="button" iconCls="icon-excel" plain="true" onclick="javascript:cetak(2);"><i class="fa fa-excel"></i> Cetak Excel</a>
                        <!--<a class="button" iconCls="icon-word" plain="true" onclick="javascript:cetak(3);">Word</a></td>-->
                    </td>
                </tr>
                <tr >
                    <td colspan="2" width="80%"><b> Cetak PERDA (Fungsi - Sub Fungsi) </b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="button" iconCls="icon-print" plain="true" onclick="javascript:cetak1(0);"><i class="fa fa-print"></i> Cetak Layar</a>
                        <a class="button" iconCls="icon-pdf" plain="true" onclick="javascript:cetak1(1);"><i class="fa fa-pdf"></i> Cetak PDF</a>
                        <a class="button" iconCls="icon-excel" plain="true" onclick="javascript:cetak1(2);"><i class="fa fa-excel"></i> Cetak Excel</a>
                        <!--<a class="button" iconCls="icon-word" plain="true" onclick="javascript:cetak(3);">Word</a></td>-->
                    </td>
                </tr>
                <tr >
                    <td colspan="2" width="80%"><b> Cetak PERDA (Fungsi - Bidang Urusan)</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="button" iconCls="icon-print" plain="true" onclick="javascript:cetak2(0);"><i class="fa fa-print"></i> Cetak Layar</a>
                        <a class="button" iconCls="icon-pdf" plain="true" onclick="javascript:cetak2(1);"><i class="fa fa-pdf"></i> Cetak PDF</a>
                        <a class="button" iconCls="icon-excel" plain="true" onclick="javascript:cetak2(2);"><i class="fa fa-excel"></i> Cetak Excel</a>
                        <!--<a class="button" iconCls="icon-word" plain="true" onclick="javascript:cetak(3);">Word</a></td>-->
                    </td>
                </tr>
            <tr >
                <td ></td>
                <td ></td>
            </tr>
        </table>                      
    </p> 
</div>
</body>
</html>