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
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>    
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.min.js"></script>
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.css" />
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
   
    <script type="text/javascript"> 
    var nip='';
	var kdskpd='';
	var kdrek5='';
	var ctk = 1;
	var pilihan = '';    

    
	$(function(){

		$('#skpd').combogrid({  
           panelWidth:700,  
           idField:'kd_skpd',  
           textField:'kd_skpd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/skpd_2',  
           columns:[[  
               {field:'kd_skpd',title:'Kode SKPD',width:100},  
               {field:'nm_skpd',title:'Nama SKPD',width:700}    
           ]], 
           onSelect:function(rowIndex,rowData){
               kode = rowData.kd_skpd; 
              // bend(kode);               
               $("#nmskpd").attr("value",rowData.nm_skpd);
               //$('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/rka/load_trskpd',
                        //             queryParams:({kode:kode})
                        //             });
			
			validate_kegi(kode);
           }  
         });


		$('#anggaran').combogrid({  
                    panelWidth:400,  
                    idField:'kode',  
                    textField:'nama',  
                    mode:'remote',
                    url:'<?php echo base_url(); ?>index.php/rka_ro/load_jang/',  
                    columns:[[  
                        {field:'nama',title:'Nama',width:400}    
                    ]]
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
		url: '<?php echo base_url(); ?>index.php/tukd/list_ttd',  
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
      	opt(2);
      	pilih(1)
    		 cdate = '<?php echo date("Y-m-d"); ?>';
		 $("#dcetak").datebox("setValue",cdate);
        $("#dcetak2").datebox("setValue",cdate);

	  }); 
	 function validate_kegi(kode){
           $(function(){
		 $('#kg').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>index.php/tukd/pgiat/'+kode,  			
                    idField:'kd_sub_kegiatan',  
                    textField:'kd_sub_kegiatan',
                    mode:'remote',  
                    fitColumns:true,                       
                    columns:[[  
                        {field:'kd_sub_kegiatan',title:'Kode',width:30},  
                        {field:'nm_sub_kegiatan',title:'Nama',align:'left',width:70}                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    kegi   = rowData.kd_sub_kegiatan;
                    nmkegi = rowData.nm_sub_kegiatan;
                    $("#nm_kg").attr("value",rowData.nm_sub_kegiatan);

					validate_rek(kegi);
                    }    
                });
                
		       });
        }
    
	 function validate_rek(cgiat){
           $(function(){
        $('#kdrek5').combogrid({  
		panelWidth:630,  
		idField:'kd_rek6',  
		textField:'kd_rek6',  
		mode:'remote',
		url:'<?php echo base_url(); ?>index.php/tukd/ld_rek/'+cgiat,  
		columns:[[  
			{field:'kd_rek6',title:'Kode Rekening',width:100},  
			{field:'nm_rek6',title:'Nama Rekening',width:500}    
		]],
		onSelect:function(rowIndex,rowData){
			rekening = rowData.kd_rek6;
			//$("#kdrek5").attr("value",rowData.kd_rek5);
			$("#nmrek5").attr("value",rowData.nm_rek6);
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
		$( "#div_skpd" ).hide();
        } else if (ctk=='1'){
//            $("#div_skpd").show();
			$( "#div_skpd" ).show();
            } else {
            exit();
        }                 
    }    

function pilih(pilih){        
        pilihan = pilih;
        if(pilihan==1){
        	$( "#div_bulan" ).hide();
        	$( "#div_periode" ).hide();	
        } else if(pilihan==2){
        	$( "#div_bulan" ).show();
        	$( "#div_periode" ).hide();	
        }else{
        	$( "#div_bulan" ).hide();
        	$( "#div_periode" ).show();	
        }
    }	

	 function callback() {
      setTimeout(function() {
        $( "#div_skpd" ).removeAttr( "style" ).hide().fadeIn();
      }, 1000 );
    };
	
	
	function cetak_register_sp2d_lama(cetak){
			var cetak =cetak;           	
			var dcetak = $('#dcetak').datebox('getValue');      
			var dcetak2 = $('#dcetak2').datebox('getValue');      
			var tstatus = document.getElementById('tstatus').value; 
			var jenis = document.getElementById('jenis').value; 
			var urut = document.getElementById('urut').value; 
			var ttd    = nip;                           
            var ttd1 =ttd.split(" ").join("a"); 
			var skpd   = '--';
			var cbulan = $('#bulan').combogrid('getValue');
			if (ctk=='1'){
			var skpd   = kode; 			
			}
			if(pilihan=='1'){
				var url    = "<?php echo site_url(); ?>tukd/cetak_register_sp2d_baru"; 
				window.open(url+'/-/'+ttd1+'/'+skpd+'/'+tstatus+'/-/'+cetak+'/'+urut+'/1');
				window.focus();				
			} else if (pilihan=='2'){
				if(cbulan==''){
					alert('Pilih Bulan terlebih dahulu!');
					exit();
				}
				var url    = "<?php echo site_url(); ?>tukd/cetak_register_sp2d_baru"; 
				window.open(url+'/'+cbulan+'/'+ttd1+'/'+skpd+'/'+tstatus+'/-/'+cetak+'/'+urut+'/2');
				window.focus();
				
			}else if(pilihan=='3'){
			var url    = "<?php echo site_url(); ?>tukd/cetak_register_sp2d_baru"; 
			window.open(url+'/'+dcetak+'/'+ttd1+'/'+skpd+'/'+tstatus+'/'+dcetak2+'/'+cetak+'/'+urut+'/3');
			window.focus();
			}
        }
	
	function cetak_register_sp2d(cetak){
			var cetak =cetak;           	
			var dcetak = $('#dcetak').datebox('getValue');      
			var dcetak2 = $('#dcetak2').datebox('getValue');      
			var tstatus = document.getElementById('tstatus').value; 
			//var jenis = document.getElementById('jenis').value; 
			var urut = document.getElementById('urut').value; 
			var ttd    = nip;                           
			var ttd1 =ttd.split(" ").join("a"); 
			var skpd   = '--';
			var cbulan = $('#bulan').combogrid('getValue');
			if (ctk=='1'){
			var skpd   = kode; 			
			}
			var dengan = document.getElementById('dengan').checked; 
					if ( dengan == false ){
					   dengan=0;
					}else{
					   dengan=1;
					}
			if(pilihan=='1'){
				if(dengan==0){
				var url    = "<?php echo site_url(); ?>tukd/cetak_register_sp2d_baru"; 
				} else {
				var url    = "<?php echo site_url(); ?>tukd/cetak_register_sp2d_baru2"; 
				}
				window.open(url+'/-/'+ttd1+'/'+skpd+'/'+tstatus+'/-/'+cetak+'/'+urut+'/1');
				window.focus();				
			} else if (pilihan=='2'){
				if(cbulan==''){
					alert('Pilih Bulan terlebih dahulu!');
					exit();
				}
				if (dengan==0){
				var url    = "<?php echo site_url(); ?>tukd/cetak_register_sp2d_baru"; 
				} else {
				var url    = "<?php echo site_url(); ?>tukd/cetak_register_sp2d_baru2"; 
				}
				window.open(url+'/'+cbulan+'/'+ttd1+'/'+skpd+'/'+tstatus+'/-/'+cetak+'/'+urut+'/2');
				window.focus();
				
			}else if(pilihan=='3'){
			var url    = "<?php echo site_url(); ?>tukd/cetak_register_sp2d_baru"; 
			window.open(url+'/'+dcetak+'/'+ttd1+'/'+skpd+'/'+tstatus+'/'+dcetak2+'/'+cetak+'/'+urut+'/3');
			window.focus();
			}
        }
	


	function cetak_realisasi_sp2d(cetak){
			var cetak =cetak;           	
			var dcetak = $('#dcetak').datebox('getValue');      
			var dcetak2 = $('#dcetak2').datebox('getValue');      
			var tstatus = document.getElementById('tstatus').value; 
			var anggaran = $('#anggaran').combogrid('getValue');//document.getElementById('anggaran').value; 
			var ttd    = nip;                           
            var ttd1 =ttd.split(" ").join("a"); 
			var cbulan = $('#bulan').combogrid('getValue');
			if(pilihan=='1'){
				var url    = "<?php echo site_url(); ?>tukd/cetak_realisasi_sp2d"; 
				window.open(url+'/-/'+ttd1+'/-/'+tstatus+'/-/'+cetak+'/-/1/'+anggaran);
				window.focus();				
			} else if (pilihan=='2'){
				if(cbulan==''){
					alert('Pilih Bulan terlebih dahulu!');
					exit();
				}
				var url    = "<?php echo site_url(); ?>tukd/cetak_realisasi_sp2d"; 
				window.open(url+'/'+cbulan+'/'+ttd1+'/-/'+tstatus+'/-/'+cetak+'/-/2/'+anggaran);
				window.focus();
				
			}else if(pilihan=='3'){
			var url    = "<?php echo site_url(); ?>tukd/cetak_realisasi_sp2d"; 
			window.open(url+'/'+dcetak+'/'+ttd1+'/-/'+tstatus+'/'+dcetak2+'/'+cetak+'/-/3/'+anggaran);
			window.focus();
			}
        }	

     function cetak_realisasi_sp2dskpd(cetak){
			var cetak =cetak;           	
			var dcetak = $('#dcetak').datebox('getValue');      
			var dcetak2 = $('#dcetak2').datebox('getValue');      
			var tstatus = document.getElementById('tstatus').value; 
			var anggaran = $('#anggaran').combogrid('getValue');//document.getElementById('anggaran').value; 
			var ttd    = nip;                           
            var ttd1 =ttd.split(" ").join("a"); 
			var cbulan = $('#bulan').combogrid('getValue');
			if(pilihan=='1'){
				var url    = "<?php echo site_url(); ?>tukd/cetak_realisasi_sp2dskpd"; 
				window.open(url+'/-/'+ttd1+'/-/'+tstatus+'/-/'+cetak+'/-/1/'+anggaran);
				window.focus();				
			} else if (pilihan=='2'){
				if(cbulan==''){
					alert('Pilih Bulan terlebih dahulu!');
					exit();
				}
				var url    = "<?php echo site_url(); ?>tukd/cetak_realisasi_sp2dskpd"; 
				window.open(url+'/'+cbulan+'/'+ttd1+'/-/'+tstatus+'/-/'+cetak+'/-/2/'+anggaran);
				window.focus();
				
			}else if(pilihan=='3'){
			var url    = "<?php echo site_url(); ?>tukd/cetak_realisasi_sp2dskpd"; 
			window.open(url+'/'+dcetak+'/'+ttd1+'/-/'+tstatus+'/'+dcetak2+'/'+cetak+'/-/3/'+anggaran);
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

<div id="accordion">

<h3>CETAK REGISTER SP2D</h3>
    <div>
    <p align="right">         
        <table id="sp2d" title="Cetak register Sp2d" style="width:870px;height:300px;" >
		<tr style="border-bottom-style: hidden;">
                <td colspan="2"><input type="radio" name="cetak" value="1" onclick="opt(this.value)" /> Per SKPD &ensp;
                <input type="radio" name="cetak" value="2" id="status" onclick="opt(this.value)" /> Keseluruhan
                </td>
                
            </tr>		
		<tr style="border-bottom-style: hidden;">
			<td colspan="2">
			<div id="div_skpd">
				<table style="width:100%;" border="0">
				<tr>
				<td width="20%" height="40" ><B>SKPD</B></td>
				<td width="80%"><input id="skpd" name="skpd" style="width: 100px;border: 0;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" readonly="true" style="width: 500px; border:0;" /></td>
				</tr>
				</table>
			</div>
			</td>
		</tr>
		<tr style="border-bottom-style: hidden;">
			<td colspan = "2" width="20%" height="40" ><input type="radio" name="pilihan" value="1" onclick="pilih(this.value)"/><B>&nbsp;Keseluruhan</B>&nbsp;&nbsp;&nbsp;
			<input type="radio" name="pilihan" value="2" onclick="pilih(this.value)"/><B>&nbsp;BULAN</B>&nbsp;&nbsp;&nbsp;
			<input type="radio" name="pilihan" value="3" onclick="pilih(this.value)"/><B>&nbsp;PERIODE</B>
		</td>
		</tr>
		<tr style="border-bottom-style: hidden;">
			<td width="20%" height="40" ></td>
			<td width="80%">
				<div id="div_bulan">
					<table style="width:100%;" border="0">
						<tr>
							<td width="80%"><input type="text" id="bulan" style="width: 100px;" /></td>
						</tr>
					</table>
				</div>
				<div id="div_periode">
					<table style="width:100%;" border="0">
						<tr>
							<td width="80%"><input id="dcetak" name="dcetak" type="text"  style="width:155px" />&nbsp;&nbsp;s/d&nbsp;&nbsp;<input id="dcetak2" name="dcetak2" type="text"  style="width:155px" /></td>
						</tr>
					</table>
				</div>
				

			</td>
		</tr>
		<tr style="border-bottom-style: hidden;">
			<td width="20%" height="40" ><B>STATUS</B></td>
			<td width="80%">			
				<select name="tstatus" id="tstatus" style="width:300px;">
				 <option value="1">SP2D TERBIT</option>
				 <option value="2">SP2D LUNAS</option>
				 <option value="3">SP2D ADVICE</option>
				 <option value="4">SP2D BELUM CAIR</option>
				 <option value="5">SP2D BELUM ADVICE</option>
				 </select>
		</td>
		</tr>
		<tr style="border-bottom-style: hidden;">
			<td width="20%" height="40" ><B>PENANDA TANGAN</B></td>
			<td width="80%"><input id="ttd" name="ttd" type="text"  style="width:300px" /></td>
		</tr>
		<tr style="border-bottom-style: hidden;">
			<td width="20%" height="40" ><B>ANGGARAN</B></td>
			<td width="80%">			
				 <input id="anggaran" name="anggaran" style="width: 300px;" />
		</td>
		</tr>
		<tr style="border-bottom-style: hidden;">
			<td width="20%" height="40" ><B>URUTAN</B></td>
			<td width="80%">			
				<select name="urut" id="urut" style="width:300px;">
				 <option value="1">NO SP2D</option>
				 <option value="2">NO KAS</option>
				 </select>
		</td>
		</tr>
		<tr style="border-bottom-style: hidden;">            
            <td>&nbsp;</td>
            <td><input type="checkbox" id="dengan"> Tampilkan No Kasda</td>
        </tr>
		<tr style="border-bottom-style: hidden;">
			<td width="20%" height="40" ><B>Register </B></td>
			<td width="80%">
			<button class="btn btn-dark" plain="true" onclick="javascript:cetak_register_sp2d(2);"><i class="fa fa-television"></i> Layar</button>
			<button class="btn btn-pdf" plain="true" onclick="javascript:cetak_register_sp2d(1);"><i class="fa fa-file-pdf-o"></i> Pdf</button>
			<button class="btn btn-success" plain="true" onclick="javascript:cetak_register_sp2d(3);"><i class="fa fa-file-excel-o"></i> Excel</button>
			</td>
			
		</tr>
		<tr style="border-bottom-style: hidden;">
			<td width="20%" height="40" ><B>Realisasi </B></td>
			<td width="80%"> 
			<button class="btn btn-dark" plain="true" onclick="javascript:cetak_realisasi_sp2d(2);"><i class="fa fa-television"></i> Layar</button>
			<button class="btn btn-pdf" plain="true" onclick="javascript:cetak_realisasi_sp2d(1);"><i class="fa fa-file-pdf-o"></i> Pdf</button>
			<button class="btn btn-success" plain="true" onclick="javascript:cetak_realisasi_sp2d(3);"><i class="fa fa-file-excel-o"></i> Excel</button>
			</td>
			
		</tr>
		<tr style="border-bottom-style: hidden;">
			<td width="20%" height="40" ><B>Realisasi per skpd </B></td>
			<td width="80%"> 
			<button class="btn btn-dark" plain="true" onclick="javascript:cetak_realisasi_sp2dskpd(2);"><i class="fa fa-television"></i> Layar</button>
			<button class="btn btn-pdf" plain="true" onclick="javascript:cetak_realisasi_sp2dskpd(1);"><i class="fa fa-file-pdf-o"></i> Pdf</button>
			<button class="btn btn-success" plain="true" onclick="javascript:cetak_realisasi_sp2dskpd(3);"><i class="fa fa-file-excel-o"></i> Excel</button>
			</td>
			
		</tr>
		<tr style="border-bottom-style: hidden;">
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