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
            $("#nm_lo").attr("value",rowData.nm_rek6.toUpperCase());
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
            $("#nm_piu").attr("value",rowData.nm_rek6.toUpperCase());
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
            $("#nm_5").attr("value",rowData.nm_rek5.toUpperCase());
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
    		title:'Kode Sub RO',
    		width:10,
            align:"center"},
            {field:'nm_rek6',
    		title:'Nama Sub RO',
    		width:50},
    	    {field:'kd_rek5',
    		title:'Kode RO',
    		width:10,
            align:"center"},           
            {field:'kd_rek64',
    		title:'Kode P64',
    		width:10}//,
//            {field:'map_lo',
//    		title:'MAP LO',
//    		width:15,
//            align:"center"}
        ]],
        onSelect:function(rowIndex,rowData){
          kd_5          = rowData.kd_rek5;
          kd_64         = rowData.kd_rek64;
          kd_6          = rowData.kd_rek6;
          nm_6          = rowData.nm_rek6;
          nm_5          = rowData.nm_rek5;
          lo            = rowData.map_lo;
          piutang_utang = rowData.piutang_utang;
          nm_lo         = rowData.nm_reklo;
          get(kd_5,kd_64,kd_6,nm_6,lo,piutang_utang,nm_lo,nm_5); 
          lcidx         = rowIndex;  
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           kd_6 = rowData.kd_rek6;
           judul = 'Edit Data Urusan'; 
           edit_data(kd_6);   
        }
        
        });
       
    });        

 
    
    function get(kd_5,kd_64,kd_6,nm_6,lo,piutang_utang,nm_lo,nm_5) {
        
        $("#kd_6").attr("value",kd_6);
        $("#kode6").attr("value",kd_6);
        $("#kd_64").attr("value",kd_64);
        $("#kode64").attr("value",kd_64);
        $("#kd_5").combogrid("setValue",kd_5);
        $("#nm_6").attr("value",nm_6);
        $("#nm_5").attr("value",nm_5);
        $("#piutang_utang").combogrid("setValue",piutang_utang);
        $("#lo").combogrid("setValue",lo);
        $("#nm_lo").attr("value",nm_lo);   
        
        
                       
    }
       
    function kosong(){
        $("#kd_6").attr("value",'');
        $("#kode6").attr("value",'');
        $("#kd_64").attr("value",'');
        $("#kode64").attr("value",'');
        $("#kd_5").combogrid("setValue",'');
        $("#nm_6").attr("value",'');
        $("#nm_5").attr("value",'');
        $("#piutang_utang").combogrid("setValue",'');
        $("#lo").combogrid("setValue",'');
        $("#nm_lo").attr("value",'');   
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
       
        var ckd_6    = $.trim(document.getElementById('kd_6').value);
        var ckode6  = $.trim(document.getElementById('kode6').value);
        var ckode64 = $.trim(document.getElementById('kode64').value);
        var ckd_64  = $.trim(document.getElementById('kd_64').value);
        var ckd_5   = $.trim($('#kd_5').combogrid('getValue'));
        var clo     = $.trim($('#lo').combogrid('getValue'));
        var cnm_6   = $.trim(document.getElementById('nm_6').value);
        var cnm_5   = $.trim(document.getElementById('nm_5').value);
        var cnm_lo  = $.trim(document.getElementById('nm_lo').value);
        var cpiu    = $.trim($('#piutang_utang').combogrid('getValue'));
        var cnm_piu = $.trim(document.getElementById('nm_piu').value);
        if (ckd_6==''){
            alert('Kode  Tidak Boleh Kosong');
            exit();
        } 
        if (ckd_5==''){
            alert('Kode  Tidak Boleh Kosong');
            exit();
        } 
        if (cnm_6==''){
            alert('Nama  Tidak Boleh Kosong');
            exit();
        }
        
        if(lcstatus=='tambah'){ 
            
            lcinsert  = "(kd_rek5,kd_rek6,nm_rek6,map_lo,piutang_utang,kd_rek64)";
            lcvalues  = "('"+ckd_5+"','"+ckd_6+"','"+cnm_6+"','"+clo+"','"+cpiu+"','"+ckd_64+"')";
            //lcinsert1 = "(kd_rek5,nm_rek5)";
            
            // lcinsert1 = "(kd_rek64,nm_rek64)";
            // lcvalues1 = "('"+clo+"','"+cnamalo+"')";
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_master',
                    data: ({tabel:'ms_rek6',kolom:lcinsert,nilai:lcvalues,cid:'kd_rek6',lcid:ckode64}),
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
            
            lcquery = "UPDATE ms_rek6 SET kd_rek6='"+ckd_6+"',nm_rek6='"+cnm_6+"',kd_rek5='"+ckd_5+"',kd_rek64='"+ckode64+"',map_lo='"+clo+"',piutang_utang='"+cpiu+"' where kd_rek6='"+ckode6+"' "+
					  "update trdrka set nm_rek5='"+cnm_6+"' where kd_rek5='"+ckode6+"' "+
					  "update trdspd set nm_rek5='"+cnm_6+"' where kd_rek5='"+ckode6+"' "+
					  "update trdspp set nm_rek5='"+cnm_6+"' where kd_rek5='"+ckode6+"' "+
					  "update trdtransout set nm_rek5='"+cnm_6+"' where kd_rek5='"+ckode6+"'";

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
    
      function edit_data(kdrek6){
            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/master/cari1/'+kdrek6,
                data: ({tabel:'ms_rek6',field:'kd_rek5'}),
                dataType:"json",
                success:function(data){
                        status = data;
                        if (status==0){
                            $('#hapus').linkbutton('enable');
                        }else{
                            $('#hapus').linkbutton('disable');
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
        document.getElementById("kd_6").disabled=false;
        document.getElementById("kd_6").focus();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
        //lcstatus = 'edit';
     }    
     

     
     function hapus(){
        var ckode = document.getElementById('kd_6').value;
        
        var urll = '<?php echo base_url(); ?>index.php/master/hapus_master';
        $(document).ready(function(){
         $.post(urll,({tabel:'ms_rek6',cnid:ckode,cid:'kd_rek6'}),function(data){
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
            <tr>
                <td width="30%">KODE RINCIAN OBJEK</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_5" style="width:100px;"/><input type="text" id="nm_5" style="width:310px;"/></td>  
            </tr> 
           <tr>
                <td width="30%">KODE REKENING SUB RINCIAN OBJEK</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_6" style="width:100px;"/><input type="hidden" id="kode6" style="width: 140px;" readonly="true"/></td>  
            </tr>
            <tr>
                <td width="30%">NAMA REKENING SUB RINCIAN OBJEK</td>
                <td width="1%">:</td>
                <td><input type="text" id="nm_6" style="width:360px;"/></td>  
            </tr>
            <tr>
                <td width="30%">KODE REKENING 64</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_64" style="width:100px;"/><input type="hidden" id="kode64" style="width: 140px;" readonly="true"/></td>  
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