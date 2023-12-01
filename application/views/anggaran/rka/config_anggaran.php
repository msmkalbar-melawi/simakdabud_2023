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
            height: 300,
            width: 700,
            modal: true,
            autoOpen:false,
        });
        });    
     
  
        $(function(){
   	     $('#tgl_con').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
				return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
            	//return y+'-'+m+'-'+d;
            }
        });
   	});
	
	
     
     $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/rka/load_konfig_anggaran',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        nowrap:"true",                       
        columns:[[
    	    {field:'no_konfig',
    		title:'No.',
    		width:5,
            align:"center",
            hidden:true},
            {field:'judul_ag',
    		title:'JUDUL',
    		width:30,
            align:"left"},
			{field:'nomor',
    		title:'NOMOR LAMPIRAN',
    		width:20,
            align:"center"},
			{field:'tanggal',
    		title:'TANGGAL LAMPIRAN',
    		width:20,
            align:"center"},
			{field:'lampiran',
    		title:'LAMPIRAN',
    		width:10,
            align:"center"},
            {field:'jns',title:'Status',width:15,align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          vno = rowData.no_konfig;
          vjenis_anggaran   = rowData.jenis_anggaran.trim();
          vjudul = rowData.judul_ag;
          vnomor = rowData.nomor;
          vtanggal = rowData.tanggal;
          vlampiran = rowData.lampiran;
          lcidx = rowIndex;
          get(vno,vjenis_anggaran,vjudul,vnomor,vtanggal,vlampiran);   
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data ANGGARAN'; 
           edit_data();   
        }
        
        });
        
         
              
       

      
    });        
 
	function get(vno,vjenis_anggaran,vjudul,vnomor,vtanggal,vlampiran){
        $("#no").attr("value",vno);
        $("#jns_anggaran").attr("value",vjenis_anggaran);
        $("#judul_lamp").attr("value",vjudul);
        $("#nomor_lamp").attr("value",vnomor);
        $("#tanggal_lamp").attr("value",vtanggal);
        $("#jns_lamp").attr("value",vlampiran);     
                          
    }
    
    function kosong(){
		cdate = '<?php echo date("Y-m-d"); ?>';
        $("#no").attr("value",'');
         $("#jns_anggaran").attr("value",'');
        $("#judul_lamp").attr("value",'');
        $("#nomor_lamp").attr("value",'');
        $("#tanggal_lamp").attr("value",'');
        $("#jns_lamp").attr("value",''); 
    }
    
  
       function hapus(){
        //alert(lcstatus);
        var cno = document.getElementById('no').value;
		 $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/hapus_master',
                    data: ({tabel:'trkonfig_anggaran',cid:'no_konfig',cnid:cno}),
                    dataType:"json",
                    success:function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Hapus..!!');
                            exit();
                        }else if(status=='1'){
                            alert('Data Berhasil Dihapus..!!');
							$('#dg').edatagrid('reload');
							$("#dialog-modal").dialog('close');
                            exit();
                        }else{
                            alert('Gagal Hapus..!!');
                            exit();
                        }
                    }
                });
            });  
	   }
    
    
	
       function simpan(){
        //alert(lcstatus);
        var cno = document.getElementById('no').value;
        var cjns_anggaran = document.getElementById('jns_anggaran').value;
        var cjudul_lamp = document.getElementById('judul_lamp').value;
        var cnomor_lamp = document.getElementById('nomor_lamp').value;
        var ctanggal_lamp = document.getElementById('tanggal_lamp').value;
        var cjns_lamp = document.getElementById('jns_lamp').value;		
        
		if (cjns_anggaran=='0'){
            alert('JENIS ANGGARAN Tidak Boleh Kosong');
            exit();
        } 
        if (cjns_lamp=='0'){
            alert('JENIS LAMPIRAN Tidak Boleh Kosong');
            exit();
        }
        if (cjudul_lamp==''){
            alert(' JUDUL LAMPIRAN Tidak Boleh Kosong');
            exit();
        }
        
        if(lcstatus=='tambah'){ 
            
            lcinsert = "(jenis_anggaran, judul, nomor, tanggal, lampiran)";
            lcvalues = "('"+cjns_anggaran+"','"+cjudul_lamp+"','"+cnomor_lamp+"','"+ctanggal_lamp+"','"+cjns_lamp+"')";

            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_master',
                    data: ({tabel:'trkonfig_anggaran',kolom:lcinsert,nilai:lcvalues,cid:'no_konfig',lcid:cno}),
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
            
            lcquery = "UPDATE trkonfig_anggaran SET jenis_anggaran='"+cjns_anggaran+"',judul='"+cjudul_lamp+"',nomor='"+cnomor_lamp+"',tanggal='"+ctanggal_lamp
			+"',lampiran='"+cjns_lamp+"' where no_konfig='"+cno+"'";
            //alert(lcquery);
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
       // alert("Data Berhasil disimpan");
	   
        $("#dialog-modal").dialog('close');
        
        //section1();
		} 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("no").disabled=true;
        
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("no").disabled=false;
        document.getElementById("no").focus();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
     }    

    
       

  // Created by Tox
    
  
   </script>

