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
        
     $('#kode_f').combogrid({  
       panelWidth:500,  
       idField:'kd_rek6',  
       textField:'kd_rek6',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/load_rek6',  
       columns:[[  
           {field:'kd_rek6',title:'Kode Kode',width:100},  
           {field:'nm_rek6',title:'Nama Rekening',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           $("#nm_f").attr("value",rowData.nm_rek6.toUpperCase());                
       }  
     });     
        
        
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/master/load_rekpot_spm',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
            {field:'kd_rek6',
            title:'Kode Rekening',
            width:90,
            align:"center"},
            {field:'nm_rek6',
            title:'Nama Rekening',
            width:650,
            align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          kd_f = rowData.kd_rek6;
          nm_f = rowData.nm_rek6;
          kelompok = rowData.kelompok;
          get(kd_f,nm_f,kelompok); 
          lcidx = rowIndex;  
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data Urusan'; 
           edit_data();   
        }
        
        });
       
    });        

 
    
    function get(kd_f,nm_f,kelompok) {
        
        $("#kode_f").combogrid("setValue",kd_f);
        $("#nm_f").attr("value",nm_f);
        $("#kelompok").attr("value",kelompok);     
                       
    }
       
    function kosong(){
        $("#kode_f").combogrid("setValue",'');
        $('#kode_f').combogrid("enable"); 
        $("#nm_f").attr("value",'');
        $("#kelompok").attr("value",'');
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/master/load_rekpot_spm',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
       function simpan_potongan(){
       
        var ckode_f= $('#kode_f').combogrid('getValue');
        var cnama = document.getElementById('nm_f').value;
        var ckelompok = document.getElementById('kelompok').value;
                
        if (ckode_f==''){
            alert('Kode Rekening Tidak Boleh Kosong');
            exit();
        } 
        if (cnama==''){
            alert('Nama Rekening Tidak Boleh Kosong');
            exit();
        }

        
        if(lcstatus=='tambah'){ 
            
            lcinsert = "(kd_rek6,kd_rek5,nm_rek6,map_pot,nm_pot,kelompok)";
            lcvalues = "('"+ckode_f+"','"+ckode_f.substring(0,8)+"','"+cnama+"','"+ckode_f+"','"+cnama+"','"+ckelompok+"')";
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_rekpot',
                    data: ({tabel:'ms_pot',kolom:lcinsert,nilai:lcvalues,cid:'kd_rek6',lcid:ckode_f}),
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
            
            lcquery = "UPDATE ms_pot SET nm_rek6='"+cnama+"',nm_pot='"+cnama+"',kelompok='"+ckelompok+"' where kd_rek6='"+ckode_f+"'";

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
        judul = 'Edit Data Urusan';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("kode").disabled=true;
        $('#kode_f').combogrid("disable"); 
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Urusan';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("kode").disabled=false;
        document.getElementById("kode").focus();
        $('#kode_f').combogrid("enable"); 
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
     }    
    
     function hapus(){
        // var ckode = document.getElementById('kode_f').value;
        var ckode= $('#kode_f').combogrid('getValue');
        
        var urll = '<?php echo base_url(); ?>index.php/master/hapus_master';
        $(document).ready(function(){
         $.post(urll,({tabel:'ms_pot',cnid:ckode,cid:'kd_rek6'}),function(data){
            status = data;
            if (status=='0'){
                alert('Gagal Hapus..!!');
                exit();
            } else {
                $('#dg').datagrid('deleteRow',lcidx);   
                alert('Data Berhasil Dihapus..!!');
                exit();
                keluar();
            }
         });
        });    
    } 
    
  
    
  
   </script>

</head>
<body>

<div id="content"> 
<h3 align="center"><u><b><a>INPUTAN MASTER REKENING POTONGAN SPM</a></b></u></h3>
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
        <table id="dg" title="LISTING DATA REKENING POTONGAN" style="width:900px;height:440px;" >  
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
                <td width="30%">KODE REKENING POTONGAN</td>
                <td width="1%">:</td>
                <td><input type="text" id="kode_f" style="width:150px;"/><br><input type="text" id="nm_f" style="width:310px;" readonly="true" /></td>  
            </tr> 
            
            <tr>
                <td width="30%">Mengurangi Jumlah Pembayaran</td>
                <td width="1%">:</td>
                <td>
                    <select id="kelompok" style="width:150px;">
                        <option value='0'>Pilih</option>
                        <option value='1'>Ya</option>
                        <option value='2'>Tidak</option>
                    </select>
                </td>  
            </tr> 
            
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_potongan();">Simpan</a>
                <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>

</body>

</html>