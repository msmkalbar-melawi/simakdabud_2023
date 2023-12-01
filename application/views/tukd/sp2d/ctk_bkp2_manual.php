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
            height: 360,
            width: 900,
            modal: true,
            autoOpen:false,
        });
        });    
     
     $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_bkp2',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        nowrap:"true",                       
        columns:[[
    	    {field:'jenis',
    		title:'JENIS',
    		width:10,
            align:"center"},
            {field:'tanggal',
    		title:'TANGGAL',
    		width:100,
            align:"left"},
            {field:'nilai',
    		title:'Nilai',
    		width:20,
            align:"right"}
        ]],
        onSelect:function(rowIndex,rowData){
          jenis = rowData.jenis;
          tanggal = rowData.tanggal;
          lcnilai = rowData.nilai;
          lcidx = rowIndex;
          get(jenis,tanggal,lcnilai);   
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data Buku Kas Penerimaan'; 
           edit_data();   
        }
        
        });
		

		 $('#tgl_ttd').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
		
		 $('#tgl_ttd1').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
 
    });        

	$(function(){  
		$('#ttd').combogrid({  
			panelWidth:600,  
			idField:'nip',  
			textField:'nip',  
			mode:'remote',
			url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/BUD',  
			columns:[[  
				{field:'nip',title:'NIP',width:200},
				{field:'nama',title:'Nama',width:400}
			]],  
	   onSelect:function(rowIndex,rowData){
		   $("#nama").attr("value",rowData.nama);
	   } 
		});          
	 });
	 
    function get(jenis,tanggal,lcnilai){
        $("#jns_beban").attr("value",jenis);
        $("#tgl_ttd1").attr("value",tanggal);
        $("#nilai").attr("value",lcnilai);
        
                
    }
    
    function kosong(){
        $("#nomor").attr("value",'');
        $("#uraian").attr("value",'');
        $("#nilai").attr("value",'');                

    }
    
    function simpan(){
        var jenis = document.getElementById('jns_beban').value;
		var ctglttd = $('#tgl_ttd1').datebox('getValue'); 
        var lntotal = angka(document.getElementById('nilai').value);
            lctotal = number_format(lntotal,0,'.',',');
        
        if(lcstatus=='tambah'){ 
            
            lcinsert = "(jenis,tanggal,nilai)";
            lcvalues = "('"+jenis+"','"+ctglttd+"','"+lntotal+"')";

            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/tukd/simpan_bkp2',
                    data: ({tabel:'buku_kas_penerimaan_pengeluaran',kolom:lcinsert,nilai:lcvalues,cid:'jenis',lcid:jenis}),
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
            
            lcquery = "UPDATE buku_kas_penerimaan_pengeluaran SET nilai='"+lntotal+"' where jenis='"+jenis+"' AND tanggal='"+ctglttd+"'";
            
            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/tukd/update_bkp2',
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
        judul = 'Edit Data Buku Kas Penerimaan';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        //document.getElementById("nomor").disabled=true;
    }    
        
    
    function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Buku Kas Penerimaan';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("nomor").disabled=false;
        document.getElementById("nomor").focus();
    }
		
    function keluar(){
        $("#dialog-modal").dialog('close');
    }   

	function cetak(ctk)
        {
			var nomor      = document.getElementById('nomor').value;
			var no_halaman = document.getElementById('no_halaman').value;
			var  ttd2 = $('#ttd').combogrid('getValue');
		    ttd2 = ttd2.split(" ").join("123456789");
		    var  ttd = $('#tgl_ttd').combogrid('getValue');
		    ttd = ttd.split(" ").join("123456789");
			var url    = "<?php echo site_url(); ?>/tukd/buku_kas_penerimaan_pengeluaran_manual";  
			
			if(ttd==''){
			alert('Pilih Tanggal dulu')
			exit()
			}
			
			window.open(url+'/'+ctk+'/'+ttd2+'/'+ttd+'/'+nomor+'/'+no_halaman, '_blank');
			window.focus();
			
        }	
    
    function hapus(){
     
        var urll = '<?php echo base_url(); ?>index.php/tukd/hapus_bkp2';
        $(document).ready(function(){
         $.post(urll,({no:nomor,uraian:kode}),function(data){
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
    
       
    function addCommas(nStr){
		
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
    
    function delCommas(nStr){
		
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
<h3 align="center"></h3>
    <div>
    <p align="right">         
		<tr >
			<td colspan="4">
                <div id="div_tgl">
                        <table style="width:100%;" border="0">
                            <td width="20%">Tanggal</td>
                            <td><input type="text" id="tgl_ttd" style="width: 100px;" /></td>
                        </table>
						 <table style="width:100%;" border="0">
							<td width="20%">Kuasa BUD</td>
                            <td><input type="text" id="ttd" style="width: 200px;" /> &nbsp;&nbsp;
							<input type="nama" id="nama" readonly="true" style="width: 200px;border:0" /> 
                            </td> 
                        </table>
						<table style="width:100%;" border="0">
								<td width="20%">No. Halaman</td>
								<td><input type="number" id="no_halaman" style="width: 100px;" value="1"/></td>                       
                        </table>
						<table style="width:100%;" border="0">
								<td width="20%">Nomor Urut</td>
								<td><input type="number" id="nomor" style="width: 100px;" value="1"/></td>                       
                        </table>
                </div>
			</td>
	</tr>		
			
	<tr >
			<td colspan="4" align="center">
			<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak(0);">Cetak</a>
			<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak(1);">Cetak Pdf</a>
			</td>
	</tr>
    </p> 
    </div>   

</div>

</div>


</html>