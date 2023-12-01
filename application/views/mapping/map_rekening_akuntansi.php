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
            height: 300,
            width: 900,
            modal: true,
            autoOpen:false
        });
             
        });    
     
     $(function(){  
        
     $('#kd_rek6').combogrid({  
       panelWidth:500,  
       idField:'kd_rek6',  
       textField:'kd_rek6',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/mapping/get_rekening6',  
       columns:[[  
           {field:'kd_rek6',title:'Kode',width:100},  
           {field:'nm_rek6',title:'Rekening',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
            $("#nm_rek6").attr("value",rowData.nm_rek6.toUpperCase());           
       }  
     });


     $('#kd_rek64').combogrid({  
       panelWidth:500,  
       idField:'kd_rek64',  
       textField:'kd_rek64',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/mapping/get_rekening64',  
       columns:[[  
           {field:'kd_rek64',title:'Kode',width:100},  
           {field:'nm_rek64',title:'Rekening',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
            $("#nm_rek64").attr("value",rowData.nm_rek64.toUpperCase());           
       }  
     });

     $('#kd_reklo').combogrid({  
       panelWidth:500,  
       idField:'kd_rek6',  
       textField:'kd_rek6',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/mapping/get_rekening90lo',  
       columns:[[  
           {field:'kd_rek6',title:'Kode',width:100},  
           {field:'nm_rek6',title:'Rekening',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
            $("#nm_reklo").attr("value",rowData.nm_rek6.toUpperCase());           
       }  
     }); 


     $('#kd_rekpiu').combogrid({  
       panelWidth:500,  
       idField:'kd_rek6',  
       textField:'kd_rek6',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/mapping/get_rekening90piu',  
       columns:[[  
           {field:'kd_rek6',title:'Kode',width:100},  
           {field:'nm_rek6',title:'Rekening',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
            $("#nm_rekpiu").attr("value",rowData.nm_rek6.toUpperCase());
       }  
     }); 
        
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/mapping/load_mapping_rekening_akuntansi',
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
            width:15,
            align:"center"},
            {field:'nm_rek6',
            title:'Nama Rekening',
            width:50},
            {field:'kd_lo',
            title:'LO',
            width:15,
            align:"center"},
            {field:'kd_piu',
            title:'Piutang/Utang',
            width:15},
            {field:'kd_rek64',
            title:'64',
            width:15}
        ]],onSelect:function(rowData,rowData){    
            $("#kd_rek6").combogrid("setValue",rowData.kd_rek6);
            $("#kd_rek6_edit").attr("value",rowData.kd_rek6);
            $("#kd_reklo").combogrid("setValue",rowData.kd_lo);
            $("#kd_rekpiu").combogrid("setValue",rowData.kd_piu);
            $("#kd_rek64").combogrid("setValue",rowData.kd_rek64);
            // $("#kode").combogrid("setValue",kdskpd);

         },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data Mapping Rekening'; 
           edit_data(); 
           load_detail();  
        }
        
        });

    
       
    });        


    function load_detail() {
        
        var kk = document.getElementById("kd_rek6_edit").value; 

        $('#dg2').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/mapping/load_detail_mapping_rekening',
        queryParams   : ({rekening:kk}),
        idField       : 'id',
        toolbar       : "#toolbar",              
        rownumbers    : "true", 
        fitColumns    : "false",
        autoRowHeight : "false",
        singleSelect  : "true",
        nowrap        : "false",          
        columns       : [[{field:'kd_rek13',title:'Kode 13',width:50},
                          {field:'nm_rek13',title:'Rekening 13',width:70},
                          {field:'kd_rek64',title:'Kode 64',width:50},
                          {field:'nm_rek64',title:'Rekening 64',width:70},
                          {field:'kd_rek90',title:'Kode 90',width:50},
                          {field:'nm_rek90',title:'Rekening 90',width:70},
                          // {field:'kd_rek90_lo',title:'Kode 90 LO',width:50},
                          // {field:'nm_rek90_lo',title:'Rekening 90 LO',width:70}, 
                          // {field:'kd_rek90_piu',title:'Kode 90 LO',width:50},
                          // {field:'nm_rek90_piu',title:'Rekening 90 LO',width:70},  
                          {field:'hapus',     title:'Hapus',        width:70, align:"center",
                          formatter:function(value,rec){ 
                          return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                          }
                          }
                         ]]
        });
    }


    function hapus_detail(){
        
       var rows = $('#dg2').edatagrid('getSelected'); 
         var ckd_rek13 = rows.kd_rek13;        
         var cnm_rek13 =  rows.nm_rek13;         
         var idx  = $('#dg2').edatagrid('getRowIndex',rows);
         var tny = confirm('Yakin Ingin Menghapus Data, Rekening : '+cnm_rek13);
         if (tny==true){                                      
             $('#dg2').edatagrid('deleteRow',idx);
         }     
    }

       
    function kosong(){
        $("#kd_rek64").combogrid("setValue",'');
        $("#nm_rek64").attr("value",'');
        $("#kd_reklo").combogrid("setValue",'');
        $("#nm_reklo").attr("value",'');
        $("#kd_rekpiu").combogrid("setValue",'');
        $("#nm_rekpiu").attr("value",'');
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/mapping/load_mapping_rekening_akuntansi',
        queryParams:({cari:kriteria})
        });        
     });
    }

