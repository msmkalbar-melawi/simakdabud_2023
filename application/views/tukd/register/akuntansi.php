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
	var ctk = 1;
    

    
	$(function(){


		$('#skpd').combogrid({  
           panelWidth:700,  
           idField:'kd_skpd',  
           textField:'kd_skpd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/skpd_3',  
           columns:[[  
               {field:'kd_skpd',title:'Kode SKPD',width:100},  
               {field:'nm_skpd',title:'Nama SKPD',width:700}    
           ]], 
           onSelect:function(rowIndex,rowData){
               kode = rowData.kd_skpd;               
               $("#nmskpd").attr("value",rowData.nm_skpd);
			validate_kegi(kode);
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
        
        $('#dcetak_ttd').datebox({  
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
		
		$('#bulan').combogrid({  
                   panelWidth:120,
                   panelHeight:300,  
                   idField:'bln',  
                   textField:'nm_bulan',  
                   mode:'remote',
                   url:'<?php echo base_url(); ?>index.php/rka/bulan',  
                   columns:[[ 
                       {field:'nm_bulan',title:'Nama Bulan',width:700}    
                   ]],
					onSelect:function(rowIndex,rowData){
						bulan = rowData.nm_bulan;
						$("#bulan").attr("value",rowData.nm_bulan);
					}
               });
		
        
        $('#ttd').combogrid({  
		panelWidth:500,  
		url: '<?php echo base_url(); ?>/index.php/tukd/load_ttd/BUD',  
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
		 $(document).ready(function() { 
      //get_skpd();                                                            
    		 cdate = '<?php echo date("Y-m-d"); ?>';
		 $("#dcetak").datebox("setValue",cdate);
        $("#dcetak2").datebox("setValue",cdate);

	  }); 
	 function validate_kegi(kode){
           $(function(){
		 $('#kg').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/pgiat/'+kode,  			
                    idField:'kd_kegiatan',  
                    textField:'kd_kegiatan',
                    mode:'remote',  
                    fitColumns:true,                       
                    columns:[[  
                        {field:'kd_kegiatan',title:'Kode',width:30},  
                        {field:'nm_kegiatan',title:'Nama',align:'left',width:70}                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    kegi   = rowData.kd_kegiatan;
                    nmkegi = rowData.nm_kegiatan;
                    $("#nm_kg").attr("value",rowData.nm_kegiatan);

					validate_rek(kegi);
                    }    
                });
                
		       });
        }
    
	 function validate_rek(cgiat){
           $(function(){
        $('#kdrek5').combogrid({  
		panelWidth:630,  
		idField:'kd_rek5',  
		textField:'kd_rek5',  
		mode:'remote',
		url:'<?php echo base_url(); ?>/index.php/tukd/ld_rek/'+cgiat,  
		columns:[[  
			{field:'kd_rek5',title:'Kode Rekening',width:100},  
			{field:'nm_rek5',title:'Nama Rekening',width:500}    
		]],
		onSelect:function(rowIndex,rowData){
			rekening = rowData.kd_rek5;
			//$("#kdrek5").attr("value",rowData.kd_rek5);
			$("#nmrek5").attr("value",rowData.nm_rek5);
		}  
		}); 
		
				       });
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
                                        $("#skpd").attr("value",data.kd_skpd);
        								kdskpd = data.kd_skpd;
                                               
        							  }                                     
        	});  
        }



  function opt(val){        
        ctk = val; 
        if (ctk=='2'){
		//$("#div_skpd").hide();
		options = { percent: 0 };
		selectedEffect = "clip";
		$( "#div_skpd" ).hide( selectedEffect, options, 1000 );
        } else if (ctk=='1'){
//            $("#div_skpd").show();
			$( "#div_skpd" ).show( selectedEffect, options, 1000 );
            } else {
            exit();
        }                 
    }    

function pilih(pilih){        
        pilihan = pilih; 
    }	

	 function callback() {
      setTimeout(function() {
        $( "#div_skpd" ).removeAttr( "style" ).hide().fadeIn();
      }, 1000 );
    };

        	
	function cetak_register_akun1(cetak){
			var cetak =cetak;
			var ttd    = nip;                           
			var ttd1 =ttd.split(" ").join("a");
			var cbulan = $('#bulan').combogrid('getValue');
				var url    = "<?php echo site_url(); ?>tukd/cetak_register_akun"; 
				window.open(url+'/'+ttd1+'/'+cbulan+'/'+cetak+'/1');
				window.focus();				
        }

        	
	function cetak_register_akun2(cetak){
			var cetak =cetak;
			var ttd    = nip;                           
			var ttd1 =ttd.split(" ").join("a");
			var cbulan = $('#bulan').combogrid('getValue');
				var url    = "<?php echo site_url(); ?>tukd/cetak_register_akun_rekap"; 
				window.open(url+'/'+ttd1+'/'+cbulan+'/'+cetak+'/1');
				window.focus();				
        }

        	
	function cetak_register_akun3(cetak){
			var cetak =cetak;
			var ttd    = nip;                           
			var ttd1 =ttd.split(" ").join("a");
			var cbulan = $('#bulan').combogrid('getValue');
				var url    = "<?php echo site_url(); ?>tukd/cetak_register_terima_rekap"; 
				window.open(url+'/'+ttd1+'/'+cbulan+'/'+cetak+'/1');
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

<h3>CEK DATA AKUNTANSI</h3>
    <div>
    <p align="right">         
        <table id="sp2d" title="Cetak Monitoring Akuntnasi" style="width:870px;height:300px;" >		

		<tr> 
			<td width="20%" height="40" ><B>BULAN</B></td>
			<td width="80%"><input type="text" id="bulan" style="width: 100px;" /></td>
		</tr>      
		<tr>
			<td width="20%" height="40" ><B>PENANDA TANGAN</B></td>
			<td width="80%"><input id="ttd" name="ttd" type="text"  style="width:230px" /></td>
		</tr>
        	<tr>            
            <td>&nbsp;</td>
            <td>Tanggal TTD   <input id="dcetak_ttd" name="dcetak_ttd" type="text"  style="width:155px" /></td>
        </tr>
		<!--<tr >
			<td width="20%" height="40" ><B>CEK DATA AKUNTANSI (BELANJA)</B></td>
			<td width="80%"> <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_register_akun1(2);">Cetak Layar</a>
			<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_register_akun1(1);">Cetak Pdf</a>
			</td>
		</tr>-->     
		<tr >
			<td width="20%" height="40" ><B>CEK DATA GLOBAL AKUNTANSI (BELANJA)</B></td>
			<td width="80%"> <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_register_akun2(2);">Cetak Layar</a>
			<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_register_akun2(1);">Cetak Pdf</a>
			</td>
		</tr>          
		<tr >
			<td width="20%" height="40" ><B>CEK DATA GLOBAL AKUNTANSI (PENDAPATAN)</B></td>
			<td width="80%"> <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_register_akun3(2);">Cetak Layar</a>
			<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_register_akun3(1);">Cetak Pdf</a>
			</td>
		</tr>          
		<tr >
			<td >&nbsp;</td>
			<td >&nbsp;</td>
		</tr>
        </table>                      
    </p> 
    </div>
</div>
</div>

 	
</body>

</html>