</head>
<body>

<div id="content"> 
<div id="accordion">
<h3 align="center"><u><b><a href="#" id="section1">KONFIGURASI ANGGARAN</a></b></u></h3>
    <div>
    <p align="right">         
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a>               
        <!--<a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>-->
        <table id="dg" title="Listing KONFIGURASI ANGGARAN" style="width:870px;height:450px;" >  
        </table>
 
    </p> 
    </div>   

</div>

</div>


<div id="dialog-modal" title="">
    <fieldset>
     <table align="center" style="width:100%;" border="0">
	        <tr>
                <td ></td>
                <td></td>
                <td>&nbsp;<input type="text" id="no" style="width: 200px;" readonly="true" hidden="true" /></td>  
            </tr>            
			<tr>
                <td>JENIS ANGGARAN</td>
                <td></td>
                <td>
				<select name="jns_anggaran" id="jns_anggaran" onchange="" style="height: 27px; width:190px;">
     <option value="0">...Pilih Beban... </option>     
     <option value="M">PENYUSUNAN</option>
     <option value="P1">PENYEMPURNAAN 1</option>
     <option value="P2">PENYEMPURNAAN 2</option>
     <option value="P3">PENYEMPURNAAN 3</option>
     <option value="P4">PENYEMPURNAAN 4</option>
     <option value="P5">PENYEMPURNAAN 5</option>
     <option value="U">PERUBAHAN</option>
	 </select>
     
     
	 </td>  
            </tr><tr>
                <td>JUDUL LAMPIRAN</td>
                <td></td>
                <td>&nbsp;<input type="text" id="judul_lamp" style="width: 500px;"/></td>  
            </tr> 
			<tr>
                <td>NOMOR</td>
                <td>:</td>
                <td>&nbsp;<input type="text" id="nomor_lamp" style="width: 500px;"/></td>  
            </tr> 
			<tr>
                <td>TANGGAL</td>
                <td></td>
                <td>&nbsp;<input type="text" id="tanggal_lamp" style="width: 500px;"/></td>  
            </tr> 
			<tr>
                <td>JENIS LAMPIRAN</td>
                <td></td>
                <td>				<select name="jns_lamp" id="jns_lamp" onchange="javascript:validate_jenis();" style="height: 27px; width:190px;">
     <option value="0">...Pilih Beban... </option>     
     <option value="perda">PERDA</option>
     <option value="perkada">PERKADA</option>
	 </select>
			</td>  
            </tr>
			
            <tr>
                <td colspan="3" align="center">
				<a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();section1();">Hapus</a>
				<a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();$('#dg').edatagrid('reload');">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>


  	
</body>

</html>