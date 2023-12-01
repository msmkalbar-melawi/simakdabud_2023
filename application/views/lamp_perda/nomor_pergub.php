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
       
        get_nogub()
        });    
     
  
    
     
     $(function(){ 
     
        
         $('#tgl_rka').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
         $('#tgl_dpa').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
         $('#tgl_ubah').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
         $('#tgl_dpa').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
         $('#tgl_dppa').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
    
           
    }); 
    
    function get_nogub()
        {
        
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/lamp_perda/get_nogub',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#ket_perda").attr("value",data.ket_perda);
                                        $("#ket_perda_no").attr("value",data.ket_perda_no);
        								$("#ket_perda_tentang").attr("value",data.ket_perda_tentang);
                                        $("#ket_pergub").attr("value",data.ket_pergub);
                                        $("#ket_pergub_no").attr("value",data.ket_pergub_no);
        								$("#ket_pergub_tentang").attr("value",data.ket_pergub_tentang);
        								
        							  }                                     
        	});  
        }       


    
       
       

    
    function kosong(){
       $("#ket_perda").attr("value","");
		$("#ket_perda_no").attr("value","");
		$("#ket_perda_tentang").attr("value","");
		$("#ket_pergub").attr("value","");
		$("#ket_pergub_no").attr("value","");
		$("#ket_pergub_tentang").attr("value","");

    }
    

    
       function simpan()
       {
       
        var cket_perda          = document.getElementById('ket_perda').value;
        var cket_perda_no       = document.getElementById('ket_perda_no').value;
        var cket_perda_tentang  = document.getElementById('ket_perda_tentang').value;
        var cket_pergub         = document.getElementById('ket_pergub').value;
        var cket_pergub_no      = document.getElementById('ket_pergub_no').value;
        var cket_pergub_tentang = document.getElementById('ket_pergub_tentang').value;
        
        // alert(rek_lo);       
       

            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/lamp_perda/simpan_nogub',
                    data: ({tabel:'config_nogub',cket_perda:cket_perda,cket_perda_no:cket_perda_no,cket_perda_tentang:cket_perda_tentang,cket_pergub:cket_pergub,cket_pergub_no:cket_pergub_no,cket_pergub_tentang:cket_pergub_tentang}),
                    dataType:"json"
                });
            });    
            
            

        
        alert("Data Berhasil disimpan");

    } 
    

  
   </script>

</head>
<body>

<div id="content"> 
    <div id="accordion">
        <h3 align="center"><u><b><a href="#" id="section1">CONFIG NOMOR DAN TANGGAL LAMPIRAN</a></b></u></h3>
            <div>
                
                   
                    <fieldset>
                    <table align="center" style="width:100%;" border="0">
					<tr>
					<td colspan="4"><b>PERATURAN DAERAH</b></td>
					</tr>
					<tr>
                        <td>Keterangan</td>
                        <td><textarea rows="1" cols="50" id="ket_perda" style="width: 250px;"></textarea>
                        </td> 
                        <td>Nomor</td>
                        <td ><textarea rows="1" cols="50" id="ket_perda_no" style="width: 250px;"></textarea>
                        </td> 
                    </tr>
					<tr>
                        <td>Tentang</td>
                        <td><textarea rows="1" cols="100" id="ket_perda_tentang" style="width: 250px;"></textarea>
                        </td> 
                    </tr>
                   
               <tr>
					<td colspan="4"><b>PERATURAN GUBERNUR</b></td>
					</tr>
				<tr>
                        <td>Keterangan</td>
                        <td><textarea rows="1" cols="50" id="ket_pergub" style="width: 250px;"></textarea>
                        </td> 
                        <td>Nomor</td>
                        <td ><textarea rows="1" cols="50" id="ket_pergub_no" style="width: 250px;"></textarea>
                        </td> 
                    </tr>
					<tr>
                        <td>Tentang</td>
                        <td><textarea rows="1" cols="50" id="ket_pergub_tentang" style="width: 250px;"></textarea>
                        </td> 
                    </tr>
                   
                  
                   
                    <tr>
                        <td colspan="4" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Simpan</a>
        		        <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:kosong();">Kosongkan</a>
                        </td>                
                    </tr>
                </table>       
                </fieldset>
            

    </div>   

</div>

</div>


  	
</body>

</html>




  	
