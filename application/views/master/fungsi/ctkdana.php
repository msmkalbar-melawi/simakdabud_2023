<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.min.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
  <style>    
    #tagih {
        position: relative;
        width: 500px;
        height: 70px;
        padding: 0.4em;
    }  
    </style>
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
	   
    $('#tgl_ttd').datebox({  
        required:true,
        formatter :function(date){
        	var y = date.getFullYear();
        	var m = date.getMonth()+1;
        	var d = date.getDate();
        	return y+'-'+m+'-'+d;
        }
        
    });
    
    $('#jang').combogrid({  
                panelWidth:400,  
                idField:'kolom',  
                textField:'nama',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/anggaran/ambil_jns_anggaran',  
                columns:[[    
                    {field:'nama',title:'Nama',width:400}    
                ]]
            });
       
	$('#sskpd').combogrid({  
		panelWidth:630,  
		idField:'kd_skpd',  
		textField:'kd_skpd',  
		mode:'remote',
		url:'<?php echo base_url(); ?>index.php/akuntansi/skpd',  
		columns:[[  
			{field:'kd_skpd',title:'Kode SKPD',width:100},  
			{field:'nm_skpd',title:'Nama SKPD',width:500}    
		]],
		onSelect:function(rowIndex,rowData){
			kdskpd = rowData.kd_skpd;
			$("#nmskpd").attr("value",rowData.nm_skpd);
			$("#skpd").attr("value",rowData.kd_skpd);
            $(function(){
                $('#ttd1').combogrid({  
                    panelWidth:500,  
                    url: '<?php echo base_url(); ?>index.php/rka/load_ttd_unit/'+kdskpd,  
                        idField:'nip',  
                        textField:'nip',
                        mode:'remote',  
                        fitColumns:true
                });
            });
		},
		onChange:function(rowIndex,rowData){
				$("#ttd1").combogrid("setValue",'');
			}			
		}); 
	});

  
	$(function(){  
        $('#ttd1').combogrid({  
            panelWidth:400,  
            idField:'nip',  
            textField:'nip',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka/load_ttd_unit',  
            columns:[[  
                {field:'nip',title:'NIP',width:200},  
                {field:'nama',title:'Nama',width:400}    
            ]],
            onSelect:function(rowIndex,rowData){
                $("#ttd1").attr("value",rowData.nip);
                $("#nama").attr("value",rowData.nama);
            }  
        });  

        $('#kode_u').combogrid({  
       panelWidth:550,  
       idField:'kd_sdana',  
       textField:'kd_sdana',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/Lap_anggaran/ambil_sdana',  
       columns:[[  
           {field:'kd_sdana',title:'KODE SUMBERN DANA',width:150},  
           {field:'nm_sdana',title:'NAMA SUMBER DANA',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
            kd_sdana = rowData.kd_sdana;

            // $("#kode_u").attr("value",rowData.kd_sdana.toUpperCase());
            // $("#kode_u").combogrid("setValue",kd_sdana);
            $("#nmkode").attr("value",rowData.nm_sdana.toUpperCase());
            //muncul();                
       }  
     }); 

     });

      $(document).ready(function(){                
        kosong()                                             
     });

    
    function kosong(){
        //$cekall = ;

        //if ($('input[name="all"]:checked').val()=='1'){
        if(document.getElementById("all").checked == true){
            $("#kode_u").combogrid("setValue",'');
            $("#nmkode").attr("value",'');
            $("#sskpd").combogrid("setValue",'');
            $("#ttd1").combogrid("setValue",'');
            $("#nmskpd").attr("value",'');
            $("#sskpd").combogrid('disable');
            //$("#nomor_urut").attr("disabled", true); 
        $(function(){
        $('#ttd1').combogrid({  
            panelWidth:400,  
            idField:'nip',  
            textField:'nip',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka/load_ppkd',  
            columns:[[  
                {field:'nip',title:'NIP',width:200},  
                {field:'nama',title:'Nama',width:400}    
            ]],  
            onSelect:function(rowIndex,rowData){
                $("#ttd1").attr("value",rowData.nip);
                $("#nama").attr("value",rowData.nama);
            }
        });
        });          
 
        }else{
            $("#sskpd").combogrid("setValue",'');
            $("#nmskpd").attr("value",'');
            $("#sskpd").combogrid('enable');
            
            $(function(){
            $('#ttd1').combogrid({  
                panelWidth:400,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/rka/load_ttd_unit',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},  
                    {field:'nama',title:'Nama',width:400}    
                ]],  
                onSelect:function(rowIndex,rowData){
                    $("#ttd1").attr("value",rowData.nip);
                    $("#nama").attr("value",rowData.nama);
                }
            });
            });          
            $("#ttd1").combogrid("setValue",'');
        } 
    }

    function cek($cet){
             
            if (document.getElementById("all").checked == true){
                var skpd = '0';
            }else{
                var skpd   = kdskpd;
            } 
            if (skpd==''){
                alert('Pilih SKPD Terlebih Dahulu');
                exit();
                }else{
                   cetak($cet);   
                }
           
    }
    function cetak($ctk)
        {
            var cetak       = $ctk;
            var judul       = '';
            var jns         = $('#jang').combogrid('getValue');;
            var kd_sdana    = '';
            // var kd_sdana    = $('#kode_u').combogrid('getValue');
            
            if (document.getElementById("all").checked == true){
                var skpd = '0';
            }else{
                var skpd   = kdskpd;
            } 

            if (document.getElementById("lamp1").checked == true){
                var lamp1 = '1';
            }else{
                var lamp1 = '0';
            }
			
	           url="<?php echo site_url(); ?>lap_anggaran/preview_sumberdana/"+skpd+'/'+cetak+'/'+jns+'/'+lamp1+'/'+kd_sdana+'/Report_Sumber_Dana_-'+skpd;
                openWindow( url );
        }
     
     function runEffect() {
        var selectedEffect = 'blind';            
        var options = {};                      
        $( "#tagih" ).toggle( selectedEffect, options, 500 );
        };
        
      function pilih() {
       op = '1';       
      };   
        
    function openWindow( url ){
           var  ctglttd = $('#tgl_ttd').datebox('getValue');
           var  ttd = $('#ttd1').combogrid('getValue');
           var ttd1 = alltrim(ttd);
           
            lc = '?tgl_ttd='+ctglttd+'&ttd1='+ttd1+'';
			window.open(url+lc,'_blank');
			window.focus();
		  
     } 

	 function alltrim(kata){
	 //alert(kata);
		b = (kata.split(' ').join(''));
		c = (b.replace( /\s/g, ""));
		return c
	 
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



<h3>Cetak Sumber Dana</h3>
<div id="accordion">
    
    <p align="right">         
        <table id="sp2d" title="Cetak" style="width:922px;height:200px;" >  
        <tr style="border-bottom-style:hidden;">
			<td width="20%" height="40" ><B>KODE SKPD</B></td>
			<td width="80%"><input id="sskpd" name="sskpd" style="width: 200px;" /></td>
		</tr>
        <tr style="border-bottom-style:hidden;">
            <td width="20%" height="40" ><B>NAMA SKPD</B></td>
            <td width="80%"><input id="nmskpd" name="nmskpd" style="width: 500px; border:0;" /></td>
        </tr>
        <tr>
                <td width="20%"><B>JENIS</B></td>
                <td>
                    <input type="text" id="jang" style="width: 200px;" />
                </td>  
        </tr>
       <!--  <tr>
                <td width="30%">SUMBER DANA</td>
                
                <td><input type="text" id="kode_u" style="width:90px;"/>&nbsp;&nbsp;
                    <input type="text" id="nmkode" name="nmkode" style="width:350px;border:0" readonly="true" />
                </td>  
            </tr>  --> 
        
        <tr >
			<td width="20%" height="40" ><B>Penandatangan</B></td>
			<td width="80%"><input id="ttd1" name="ttd1" style="width: 200px;" />&nbsp;&nbsp;<input id="nama" name="nama" style="width: 300px; border:0;" /></td>
		</tr>

		<tr>
            <td width="20%"><B>TANGGAL TTD</B></td>
            <td><input type="text" id="tgl_ttd" style="width: 100px;" /> </td>
        </td> 
		</tr>
		      
        <tr>
            <td width="10%"><input type="checkbox" name="all" id="all"  onclick="kosong();"/> Keseluruhan</td> 
            <td width="10%"><input type="checkbox" name="lamp1" id="lamp1"  />Format Lamp 1</td>        
        </tr>
		<tr >
			<td colspan="2"><a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cek(0);">Cetak</a>
            <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cek(1);">Cetak PDF</a>
            <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cek(4);">Save PDF</a>
            <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cek(2);">Cetak excel</a>
            <a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:cek(3);">Cetak word</a></td>
		</tr>
		
        </table>                      
    </p> 
    

</div>

</div>

 	
</body>

</html>