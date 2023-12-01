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
            $("#div_tgl1").hide();
			$("#div_tgl2").hide();            
        });   
    
	
	function opt(val){  	        
        cetk = val;
        if (cetk=='1'){
            $("#div_tgl1").show();
			$("#div_tgl2").hide();
        }else if(cetk=='2'){
           $("#div_tgl1").hide();
			$("#div_tgl2").show();
		}else{
			exit();
		}    
	}
	
	$(function(){  
            $('#ttd').combogrid({  
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_ttdrth/BUD',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},
                    {field:'nama',title:'Nama',width:400}
                ]],  
           onSelect:function(rowIndex,rowData){
               $("#nama").attr("value",rowData.nama);
           } 
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
		}); 
		
		$(function(){   
		 $('#tgl_ttd1').datebox({  
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
		 $('#tgl_ttd2').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
		}); 
		
    function validate1(){
        var bln1 = document.getElementById('bulan1').value;
        
    }
    	
        function cetak(ctk)
        {
			
			var bulan   =  document.getElementById('bulan1').value;
			var format   =  document.getElementById('format').value;
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			var ctglttd1 = $('#tgl_ttd1').datebox('getValue');
			var ctglttd2 = $('#tgl_ttd2').datebox('getValue');
			var  ttd = $('#ttd').combogrid('getValue');
		    ttd = ttd.split(" ").join("123456789");
		    var atas   =  document.getElementById('atas').value;
			var bawah   =  document.getElementById('bawah').value;
			var kanan   =  document.getElementById('kanan').value;
			var kiri   =  document.getElementById('kiri').value;
			
			var url    = "<?php echo site_url(); ?>tukd/cetak_rth";  
			
			if(ctglttd==''){
			alert('Pilih Tanggal tanda tangan dulu')
			exit()
			}
			if(ttd==''){
			alert('Pilih Penanda tangan dulu')
			exit()
			}
			
			if(cetk=='1'){
				window.open(url+'/'+bulan+'/'+ctk+'/'+ttd+'/'+ctglttd+'/'+cetk+'/'+'-'+'/'+'-'+'/'+format+'/'+atas+'/'+bawah+'/'+kiri+'/'+kanan, '_blank');
				window.focus();
			}else{
				window.open(url+'/'+bulan+'/'+ctk+'/'+ttd+'/'+ctglttd+'/'+cetk+'/'+ctglttd1+'/'+ctglttd2, '_blank');
				window.focus();
			}
        }
        
        function cetak_djp1(ctk){            
            var skpd ='-';
			var bulan   =  document.getElementById('bulan1').value;			
			var url    = "<?php echo site_url(); ?>/tukd_djp/cetak_csv1";  
			if(bulan==0){
			alert('Pilih Bulan dulu')
			exit()
			}
            
			window.open(url+'/'+bulan+'/CSV 01', '_blank');
			window.focus();
        }
        
        function cetak_djp2(ctk){            
            var skpd ='-';
			var bulan   =  document.getElementById('bulan1').value;			
			var url    = "<?php echo site_url(); ?>/tukd_djp/cetak_csv2";  
			if(bulan==0){
			alert('Pilih Bulan dulu')
			exit()
			}
            
			window.open(url+'/'+bulan+'/CSV 02', '_blank');
			window.focus();
        }    

        function cetak_djp3(ctk){            
            var skpd ='-';
			var bulan   =  document.getElementById('bulan1').value;			
			var url    = "<?php echo site_url(); ?>/tukd_djp/cetak_csv3";  
			if(bulan==0){
			alert('Pilih Bulan dulu')
			exit()
			}
            
			window.open(url+'/'+bulan+'/CSV 03', '_blank');
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



<h3>CETAK DAFTAR TRANSAKSI HARIAN</h3>
<div id="accordion">
    
    <p align="right">         
        <table id="sp2d" title="Cetak Buku Besar" style="width:922px;height:200px;" >  
		 <tr >
			<td colspan="4">
				<table style="width:100%;" border="0">
				<tr>
					<td ><input type="radio" name="cetak" value="1" id="status" onclick="opt(this.value)" /><b>Per Bulan</b>
						<div id="div_tgl1">
								<table style="width:100%;" border="0">
									
										<td width="20%" height="40" ><B>BULAN</B></td>
										<td><?php echo $this->rka_model->combo_bulan('bulan1','onchange="javascript:validate1();"'); ?> </td onclick="return confirm('hapus data yang telah diinput?')"></td>
									
								</table>
						</div>
					</td> 
				</tr>
				<tr>
					<td ><input type="radio" name="cetak" value="2" id="status" onclick="opt(this.value)" /><b>Per Periode</b>
						<div id="div_tgl2">
								<table style="width:100%;" border="0">
									<td width="20%">Tanggal</td>
									<td><input type="text" id="tgl_ttd1" style="width: 100px;" /> s.d <input type="text" id="tgl_ttd2" style="width: 100px;" /></td>
								</table>
						</div>
					</td> 
				</tr>
				
				</table>
			 </td>
		</tr>
       
		 <tr>
                <td colspan="4">
                <div id="div_tgl">
                        <table style="width:100%;" border="0">
                            <td width="20%">Tanggal TTD</td>
                            <td><input type="text" id="tgl_ttd" style="width: 100px;" /> 
                            </td> 
                        </table>
                </div>
                </td> 
            </tr>
		
		
		<tr>
		<td colspan="4">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
							<td width="20%">Penanda tangan</td>
                            <td><input type="text" id="ttd" style="width: 200px;" /> &nbsp;&nbsp;
							<input type="nama" id="nama" readonly="true" style="width: 200px;border:0" /> 
							
                            </td> 
                        </table>
                </div>
        </td> 
		</tr>
		
		<tr>
		<td colspan="4">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
							<td width="20%">FORMAT</td>
                            <td> <select  name="format" id="format" >
									 <option value="1">RTH</option>
									 <option value="2">SINERGI</option>
								 </select>
                            </td> 
                        </table>
                </div>
        </td> 
		</tr>
		
		
			<tr >
    			<td colspan='2'width="100%" height="40" ><strong>Ukuran Margin Untuk Cetakan PDF (Milimeter)</strong></td>
    		</tr>
    		<tr >
    			<td colspan='2' align="center"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    			Kiri  : &nbsp;<input type="number" id="kiri" name="kiri" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
    			Kanan : &nbsp;<input type="number" id="kanan" name="kanan" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
    			Atas  : &nbsp;<input type="number" id="atas" name="atas" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
    			Bawah : &nbsp;<input type="number" id="bawah" name="bawah" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
    			</td>
    		</tr>

		<tr >
			<td colspan="2" align="center">
			<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak(0);">Cetak</a>
			<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak(1);">Cetak Pdf</a>
			<a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak(2);">Cetak excel</a>
			</td>
		</tr>

        <tr >
			<td colspan="2" align="center">*)Keperluan DJP
			<a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak_djp1(0);">File.01 (File Induk SP2D)</a>
			<a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak_djp2(1);">File.02 (File Rincian Belanja SP2D)</a>
            <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak_djp3(2);">File.03 (File Rincian Potongan Pajak SP2D)</a>
			</td>
		</tr>
		
        </table>                      
    </p> 
    

</div>
</div>

 	
</body>

</html>