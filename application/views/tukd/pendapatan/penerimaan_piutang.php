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
    <style>    
    #tagih {
        position : relative;
        width    : 500px;
        height   : 70px;
        padding  : 0.4em;
    }  
    
    </style>
    <script type="text/javascript">
    
    var kode     = '';
    var giat     = '';
    var nomor    = '';
    var judul    = '';
    var cid      = 0;
    var lcidx    = 0;
    var lcstatus = '';
                    
    $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height   : 400,
            width    : 900,
            modal    : true,
            autoOpen : false,
        });
        $("#tagih").hide();
          get_skpd(); 
		      get_tahun();
        });    
    
     
     $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/penerimaan/load_terima_tl',
        idField      : 'id',            
        rownumbers   : "true", 
        fitColumns   : "true",
        singleSelect : "true",
        autoRowHeight: "false",
        loadMsg      : "Tunggu Sebentar....!!",
        pagination   : "true",
        nowrap       : "true",                       
        columns:[[
    	    {field:'no_terima',
    		title:'Nomor Terima',
    		width:50,
            align:"center"},
            {field:'tgl_terima',
    		title:'Tanggal',
    		width:30},
            {field:'kd_skpd',
    		title:'S K P D',
    		width:30,
            align:"center"},
            {field:'kd_rek6',
    		title:'Rekening',
    		width:50,
            align:"center"},
            {field:'nilai',
    		title:'Nilai',
    		width:50,
            align:"center"}
        ]],
        onSelect:function(rowIndex,rowData){
          nomor     = rowData.no_terima;
          no_tetap  = rowData.no_tetap;
          tgl       = rowData.tgl_terima;
          kode      = rowData.kd_skpd;
          lcket     = rowData.keterangan;
          lcrek     = rowData.kd_rek6;
          rek       = rowData.kd_rek;
          lcnilai   = rowData.nilai;
          sts       = rowData.sts_tetap;
		      giat	    = rowData.kd_sub_kegiatan;
		      tgl_tetap = rowData.tgl_tetap;
          lcidx     = rowIndex;
          get(nomor,no_tetap,tgl,kode,lcket,lcrek,rek,lcnilai,sts,giat,tgl_tetap);   
        },
        onDblClickRow:function(rowIndex,rowData){
           lcstatus = 'edit';
           lcidx    = rowIndex;
           judul    = 'Edit Data Penerimaan'; 
           edit_data();   
        }
        });
        
        
        $('#tanggal').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
    
    });
    

    function get_skpd()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/penerimaan/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#skpd").attr("value",data.kd_skpd);
        								$("#nmskpd").attr("value",data.nm_skpd);
        								kode = data.kd_skpd;
                                        validate_rek();
                                        penetapan();
                                        
        							  }                                     
        	});
        }
        

     function validate_rek(){
	  	$(function(){
        $('#rek').combogrid({  
           panelWidth : 700,  
           idField    : 'kd_rek',  
           textField  : 'kd_rek',  
           mode       : 'remote',
           url        : '<?php echo base_url(); ?>index.php/penerimaan/ambil_rek_tetap/'+kode,             
           columns    : [[  
		        {field:'kd_rek6',title:'Kode Rek LRA',width:100},  
            {field:'kd_rek',title:'Kode Rek LO',width:100},
			      {field:'nm_rek',title:'Nama Rekening',width:200},
			      {field:'nm_rek5',title:'Nama Rekening Obyek',width:200},
            {field:'kd_sub_kegiatan',title:'Kegiatan',width:500}
              ]],
               onSelect:function(rowIndex,rowData){
               $("#nmrek").attr("value",rowData.nm_rek.toUpperCase());
               $("#rek1").attr("value",rowData.kd_rek6);
			         $("#rekcheck").attr("value",rowData.kd_rek);
               $("#giat").attr("value",rowData.kd_sub_kegiatan);
              }    
            });
	  	    });
		} 
        
    function get_tahun()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/penerimaan/config_tahun',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        			tahun_anggaran = data;
        			}                                     
        	});
             
        } 
     function penetapan(){
         var kode = kode;
         $('#notetap').combogrid({  
           panelWidth  : 420,  
           idField     : 'no_tetap',  
           textField   : 'no_tetap',  
           mode        : 'remote',
           url         : '<?php echo base_url(); ?>index.php/penerimaan/load_no_tetap',
           queryParams : ({cari:kode}),             
           columns:[[  
               {field:'no_tetap',title:'No Penetapan',width:140},  
               {field:'tgl_tetap',title:'Tanggal',width:140},
               {field:'kd_skpd',title:'SKPD',width:140}]],  
           onSelect:function(rowIndex,rowData){
            var ststagih='1';
            $("#tgltetap").attr("value",rowData.tgl_tetap);
            $("#rek").combogrid("setValue",rowData.kd_rek_lo);
            $("#rek1").attr("Value",rowData.kd_rek5);
            $("#nmrek").attr("Value",rowData.nm_rek5);
            $("#keterangan").attr("value",rowData.ket);
            $("#nilai").attr("value",number_format(rowData.nilai,2,'.',','));  
            $("#nil_tetap").attr("value",number_format(rowData.nilai,2,'.',','));  
		    }  
		});
     }       

     function section2(){
         $(document).ready(function(){    
             $('#section2').click();                                               
         });   
     }

     function section1(){
         $(document).ready(function(){    
             $('#section1').click();   
             $('#dg').edatagrid('reload');                                              
         });
     }
       
     function get(nomor,no_tetap,tgl,kode,lcket,lcrek,rek,lcnilai,sts,giat,tgl_tetap){
	    $("#notetap").combogrid("setValue",no_tetap);
        $("#nomor").attr("value",nomor);
        $("#nomor_hide").attr("value",nomor);
        $("#tanggal").datebox("setValue",tgl);
        $("#rek").combogrid("setValue",rek);
		$("#rekcheck").attr("value",rek);
        $("#rek1").attr("Value",lcrek);
		$("#giat").attr("Value",giat);
        $("#nilai").attr("value",lcnilai);
		$("#ket").attr("value",lcket);
        if (sts==1){            
            $("#status").attr("checked",true);
            $("#tagih").show();
			$("#nil_tetap").attr("value",lcnilai);
			$("#tgltetap").attr("value",tgl_tetap);
        } else {
            $("#status").attr("checked",false);
            $("#tagih").hide();
			$("#tgltetap").attr("value",'');
        }
    }
    
    
    function kosong(){
        $("#nomor").attr("value",'');
        $("#nomor_hide").attr("value",'');
        $("#tanggal").datebox("setValue",'');
		$("#nilai").attr("value",'');        
        $("#rek").combogrid("setValue",'');
        $("#rek1").attr("Value",'');
		$("#rekcheck").attr("Value",'');
        $("#nmrek").attr("value",'');
		$("#giat").attr("Value",'');
        $("#ket").attr("value",'');
        $("#notetap").combogrid("setValue",'');        
        $("#tgltetap").attr("value",'');
        $("#status").attr("checked",false);      
        $("#tagih").hide();
        document.getElementById("nomor").focus();         
        lcstatus = 'tambah';       
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/penerimaan/load_terima_tl',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
      
    
    function simpan_terima() {
        
        var cno      = document.getElementById('nomor').value;
        var cno_hide = document.getElementById('nomor_hide').value;
        
        var ctgl     = $('#tanggal').datebox('getValue');
        var cskpd    = document.getElementById('skpd').value;
        var cnmskpd  = document.getElementById('nmskpd').value ;
        var lckdrek  = $('#rek').combogrid('getValue');
        var rek      = document.getElementById('rek1').value;
		var rekcheck = document.getElementById('rekcheck').value ;
        var kegi      = document.getElementById('giat').value;
        var lcket    = document.getElementById('ket').value;
        var lntotal  = angka(document.getElementById('nilai').value);
            lctotal  = number_format(lntotal,0,'.',',');

		var tahun_input = ctgl.substring(0, 4);
		if (tahun_input != tahun_anggaran){
			alert('Tahun tidak sama dengan tahun Anggaran');
			exit();
		}
                
        if (cno==''){
            alert('Nomor  Tidak Boleh Kosong');
            exit();
        } 
        if (ctgl==''){
            alert('Tanggal Tidak Boleh Kosong');
            exit();
        }
        if (cskpd==''){
            alert('Kode SKPD Tidak Boleh Kosong');
            exit();
        }
        
		if (lckdrek != rekcheck){
            alert('Rekening Tidak Sesuai');
            exit();
        }		
            
        if ( lcstatus == 'tambah'){
		$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'tr_terima',field:'no_terima'}),
                    url: '<?php echo base_url(); ?>/index.php/penerimaan/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1){
						alert("Nomor Telah Dipakai!");
						document.getElementById("nomor").focus();
						exit();
						} 
						if(status_cek==0){
						alert("Nomor Bisa dipakai");
						//mulai
            lcinsert        = "( no_terima, tgl_terima, no_tetap,     tgl_tetap,       sts_tetap,     kd_skpd,  kd_sub_kegiatan,   kd_rek6,   kd_rek_lo,     nilai,         keterangan, jenis ) ";
            lcvalues        = "( '"+cno+"', '"+ctgl+"', '', '', '', '"+cskpd+"', '"+kegi+"',  '"+rek+"', '"+lckdrek+"', '"+lntotal+"', '"+lcket+"', '2' )";
            $(document).ready(function(){
                $.ajax({
                    type     : "POST",
                    url      : '<?php echo base_url(); ?>/index.php/penerimaan/simpan_terima_ag',
                    data     : ({tabel       :'tr_terima',  kolom       :lcinsert,        nilai       :lcvalues,        cid       :'no_terima',   lcid       :cno}),
                    dataType : "json",
                    success  : function(data) {
                        status = data;
                        if ( status == '0') {
                            alert('Gagal Simpan..!!');
                            exit();
                        }  else {
                                  
                                    alert('Data Tersimpan..!!');
                                    lcstatus = 'edit';
                                    $("#dialog-modal").dialog('close');
                                    $('#dg').edatagrid('reload');
                                    //exit();
                             }
                    }
                });
            }); 
            
            
           
       //akhir-mulai 
        }
		}
		});
		});
		
        
            
       } else {
		$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'tr_terima',field:'no_terima'}),
                    url: '<?php echo base_url(); ?>/index.php/penerimaan/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1 && cno!=cno_hide){
						alert("Nomor Telah Dipakai!");
						exit();
						} 
						if(status_cek==0 || cno==cno_hide){
						alert("Nomor Bisa dipakai");
			//mulai	
            
           lcinsert        = " ( no_terima, tgl_terima, no_tetap,     tgl_tetap,       sts_tetap,     kd_skpd,  kd_sub_kegiatan,   kd_rek6,   kd_rek_lo,     nilai,         keterangan, jenis  ) ";
            lcvalues        = " ( '"+cno+"', '"+ctgl+"', '', '', '', '"+cskpd+"', '"+kegi+"',  '"+rek+"', '"+lckdrek+"', '"+lntotal+"', '"+lcket+"', '2' ) ";
            
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/penerimaan/update_terima_ag',
                data     : ({tabel       :'tr_terima',  kolom       :lcinsert,        nilai       :lcvalues,        cid       :'no_terima',   lcid       :cno,no_hide:cno_hide}),
                dataType : "json",
                success  : function(data){
                           status=data ;
                        
                        if ( status=='2' ){
                                alert('Data Tersimpan...!!!');
                                lcstatus = 'edit';
                                $("#nomor_hide").attr("Value",cno) ;
                                $("#dialog-modal").dialog('close');
                                $('#dg').edatagrid('reload');
                               // exit();
                        }
                        
                        if ( status=='0' ){
                            alert('Gagal Simpan...!!!');
                            exit();
                        }
                    }
            });
            });
        //akhir
        }
			}
		});
		});
        }
       
    }
    
    
    
    
    function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Penerimaan';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("nomor").disabled=false;
    }    
        
    
    function tambah(){
        
		$("#notetap").combogrid("setValue",'');

		
		lcstatus = 'tambah';
        judul = 'Input Data Penerimaan Atas Piutang Tahun Lalu';
        $("#dialog-modal").dialog({ title: judul });
        //kosong();
		$("#dialog-modal").dialog('open');
		
		document.getElementById("nomor").disabled=false;
        document.getElementById("nomor").focus();
		kosong();
     } 


     function keluar(){
        $("#dialog-modal").dialog('close');
     }    
    
     function hapus(){
        
        var rows  = $("#dg").edatagrid("getSelected") ;
        var nobkt = rows.no_terima ;
                
        var tanya = confirm('Apakah Data Nomor Terima '+nobkt+' Akan Di Hapus ???') ;
        
        if ( tanya == true ) {
        
            var urll  = '<?php echo base_url(); ?>index.php/penerimaan/hapus_terima';
            $(document).ready(function(){
             $.post(urll,({no:nomor,skpd:kode}),function(data){
                status = data;
                if (status=='0'){
                    alert('Gagal Hapus..!!');
                    exit();
                } else {
                    $('#dg').datagrid('deleteRow',lcidx);   
                    alert('Data Berhasil Dihapus..!!');
                    $("#dg").edatagrid("unselectAll") ;
                    exit();
                }
             });
            });    
        }
    } 
    
    function runEffect() {
		$('#notetap').combogrid({  
           panelWidth:420,  
           idField:'no_tetap',  
           textField:'no_tetap',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/penerimaan/load_no_tetap',
           queryParams:({kd:kode}),             
           columns:[[  
               {field:'no_tetap',title:'No Penetapan',width:140},  
               {field:'tgl_tetap',title:'Tanggal',width:140},
               {field:'kd_skpd',title:'SKPD',width:140}
           ]]  
		});
        var selectedEffect = 'blind';            
        var options = {};                      
        $( "#tagih" ).toggle( selectedEffect, options, 500 );
        $("#notetap").combogrid("setValue",'');
        $("#tgltetap").attr("value",'');
        //$("#nilai").attr("value",'');
        $("#skpd").combogrid("setValue",'');
        $("#rek").combogrid("setValue",'');
        $("#nil_tetap").attr("value",'');
        
    };     
       
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
<div id="accordion">
<h3 align="center"><u><b><a href="#" id="section1">INPUTAN PENERIMAAN ATAS PIUTANG</a></b></u></h3>
    <div>
    <p align="right">         
        <button type="submit" class="easyui-linkbutton" plain="true" onclick="javascript:tambah();kosong()"><i class="fa fa-plus"></i> Tambah</button>               
        <button type="delete" class="easyui-linkbutton" plain="true" onclick="javascript:hapus();"><i class="fa fa-trash"></i> Hapus</button>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <input type="text" value="" id="txtcari"/><button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:cari();"><i class="fa fa-search"></i></button>
        <table id="dg" title="Listing data Penerimaan Atas Piutang Tahun Lalu" style="width:870px;height:450px;" >  
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
                <td>No. Terima</td>
                <td></td>
                <td><input type="text" id="nomor" style="width: 200px;"/><input type="hidden" id="nomor_hide" style="width: 200px;"/></td>  
            </tr>            
            <tr>
                <td>Tanggal </td>
                <td></td>
                <td><input type="text" id="tanggal" style="width: 140px;" /></td>
            </tr>
            <tr>
                <td>S K P D</td>
                <td></td>
                <td><input id="skpd" name="skpd" style="width: 140px;" readonly="true" disabled/>  <input type="text" id="nmskpd" style="border:0;width: 600px;" readonly="true"/></td>                            
            </tr>
            <tr>
                <td>Rekening</td>
                <td></td>
                <td><input id="rek" name="rek" style="width: 140px;" /> <input id="rek1" style="border:0;width: 140px;" readonly="true"/>
                 <input type="text" id="nmrek" style="border:0;width: 600px;" readonly="true"/>
				 <input type="hidden" id="rekcheck" style="border:0;width: 600px;" readonly="true"/></td>                
            </tr> 
            <tr>
                <td>Kegiatan</td>
                <td></td>
                <td><input type="text" id="giat" style="width: 140px;" readonly="true"/>
                 </td>                
            </tr>            
            <tr>
                <td>Nilai</td>
                <td></td>
                <td><input type="text" id="nilai" style="width: 200px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td> 
            </tr>
            <tr>
                <td>Keterangan</td>
                <td colspan="2"><textarea rows="2" cols="50" id="ket" style="width: 740px;"></textarea>
                </td> 
            </tr>
            <tr>
                <td colspan="3"><font color="red"><b>Perhatian!!</b> <br > Jika Kode rekening LO tidak tampil, silahkan lakukan mapping rekening akuntansi</font>
                </td> 
            </tr>
            <tr>
                <td colspan="3" align="center">
                  <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_terima();">Simpan</a>
		              <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>
</body>
</html>