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
   
    <script>
    var kode='';
    var kegiatan='';
		

     $(document).ready(function() {
            $("#accordion").accordion();

            $( "#dialog-modal" ).dialog({
                height: 350,
                width: 650,
                modal: true,
                autoOpen:false
            });

        });
  
      $(function(){
        $('#cc').combogrid({  
            panelWidth:700,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka/skpduser',  
            columns:[[  
                {field:'kd_skpd',title:'Kode SKPD',width:100},  
                {field:'nm_skpd',title:'Nama SKPD',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
                kode = rowData.kd_skpd;
                giat(kode);

            }  
        }); 
      });
       
 
         $(function(){ 
         $('#dg').edatagrid({
    		url: '<?php echo base_url(); ?>/index.php/rka/kegi_sumber_sempurna',
            idField:'id',
            toolbar:"#toolbar",              
            rownumbers:"true", 
            fitColumns:"true",
            singleSelect:"true",
            columns:[[
        	    {field:'kd_kegiatan',
        		title:'Kode Kegiatan',
        		width:100},
                {field:'nm_kegiatan',
        		title:'Nama Kegiatan',
        		width:200},
                {field:'nilai',
        		title:'Nilai Penyempurnaan',
        		width:100,
                align:"right"},
                {field:'sumber',
        		title:'Sumber Dana Penyempurnaan',
        		width:100,
                align:"left"}
            ]],
            onSelect:function(rowIndex,rowData){                                       
                $("#kode").attr("value",rowData.kd_kegiatan);
		        $("#nama").attr("value",rowData.nm_kegiatan);
                $("#nilai").attr("value",rowData.nilai);
                $("#sdanalm").attr("value",rowData.sumber);
                $("#sdana1").combogrid("setValue",'');
            },
            onDblClickRow:function(rowIndex,rowData){
                edit_data('');
            }
        });
        });


            $(function(){
            var selectRow = null;
            artChanged = false;    
            $("#sdana1").combogrid({
                panelWidth:300,
                idField   :'nm_sdana',
                textField :'nm_sdana',
                mode      :'remote',
                url       : '<?php echo base_url(); ?>index.php/rka/ambil_sdana',
                columns   : [[
                {field:'kd_sdana',title:'Kode',width:100},
                {field:'nm_sdana',title:'Sumber Dana',width:190}
                ]],
                onSelect :function(rowIndex,rowData){
                    selectRow = rowData.nm_sdana;   
                    artChanged = true;

                },
                onChange: function(rowIndex,rowData){
                      artChanged = true;   
                      selectRow = rowData.nm_sdana;                                       
 
	            },
                onLoadSuccess : function (data) {  
                    var t = $(this).combogrid('getValue');
                    if (artChanged) {  
                    if (selectRow == null || t != selectRow) { 
                        $(this).combogrid('setValue', '');
                    } 
                    }  
                    
                    artChanged = false;  
                    selectRow = null;  
                },
                onHidePanel: function () {  
                   var t = $(this).combogrid('getValue');  
                    if (artChanged) {  
                    if (selectRow == null || t != selectRow) {
                        $(this).combogrid('setValue', '');  
                    } 
                    }  
                    artChanged = false;  
                    selectRow = null;  
                }             
            });
            });

      function giat(kode){
        $(function(){  
            $('#dg').edatagrid({
    		  url: '<?php echo base_url(); ?>/index.php/rka/kegi_sumber_sempurna/'+kode
            });
        });
      }


      function edit_data(kd_k){        
        lcstatus = 'edit';
        judul = 'Edit Sumber Dana Kegiatan';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("kode").focus();
        
      }    

     function keluar(){
        $("#dialog-modal").dialog('close');
        lcstatus = 'edit';
     }    
     
     function section1(){
         $(document).ready(function(){    
             $('#section1').click();                                               
         });
     }

        
  
    function simpan(){
       
        var ckode = document.getElementById('kode').value;
        var cnama = document.getElementById('nama').value;
        var csumber = $('#sdana1').combobox('getValue').trim();
               
        if (csumber==''){
            alert('Sumber Dana Tidak Boleh Kosong');
            return;
        }		   
        
        var npad = 0;
        var ndak = 0;
        var ndaknf = 0;
        var ndau = 0;
        var ndbhp = 0;
        var ndid = 0;
        
		var nu_nilai='nilai';
		
        switch (csumber) {
            case 'PAD':
                npad = nu_nilai;
            break;
            case 'DAK FISIK':
                ndak = nu_nilai;
            break;
            case 'DAK NON FISIK':
                ndaknf = nu_nilai;
            break;
            case 'DAU':
                ndau = nu_nilai;
            break;
            case 'DBHP':
                ndbhp = nu_nilai;
            break;
            case 'DID':
                ndid = nu_nilai;
            break;
        } 
        
        $(document).ready(function(){
        $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>/index.php/rka/update_sumber_kegi_sempurna',
            data: ({kegi:ckode,csumber1:csumber,snpad:npad,sndak:ndak,sndaknf:ndaknf,sndau:ndau,sndbhp:ndbhp,sndid:ndid}),
            dataType:"json",
            success:function(data){
                    status = data;
                    if (status=='0'){
                        alert('Gagal Simpan..!!');
                        return;
                    }else{
                        alert('Data Tersimpan..!!');
                    }
                }
        });
        });
        $('#dg').edatagrid('reload');
        $("#dialog-modal").dialog('close');    
    }
		
	
	function enter(ckey,_cid){
        if (ckey==13 || ckey==9){    	       	       	    	   
        	   document.getElementById(_cid).focus();            
        	}     
       
    }  
    

    </script>

    <STYLE TYPE="text/css"> 
        .satu{
            width: 150px;
        }
    </STYLE>
</head>
<body>



<div id="content"> 
   
<div id="accordion">
<h3><a href="#" id="section1">Sumber Dana Penyempurnaan Per Kegiatan</a></h3>
   <div  style="height: 350px;">
   <p>
        <h3>S K P D&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="cc" name="skpd" style="width: 400px;"/> </h3>
        <table id="dg" title="Kegiatan" style="width:870px;height:350px;" >  
        </table>  
   </p>
    </div>
    
</div>
</div>  	

<div id="dialog-modal" title="">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
     <table align="center" style="width:100%;" border="0">
           <tr>
                <td width="30%">KODE KEGIATAN</td>
                <td width="1%">:</td>
                <td width="69%"><input type="text" enable="true" id="kode" class="satu" disabled="true"/></td>  
            </tr>
                       
            <tr>
                <td >NAMA KEGIATAN</td>
                <td >:</td>
                <td><textarea style="margin:0;" width="100%" name="nama" id="nama" enable="true" cols="60" rows="1" ></textarea></td>  
            </tr>
           <tr>
                <td>Nilai Penyempurnaan</td>
                <td>:</td>
                <td ><input type="text" align="right" enable="true" id="nilai" class="satu" disabled="true" style="text-align: right;"/></td>  
            </tr>
           <tr>
                <td>Sumber Dana</td>
                <td>:</td>
                <td><input type="text" enable="true" id="sdanalm" class="satu" disabled="true"/></td>  
            </tr>
            <tr>
                <td >Sumber Dana Baru</td>
                <td >:</td>
                <td><input id="sdana1" name="sdana1" style="width:155px;"/></td>  
            </tr>
            
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center"><a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();  ">Simpan</a>
                <a id="back" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>


</body>

</html>