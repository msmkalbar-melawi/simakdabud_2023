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
            height: 250,
            width: 600,
            modal: true,
            autoOpen:false
        });
        });    
     
     $(function(){        
     $('#dg').datagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_kel_barang',
        panelWidth: 200,
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination: "true",
        nowrap:"true",  
        columns: [
                    [{
                            field: 'kode_kel_brg',
                            title: 'Kode Kelompok',
                            width: 30,
                            align: "left"
                        },
                        {
                            field: 'nama_kel_brg',
                            title: 'Nama Kelompok',
                            width: 100
                        }
                    ]
                ],                     
        onSelect:function(rowIndex,rowData){
          kd = rowData.kode_kel_brg;
          nm = rowData.nama_kel_brg;
          get(kd,nm); 
          lcidx = rowIndex;  
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data KELOMPOK BARANG'; 
           edit_data();   
        }
        
        });
       
    });        

 
    
    function get(kd,nm) {
        
        $("#kode").attr("value",kd);
        $("#nama").attr("value",nm);     
                       
    }
       
    function kosong(){
        $("#kode").attr("value",'');
        $("#nama").attr("value",'');
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_kel_barang',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
       function simpan_fungsi(){
       
        var ckode = document.getElementById('kode').value;
        var cnama = document.getElementById('nama').value;
                
        if (ckode==''){
            alert('Kode Golongan Tidak Boleh Kosong');
            exit();
        } 
        if (cnama==''){
            alert('Nama Golongan Tidak Boleh Kosong');
            exit();
        }

        
        if(lcstatus=='tambah'){ 
            lcinsert = "(kd_kelompok_barang,uraian_kelompok_barang)";
            lcvalues = "('"+ckode+"','"+cnama+"')";
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_master',
                    data: ({tabel:'ms_kelompok_standar_harga',kolom:lcinsert,nilai:lcvalues,cid:'kd_kelompok_barang',lcid:ckode}),
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
            
            lcquery = "UPDATE ms_kelompok_standar_harga SET nama_kel_brg='"+cnama+"' where kode_kel_brg='"+ckode+"'";

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
        judul = 'Edit Data KELOMPOK BARANG';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("kode").disabled=true;
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data KELOMPOK BARANG';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("kode").disabled=false;
        document.getElementById("kode").focus();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
     }    
    
     function hapus(){
        var ckode = document.getElementById('kode').value;
        
        var urll = '<?php echo base_url(); ?>index.php/master/hapus_master';
        $(document).ready(function(){
         $.post(urll,({tabel:'ms_kelompok_standar_harga',cnid:ckode,cid:'kode_kel_brg'}),function(data){
            status = data;
            if (status=='0'){
                alert('Gagal Hapus..!!');
                exit();
            } else {
                $('#dg').datagrid('deleteRow',lcidx);   
                alert('Data Berhasil Dihapus..!!');
                exit();
            }
         });
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
<h3 align="center"><u><b><a>INPUTAN MASTER KELOMPOK BARANG</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:100%;" border="0">
        <tr style="border-style:hidden;">
        <td width="10%" style="border-style:hidden;">
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a></td> 
        <td width="5%" style="border-style:hidden;"><a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a></td>
        <td><input type="text" value="" id="txtcari" style="width:300px;"/></td>
        </tr>
    </table>  
    
        <table id="dg" title="LISTING DATA KELOMPOK BARANG" style="width:900px;height:365px;" >  </table>  
    
        
 
    </p> 
    </div>   
</div>

<div id="dialog-modal" title="">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
     <table align="center" style="width:100%;" border="0">
           <tr>
                <td width="30%">KODE KELOMPOK BARANG</td>
                <td width="1%">:</td>
                <td><input type="text" id="kode" style="width:150px;"/></td>  
            </tr>            
            <tr>
                <td width="30%">NAMA KELOMPOK BARANG</td>
                <td width="1%">:</td>
                <td><input type="text" id="nama" style="width:500px;"/></td>  
            </tr>
            
            
            
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_fungsi();">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>

</body>

</html>