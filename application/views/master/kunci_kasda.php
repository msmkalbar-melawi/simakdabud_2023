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
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>
    
    
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
    <script type="text/javascript">
    
    var kode = '';
    var giat = '';
    var nomor= '';
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
                    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 400,
            width: 900,
            modal: true,
            autoOpen:false,
        });
		
		$('#tgl_awal').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
       
		$('#tgl_akhir').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
	   
        });    
     
    
       function simpan()
       {
       
        var ctgl_awal = $('#tgl_awal').datebox('getValue');
        var ctgl_akhir = $('#tgl_akhir').datebox('getValue');
        
		if(ctgl_awal==''){
			alert('Tanggal Awal Tidak Boleh Kosong');
			exit();
		}
		
		if(ctgl_akhir==''){
			alert('Tanggal AKhir Tidak Boleh Kosong');
			exit();
		}
		
		if(ctgl_akhir<ctgl_awal){
			alert('Tanggal AKhir Lebih Kecil dari Tanggal Awal');
			exit();
		}
		

        $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_kunci_kasda',
                    data: ({tabel:'trhkasin_ppkd',ctgl_awal:ctgl_awal,ctgl_akhir:ctgl_akhir}),
                    dataType:"json"
                });
            });    
            
        alert("Data Berhasil Terkunci");

    } 
    
	function simpan2(){
       
        var ctgl_awal = $('#tgl_awal').datebox('getValue');
        var ctgl_akhir = $('#tgl_akhir').datebox('getValue');
        
		if(ctgl_awal==''){
			alert('Tanggal Awal Tidak Boleh Kosong');
			exit();
		}
		
		if(ctgl_akhir==''){
			alert('Tanggal AKhir Tidak Boleh Kosong');
			exit();
		}
		
		if(ctgl_akhir<ctgl_awal){
			alert('Tanggal AKhir Lebih Kecil dari Tanggal Awal');
			exit();
		}
		

        $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/buka_kunci_kasda',
                    data: ({tabel:'trhkasin_ppkd',ctgl_awal:ctgl_awal,ctgl_akhir:ctgl_akhir}),
                    dataType:"json"
                });
            });    
            
        alert("Data Berhasil Dibuka");

    }
    
  
   </script>

</head>
<body>

<div id="content"> 
    <div id="accordion">
        <h3 align="center"><u><b><a href="#" id="section1">KUNCI DATA KASDA</a></b></u></h3>
            <div>
                
                    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
                    <fieldset>
                    <table align="center" style="width:100%;" border="0">
                               
                    <tr>
                        
                        <td>TGL AWAL</td>
                        <td><input type="text" id="tgl_awal" style="width: 140px;" /></td>
                        <td>TGL AKHIR</td>
                        <td><input type="text" id="tgl_akhir" style="width: 140px;" /></td>
                        
                    </tr>
                    <tr>
                       <td colspan="2" align="right"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan2();">Buka</a>
                        </td>    
						<td colspan="2" align="left"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Kunci</a>
                        </td>   
                    </tr>
                </table>       
                </fieldset>
            

    </div>   

</div>
</div>
</body>

</html>




  	
