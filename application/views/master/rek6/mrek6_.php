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
            height: 460,
            width: 800,
            modal: true,
            autoOpen:false
        });
        });    
     
     $(function(){  
        
     $('#rek64').combogrid({  
       panelWidth:500,  
       idField:'kd_rek4',  
       textField:'kd_rek4',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_rekening4_64',  
       columns:[[  
           {field:'kd_rek4',title:'Kode Rekening',width:100},  
           {field:'nm_rek4',title:'Nama Rekening',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
            kd_rek64 = rowData.kd_rek4;
            $("#nm_64").attr("value",rowData.nm_rek4.toUpperCase());
           // muncul();                
       }  
     });



     $('#lo').combogrid({  
       panelWidth:500,  
       idField:'kd_rek6',  
       textField:'kd_rek6',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_rekening_lo',  
       columns:[[  
           {field:'kd_rek6',title:'Kode Rekening',width:100},  
           {field:'nm_rek6',title:'Nama Rekening',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
            lo = rowData.kd_rek6;
            $("#nm_lo").attr("value",rowData.nm_rek6);
           // muncul();                
       }  
     });


     $('#piutang_utang').combogrid({  
       panelWidth:500,  
       idField:'kd_rek6',  
       textField:'kd_rek6',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_rekening_piu',  
       columns:[[  
           {field:'kd_rek6',title:'Kode Rekening',width:100},  
           {field:'nm_rek6',title:'Nama Rekening',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
            lo = rowData.kd_rek6;
            $("#nm_piu").attr("value",rowData.nm_rek6);
           // muncul();                
       }  
     });
     
     $('#kd_5').combogrid({  
       panelWidth:500,  
       idField:'kd_rek5',  
       textField:'kd_rek5',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_rekening5',  
       columns:[[  
           {field:'kd_rek5',title:'Kode Rekening',width:100},  
           {field:'nm_rek5',title:'Nama Rekening',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
            kd_5 = rowData.kd_rek5;
            $("#nm_5").attr("value",rowData.nm_rek5);
            $("#kd_6").combogrid("setValue",rowData.kd_rek6);
            $("#nm_6").attr("value",rowData.nm_rek6);
           // muncul();                
       }  
     });  

     $('#kd_6').combogrid({  
       panelWidth:500,  
       idField:'kd_rek6',  
       textField:'kd_rek6',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/get_rekening6',  
       columns:[[  
           {field:'kd_rek6',title:'Kode Rekening',width:100},  
           {field:'nm_rek6',title:'Nama Rekening',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
            kd_6 = rowData.kd_rek6;
            $("#nm_6").attr("value",rowData.nm_rek6);
           // muncul();                
       }  
     });   
     
     $('#kd_13').combogrid({  
       panelWidth:500,  
       idField:'kd_rek13',  
       textField:'kd_rek13',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_rekening13',  
       columns:[[  
           {field:'kd_rek13',title:'Kode Rekening',width:100},  
           {field:'nm_rek13',title:'Nama Rekening',width:400},    
       ]],  
       onSelect:function(rowIndex,rowData){
            kd_13 = rowData.kd_rek13;
            $("#nm_13").attr("value",rowData.nm_rek13);
            $("#kd_64").combogrid("setValue",rowData.kd_rek64);
            $("#nm_64").attr("value",rowData.nm_rek64);
           // muncul();                
       }  
     });  

     $('#kd_64').combogrid({  
       panelWidth:500,  
       idField:'kd_rek64',  
       textField:'kd_rek64',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_rekening64',  
       columns:[[  
           {field:'kd_rek64',title:'Kode Rekening',width:100},  
           {field:'nm_rek64',title:'Nama Rekening',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
            kd_64 = rowData.kd_rek64;
            $("#nm_64").attr("value",rowData.nm_rek64);
           // muncul();                
       }  
     });
        
     $('#dg').edatagrid({
    url: '<?php echo base_url(); ?>/index.php/master/load_rekening6',
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
        title:'KODE',
        width:10,
            align:"center"},
            {field:'nm_rek6',
        title:'REKENING',
        width:20},
          {field:'map_lo',
        title:'LO',
        width:10,
            align:"center"},           
            {field:'piutang_utang',
        title:'PIUTANG/UTANG',
        width:10},
           {field:'kd_rek64',
       title:'64',
       width:15,
           align:"center"}
        ]],
        onSelect:function(rowIndex,rowData){
          kd_13          = rowData.kd_rek13;
          nm_13          = rowData.nm_rek13;
          kd_64         = rowData.kd_rek64;
          nm_64          = rowData.nm_rek64;
          lo            = rowData.map_lo;
          piutang_utang = rowData.piutang_utang;
          kd_6          = rowData.kd_rek6;
          nm_6          = rowData.nm_rek6;
          kd_5          = rowData.kd_rek5;
          nm_5          = rowData.nm_rek5;
          nm_lo         = rowData.nm_lo;
          nm_piu         = rowData.nm_piu;
          get(kd_13,kd_5,kd_64,kd_6,nm_6,lo,piutang_utang,nm_lo,nm_piu,nm_5,nm_64,nm_13); 
          lcidx         = rowIndex;  
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           kd_13 = rowData.kd_rek13;
           judul = 'Edit Data Urusan'; 
           edit_data(kd_13);   
        }
        
        });
       
    });        

 
    
    function get(kd_13,kd_5,kd_64,kd_6,nm_6,lo,piutang_utang,nm_lo,nm_piu,nm_5,nm_64,nm_13) {
        
        $("#kd_6").combogrid("setValue",kd_6);
        //$("#kode6").attr("value",kd_6);
        $("#kd_64").combogrid("setValue",kd_64);
        //$("#kd_64").attr("value",kd_64);
        $("#kode64").attr("value",kd_64);
        $("#kd_5").combogrid("setValue",kd_5);
        $("#kd_13").combogrid("setValue",kd_13);
        $("#nm_6").attr("value",nm_6);
        $("#nm_5").attr("value",nm_5);
        $("#nm_64").attr("value",nm_64);
        $("#nm_13").attr("value",nm_13);
        $("#piutang_utang").combogrid("setValue",piutang_utang);
        $("#lo").combogrid("setValue",lo);
        $("#nm_lo").attr("value",nm_lo);   
        $("#nm_piu").attr("value",nm_piu);   
        
        
                       
    }
       
    function kosong(){
        $("#kd_6").combogrid("setValue",'');
        //$("#kode6").attr("value",'');
        $("#kd_64").combogrid("setValue",'');
        //$("#kd_64").attr("value",'');
        $("#kode64").attr("value",'');
        $("#kd_5").combogrid("setValue",'');
        $("#kd_13").combogrid("setValue",'');
        $("#nm_6").attr("value",'');
        $("#nm_5").attr("value",'');
        $("#nm_64").attr("value",'');
        $("#nm_13").attr("value",'');
        $("#piutang_utang").combogrid("setValue",'');
        $("#lo").combogrid("setValue",'');
        $("#nm_lo").attr("value",''); 
        $("#nm_piu").attr("value",'');
    }
    
    function muncul(){
        //alert(kd_s);
        var c_urus=kd_urus+'.';
        var c_skpd=kd_s;
        if(lcstatus=='tambah'){ 
            $("#kode").attr("value",c_urus);
        } else {
            $("#kode").attr("value",c_skpd);
        }     
    }
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
    url: '<?php echo base_url(); ?>/index.php/master/load_rekening6',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
    function simpan_rek6(){
       
        var ckd_6   = $.trim($('#kd_6').combogrid('getValue'));
        //var ckode6  = $.trim(document.getElementById('kode6').value);
        var ckode64 = $.trim(document.getElementById('kode64').value);
        //var ckode64   = $.trim($('#kode64').combogrid('getValue'));
        var ckd_64   = $.trim($('#kd_64').combogrid('getValue'));
        var ckd_5   = $.trim($('#kd_5').combogrid('getValue'));
        var ckd_13   = $.trim($('#kd_13').combogrid('getValue'));
        var clo     = $.trim($('#lo').combogrid('getValue'));
        var cnm_6   = $.trim(document.getElementById('nm_6').value);
        var cnm_5   = $.trim(document.getElementById('nm_5').value);
        var cnm_64   = $.trim(document.getElementById('nm_64').value);
        var cnm_13   = $.trim(document.getElementById('nm_13').value);
        var cnm_lo  = $.trim(document.getElementById('nm_lo').value);
        var cpiu    = $.trim($('#piutang_utang').combogrid('getValue'));
        var cnm_piu = $.trim(document.getElementById('nm_piu').value);
        if (ckd_6==''){
            alert('Kode1  Tidak Boleh Kosong');
            exit();
        } 
        if (ckd_5==''){
            alert('Kode2  Tidak Boleh Kosong');
            exit();
        } 
        if (cnm_6==''){
            alert('Nama  Tidak Boleh Kosong');
            exit();
        }

        if (ckd_13==''){
            alert('Kode3  Tidak Boleh Kosong');
            exit();
        }
        
        if(lcstatus=='tambah'){ 
            
            lcinsert  = "(kd_rek5,kd_rek6,nm_rek6,map_lo,piutang_utang,kd_rek64,nm_rek64,kd_rek13,nm_rek13)";
            lcvalues  = "('"+ckd_5+"','"+ckd_6+"','"+cnm_6+"','"+clo+"','"+cpiu+"','"+ckd_64+"','"+cnm_64+"','"+ckd_13+"','"+cnm_13+"')";
            //lcinsert1 = "(kd_rek5,nm_rek5)";
            
            // lcinsert1 = "(kd_rek64,nm_rek64)";
            // lcvalues1 = "('"+clo+"','"+cnamalo+"')";
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_master',
                    data: ({tabel:'ms_rek_mapping',kolom:lcinsert,nilai:lcvalues,cid:'kd_rek13',lcid:ckode64}),
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
            
            lcquery = "UPDATE ms_rek_mapping SET kd_rek6='"+ckd_6+"',nm_rek6='"+cnm_6+"',kd_rek5='"+ckd_5+"',kd_rek64='"+ckd_64+"',nm_rek64='"+cnm_64+"',map_lo='"+clo+"',piutang_utang='"+cpiu+"',kd_rek13='"+ckd_13+"',nm_rek13='"+cnm_13+"' where kd_rek13='"+ckd_13+"'";

            /*lcquery = "UPDATE ms_rek6 SET kd_rek6='"+ckd_6+"',nm_rek6='"+cnm_6+"',kd_rek5='"+ckd_5+"',kd_rek64='"+ckode64+"',map_lo='"+clo+"',piutang_utang='"+cpiu+"',kd_rek13='"+ckd_13+"',nm_rek13='"+cnm_13+"' where kd_rek6='"+ckode6+"' "+
            "update trdrka set nm_rek5='"+cnm_6+"' where kd_rek5='"+ckode6+"' "+
            "update trdspd set nm_rek5='"+cnm_6+"' where kd_rek5='"+ckode6+"' "+
            "update trdspp set nm_rek5='"+cnm_6+"' where kd_rek5='"+ckode6+"' "+
            "update trdtransout set nm_rek5='"+cnm_6+"' where kd_rek5='"+ckode6+"' ";*/
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
        
        
        alert("Data Berhasil disimpan");
        $("#dialog-modal").dialog('close');
        $('#dg').edatagrid('reload'); 

    } 
    
      function edit_data(kdrek13){
            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/master/cari1/'+kdrek13,
                data: ({tabel:'ms_rek_mapping',field:'kd_rek13'}),
                dataType:"json",
                success:function(data){
                        status = data;
                        if (status==0){
                            $('#hapus').linkbutton('enable');
                        }else{
                            $('#hapus').linkbutton('enable');
                        }
                    }
            });
            });

        lcstatus = 'edit';
        judul = 'Edit Data Rincian Objek';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        //document.getElementById("kode").disabled=true;
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Rincian Objek';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("kd_13").disabled=false;
        document.getElementById("kd_13").focus();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
        //lcstatus = 'edit';
     }    
     

     
     function hapus(){
        //var ckode = document.getElementById('kd_13').value;
        var ckode   = $.trim($('#kd_6').combogrid('getValue'));
        
        
        var urll = '<?php echo base_url(); ?>index.php/master/hapus_master';
        $(document).ready(function(){
         $.post(urll,({tabel:'ms_rek_mapping',cnid:ckode,cid:'kd_rek6'}),function(data){
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
<h3 align="center"><u><b><a>INPUTAN MASTER REKENING RINCIAN OBJEK</a></b></u></h3>
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
        <table id="dg" title="LISTING DATA REKENING RINCIAN OBJEK" style="width:900px;height:440px;" >  
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
         <!--  <tr>
                <td width="30%">KODE REKENING 13</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_13" style="width:100px;"/><input type="text" id="nm_13" style="width:310px;"/></td>  
            </tr>  -->
            <tr>
                <td width="30%">KODE REKENING SUB RINCIAN OBJEK 90</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_6" style="width:100px;"/></td>  
            </tr>
            <tr>
                <td width="30%">NAMA REKENING SUB RINCIAN OBJEK 90</td>
                <td width="1%">:</td>
                <td><input type="text" id="nm_6" style="width:360px;"/></td>  
            </tr>

            <tr>
                <td width="30%">KODE REKENING 64</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_64" style="width:100px;"/><input type="hidden" id="kode64" style="width: 140px;" readonly="true"/></td>  
            </tr>
            <tr>
                <td width="30%">NAMA REKENING 64</td>
                <td width="1%">:</td>
                <td><input type="text" id="nm_64" style="width:360px;"/></td>  
            </tr>
            <tr>
                <td width="30%">MAP LO</td>
                <td width="1%">:</td>
                <td><input type="text" id="lo" style="width:100px;"/><input type="text" id="nm_lo" style="width:310px;"/></td>  
            </tr>        
            <tr>
                <td width="30%">MAP PIUTANG/UTANG</td>
                <td width="1%">:</td>
                <td><input type="text" id="piutang_utang" style="width:100px;"/><input type="text" id="nm_piu" style="width:310px;"/></td>  
            </tr>
            <tr>
                <td width="30%">KODE RINCIAN OBJEK 90</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_5" style="width:100px;"/><input type="text" id="nm_5" style="width:310px;"/></td>  
            </tr> 
           
                 
            
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_rek6();">Simpan</a>
            <a id="hapus" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>

</body>

</html>