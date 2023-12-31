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
	 
		$('#tgl_ttd').datebox({  
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
                url: '<?php echo base_url(); ?>/index.php/lamp_perda/load_ttd_perda',  
                    idField:'nip',                    
                    textField:'nama',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'nama',title:'NAMA',align:'left',width:100}  
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
	
 function validate_ttd(){
   $(function(){
            $('#ttd').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/lamp_perda/load_ttd_perda/'+kdskpd,  
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
	 		

		function cetak(pilih)
        {
            var bulan = document.getElementById("bulan").value;  
            var anggaran = document.getElementById("anggaran").value;  
            
			var ttd1 = $('#ttd').combogrid('getValue');
			var  ctglttd = $('#tgl_ttd').datebox('getValue');
            if(ctglttd==''){
				ctglttd="-";
			}
			
			
			if(ttd1==''){
				ttd="-";
			}else{
				ttd=ttd1.split(' ').join('abc');
			}
			
			
			
			var url    = "<?php echo site_url(); ?>perdase/lampiran_11?c="+pilih; 
			
            $("#mantap").attr('action',url);

			/*window.open(url+'/'+bulan+'/'+pilih+'/'+anggaran+'/'+ttd+'/'+ctglttd, '_blank');
			window.focus();*/
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


<h3><?= $page_title ?></h3>
<div id="">

<form target="_blank", method="post" id="mantap">
 
    <p align="center">         
        <table id="sp2d" title="Cetak Perda Lamp. 1" width="100%" border="0">  
   		
	<tr>
	<td width="20%"> Periode</td> 
	<td width="70%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><select  name="bulan" id="bulan" >
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
   </select></td> 
   </tr>
   <tr>
                 <td> <b>Anggaran<b></td> 
                    <td>
                    <select name="anggaran" id="anggaran">
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
	<td>Tanggal TTD</td>
	<td><input type="text" id="tgl_ttd" name="tgl_ttd" style="width: 150px;" /> </td> 
	</tr>
   <tr>
	<td>Penandatanganan</td>
	<td><input type="text" id="ttd" name="ttd" style="width: 200px;" /> </td> 
	</tr>
	
		<tr>
			<td><b>CETAK PERDA</b></td> 
			<td align="left"> 
            <button class="easyui-linkbutton button"  plain="true" onclick="javascript:cetak(0);"><i class="fa fa-print"></i> Layar</button>
            <button class="easyui-linkbutton button"  plain="true" onclick="javascript:cetak(1);"><i class="fa fa-pdf"></i> PDF</button>
            <button class="easyui-linkbutton button"  plain="true" onclick="javascript:cetak(2);"><i class="fa fa-excel"></i> Excel</button>
			</td>
		</tr>
	
	</tr>
		
        </table> 
 </p> 
</form>                     
   
  </div> 
</div>

 	
</body>

</html>