function simpan(){
  var ckdrek6   = $('#kd_rek6').combogrid('getValue');
  var ckdreklo  = $('#kd_reklo').combogrid('getValue');
  var ckdrekpiu = $('#kd_rekpiu').combogrid('getValue');
  var ckdrek64  = $('#kd_rek64').combogrid('getValue');
  var cnmrek64  = document.getElementById('nm_rek64').value ;

                  if (ckdrek6==''){
                    alert ('Silahkan pilih kode Rekening 90 terlebih dahulu');
                    exit();
                  }


                  var tny = confirm('Apakah data mapping rekening sudah sesuai ???');
                  if (tny==true){                                      
                     lcquery = "UPDATE ms_rek6 SET kd_rek64='"+ckdrek64+"',nm_rek64='"+cnmrek64+"',map_lo='"+ckdreklo+"',piutang_utang='"+ckdrekpiu+"'  where kd_rek6='"+ckdrek6+"'";
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

                                  $('#dg').edatagrid('reload'); 

                                  kosong();
                              }
                      });
                      });
            


                  }  
        $('#dg').edatagrid('reload'); 

    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data SKPD';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        }    
  
     function keluar(){
        kosong();
        $("#dialog-modal").dialog('close');
        $('#dg').edatagrid('reload'); 
     }    
    
     function hapus(){
        var ckode = document.getElementById('kd_rek13_edit').value ;
        if (ckode==''){
                    alert ('Silahkan pilih data yang akan dihapus terlebih dahulu');
                    exit();
                  }
        
        var urll = '<?php echo base_url(); ?>index.php/mapping/hapus_skpd';
        $(document).ready(function(){
         $.post(urll,({tabel:'ms_rekening',cnid:ckode,cid:'kd_rek13'}),function(data){
            status = data;
            if (status=='0'){
                alert('Gagal Hapus..!!');
                exit();
            } else {
                $('#dg').datagrid('deleteRow',lcidx);   
                alert('Data Berhasil Dihapus..!!');
                keluar();
                exit();
                
            }
         });
        });    
    } 
    

    
  
   </script>

</head>
<body>

<div id="content"> 
<h3 align="center"><u><b><a>INPUTAN MAPPING REKENING AKUNTANSI</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:400px;" border="0">
        <tr>
        <td width="10%">
        <!-- <button type="submit" class="easyui-linkbutton" plain="true" onclick="javascript:tambah();load_detail();"><i class="fa fa-plus"></i> Tambah</button> --></td>               
        
        <td width="5%"><button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:cari();"><i class="fa fa-search"></i></button></td>
        <td><input type="text" value="" id="txtcari" style="width:300px;"/></td>
        </tr>
        <tr>
        <td colspan="4">
        <table id="dg" title="LISTING MAPPING REKENING AKUNTANSI" style="width:900px;height:440px;" >  
        </table>
        </td>
        </tr>
    </table>    
    
        
 
    </p> 
    </div>   
</div>

<div id="dialog-modal" title="">
    <p class="validateTips">MAPPING KODE REKENING</p> 
    <fieldset>
     <table align="center" style="width:100%;" border="0">
           <tr>
                <td width="30%">KODE REKENING</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_rek6" style="width:150px;" disabled/>&nbsp;&nbsp;<input type="text" id="nm_rek6" style="width:360px;" disabled/><input type="hidden" id="kd_rek6_edit" style="width:360px;" disabled/></td>  
            </tr>
                       
            <tr>
                <td width="30%">KODE REKENING 64</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_rek64" style="width:150px;"/>&nbsp;&nbsp;<input type="text" id="nm_rek64" style="width:360px;" disabled/>
                  <input type="hidden" id="status" style="width:360px;" disabled/></td>  
            </tr>
            <tr>
                <td width="30%">KODE REKENING LO</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_reklo" style="width:150px;"/>&nbsp;&nbsp;<input type="text" id="nm_reklo" style="width:360px;" disabled/></td>  
            </tr>
            <tr>
                <td width="30%">KODE REKENING UTANG/PIUTANG</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_rekpiu" style="width:150px;"/>&nbsp;&nbsp;<input type="text" id="nm_rekpiu" style="width:360px;" disabled/></td>  
            </tr>            
            
            
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
        </table>
        <table style="width:755px;" border='0'>
            <tr>
                <td colspan="3" align="center">
                  <button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:simpan();"><i class="fa fa-save"></i> Simpan</button>
                  <button type="delete" class="easyui-linkbutton" plain="true" onclick="javascript:hapus();"><i class="fa fa-window-close"></i> Hapus</button>
                  <button type="edit" class="easyui-linkbutton" plain="true" onclick="javascript:keluar();"><i class="fa fa-arrow-left"></i> Kembali</button>
                </td>                
            </tr>
    </table>

    </fieldset>
</div>

</body>

</html>