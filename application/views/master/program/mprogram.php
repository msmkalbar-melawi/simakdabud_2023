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
            height: 350,
            width: 1000,
            modal: true,
            autoOpen:false
        });
        });    
        
	
		
     $(function(){ 
     $('#kode_f').combogrid({  
       panelWidth:500,  
       idField:'kd_bidang_urusan',  
       textField:'kd_bidang_urusan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/load_bidang_urusan',  
       columns:[[  
           {field:'kd_bidang_urusan',title:'Kode Bidang Urusan',width:100},  
           {field:'nm_bidang_urusan',title:'Nama Bidang Urusan',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           $("#nm_f").attr("value",rowData.nm_bidang_urusan.toUpperCase());                
       }  
     }); 

     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_program',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'kd_program',
    		title:'Kode Program',
    		width:15,
            align:"center"},
            {field:'nm_program',
    		title:'Nama Program',
    		width:50},
            {field:'kd_bidang_urusan',
            title:'Nama Bidang Urusan',
            width:10}
        ]],
        onSelect:function(rowIndex,rowData){
          kd    = rowData.kd_program;
          kd_f  = rowData.kd_bidang_urusan;
          nm    = rowData.nm_program;
          lcidx = rowIndex; 
          get(kd,nm,kd_f);  
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data Fungsi'; 
           edit_data();   
        }
        
        });
       
    });        

 
    
    function get(kd,nm,kd_f) {
        
        $("#kode").attr("value",kd);
        $("#nama").attr("value",nm);     
        $('#kode_f').combogrid("setValue",kd_f);               
    }
       
    function kosong(){
        $("#kode").attr("value",'');
        $("#nama").attr("value",'');
        $('#kode_f').combogrid("enable"); 
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_program',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
       function simpan_program(){
       
        var ckode = document.getElementById('kode').value;
        var cnama = document.getElementById('nama').value;
        var ckode_f= $('#kode_f').combogrid('getValue');
        if (ckode==''){
            alert('Kode Program Tidak Boleh Kosong');
            exit();
        } 
        if (cnama==''){
            alert('Nama Program Tidak Boleh Kosong');
            exit();
        }

        if (ckode_f==''){
            alert('Kode Bidang Urusan Tidak Boleh Kosong');
            exit();
        }

        
        if(lcstatus=='tambah'){ 
            
            lcinsert = "(kd_program,nm_program,kd_bidang_urusan)";
            lcvalues = "('"+$.trim(ckode_f)+'.'+$.trim(ckode)+"','"+$.trim(cnama)+"','"+$.trim(ckode_f)+"')";
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_master',
                    data: ({tabel:'ms_program',kolom:lcinsert,nilai:lcvalues,cid:'kd_program',lcid:ckode}),
                    dataType:"json",
                    success:function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Simpan..!!');
                            exit();
                        }else if(status=='1'){
                            alert('Data Sudah Ada..!!');
                            exit();
                        }else{
                            alert('Data Tersimpan..!!');
                            exit();
                        }
                    }
                });
            });   
           
        } else{
            
            lcquery = "UPDATE ms_program SET nm_program='"+$.trim(cnama)+"' where kd_program='"+$.trim(ckode)+"'"
       //      +
					   "UPDATE trskpd SET nm_program='"+$.trim(cnama)+"' where kd_program1='"+$.trim(ckode)+"'";
					  // +"UPDATE trskpd_rancang SET nm_program='"+$.trim(cnama)+"' where kd_program1='"+$.trim(ckode)+"'";

            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/master/update_master',
                data: ({st_query:lcquery}),
                dataType:"json",
                success:function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Simpan..!!');
                            exit();
                        }else{
                            alert('Data Tersimpan..!!');
							
                            exit();
                        }
                    }
            });
            });
            
            
        }
        
       
        $("#dialog-modal").dialog('close');
 $('#dg').edatagrid('reload'); 
    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Program';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("kode").disabled=true;
        $('#kode_f').combogrid("disable"); 
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Program';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        $('#kode_f').combogrid("enable"); 
        document.getElementById("kode").disabled=false;
        document.getElementById("kode").focus();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
     }    
    
     function hapus(){
        var ckode = document.getElementById('kode').value;
              $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:ckode,tabel:'trskpd',field:'kd_program1'}),
                    url: '<?php echo base_url(); ?>/index.php/rka/cek_data',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1 ){
						alert("Kode PROGRAM Telah Dipakai dan tidak bisa di Hapus!");
						exit();
						}
						
						if(status_cek==0 ){
						var urll = '<?php echo base_url(); ?>index.php/master/hapus_master';
						$(document).ready(function(){
						 $.post(urll,({tabel:'ms_program',cnid:ckode,cid:'kd_program'}),function(data){
							status = data;
							if (status=='0'){
								alert('Gagal Hapus..!!');
								exit();
							} else {
								$('#dg').datagrid('deleteRow',lcidx);   
								alert('Data Berhasil Dihapus..!!');
								  $("#dialog-modal").dialog('close');
									$('#dg').edatagrid('reload'); 
				//                exit();
							}
						 });
						}); 

						}		
					}
				});

		
    } 
    
       
    function addCommas(nStr)
    {
    	nStr += '';
    	x = nStr.split(',');
        x1 = x[0];
    	x2 = x.length > 1 ? ',' + x[1] : '';
    	var rgx = /(\d+)(\d{3})/;
    	while (rgx.test(x1)) {
    		x1 = x1.replace(rgx, '$1' + '.' + '$2');
    	}
    	return x1 + x2;
    }
    
     function delCommas(nStr)
    {
    	nStr += ' ';
    	x2 = nStr.length;
        var x=nStr;
        var i=0;
    	while (i<x2) {
    		x = x.replace(',','');
            i++;
    	}
    	return x;
    }
  
    
  
   </script>

</head>
<body>

<div id="content"> 

<h3 align="center"><u><b><a>INPUTAN MASTER PROGRAM</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:400px;" border="0">
        <tr>
        <td width="10%">
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a></td>               
        
        <td width="5%"><a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a></td>
        <td><input type="text" value="" id="txtcari" style="width:300px;"/></td>
        </tr>
        <tr>
        <td colspan="4">
        <table id="dg" title="LISTING DATA PROGRAM" style="width:900px;height:440px;" >  
        </table>
        </td>
        </tr>
    </table>    
    
        
 
    </p> 
    </div>   
</div>

<div id="dialog-modal" title="">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
     <table align="center" style="width:100%;" border="0">
            <tr>
                <td width="30%">KODE BIDANG URUSAN</td>
                <td width="1%">:</td>
                <td><input type="text" id="kode_f" style="width:50px;"/><input type="text" id="nm_f" style="width:310px;" readonly="true" /></td>  
            </tr> 
           <tr>
                <td width="30%">KODE PROGRAM</td>
                <td width="1%">:</td>
                <td><input type="text" id="kode" style="width:100px;"/></td>  
            </tr>            
            <tr>
                <td width="30%">NAMA PROGRAM</td>
                <td width="1%">:</td>
                <td><input type="text" id="nama" style="width:800px;" onkeyup="this.value = this.value.toUpperCase()"/></td>  
            </tr>
            
            
            
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_program(); $('#dg').edatagrid('reload'); ">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>

</body>

</html>