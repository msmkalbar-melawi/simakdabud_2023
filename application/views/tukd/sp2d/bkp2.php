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
          lctotal = rowData.nilai;
          lcidx = rowIndex;
          get(jenis,tanggal,lctotal);   
                                       
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
		
		$('#nilai').keypress(function(e) {
		if(e.which == 13) {
			simpan();
		}
		});
 
    });        

       
    function get(jenis,tanggal,lctotal){
        $("#jns_beban").attr("value",jenis);
        $("#tgl_ttd1").datebox("setValue",tanggal);
        $("#nilai").attr("value",lctotal);
        
                
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
        
		if(jenis==''){
			alert('Pilih Jenis dulu')
			exit()
		}
		if(ctglttd==''){
			alert('Pilih Tanggal dulu')
			exit()
		}
		
		
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
                        }else{
                            alert('Data Tersimpan..!!');
                            $("#nilai").attr("value",'');
							$("#jns_beban").focus();
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
                            keluar();
							$('#dg').edatagrid('reload');
                        }
                    }
            });
            });
            
            
        }   
        
   
    } 
    
	
	
    function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Buku Kas Penerimaan';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
		$('#del').linkbutton('enable');
        //document.getElementById("nomor").disabled=true;
    }    
        
    
    function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Buku Kas Penerimaan';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
		$('#del').linkbutton('disable');
        //document.getElementById("nomor").disabled=false;
        //document.getElementById("nomor").focus();
		
    }
		
    function keluar(){
        $("#dialog-modal").dialog('close');
    }   

	function cetak(ctk)
        {
			
			
		    var  ttd = $('#tgl_ttd').combogrid('getValue');
		    ttd = ttd.split(" ").join("123456789");
			var url    = "<?php echo site_url(); ?>/tukd/buku_kas_penerimaan_pengeluaran";  
			
			if(ttd==''){
			alert('Pilih Tanggal dulu')
			exit()
			}
			
			window.open(url+'/'+ctk+'/-/'+ttd, '_blank');
			window.focus();
			
        }	
    
    function hapus(){				
            var jenis = document.getElementById('jns_beban').value;
			var ctglttd = $('#tgl_ttd1').datebox('getValue'); 
			//var lntotal = angka(document.getElementById('nilai').value);
            var urll= '<?php echo base_url(); ?>/index.php/tukd/hapus_bkp2';             			    
         	if (jenis !=''){
				var del=confirm('Anda yakin akan menghapus Jenis '+jenis+'  ?');
				if  (del==true){
					$(document).ready(function(){
                    $.post(urll,({jns:jenis,tanggal:ctglttd}),function(data){
                    status = data;
					if (status=='0'){
                            alert('Gagal Simpan..!!');
                            exit();
                        }else{
                            alert('Data Terhapus..!!');
                           keluar();
							$('#dg').edatagrid('reload');
                        }
                    });
                    });
				}
				} 
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
<h3 align="center"><u><b><a href="#" id="section1">BUKU KAS PENERIMAAN</a></b></u></h3>
    <div>
    <p align="right">         
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a>            
        <table id="dg" title="Listing BUKU KAS PENERIMAAN" style="width:870px;height:280px;" >  
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
                <td>JENIS</td>
                <td></td>
                <td>
					<select name="jns_beban" id="jns_beban" style="width:350px;">
					 <option value="1">PENERIMAAN PAJAK DAERAH</option>
					 <option value="2">PENERIMAAN RETRIBUSI DAERAH</option>
					 <option value="3">LAIN-LAIN PENDAPATAN ASLI DAERAH YANG SAH</option>
					 <option value="4">PENERIMAAN LAINNYA</option>
					 <option value="5">DANA ALOKASI UMUM</option>
					 <option value="6">DANA BAGI HASIL</option>
					 <option value="7">CONTRA POS</option>
					 <option value="8">DANA PENYESUAIAN DAN OTONOMI KHUSUS</option>
					 <option value="9">HASIL PENGELOLAAN KEKAYAAN DAERAH YANG DIPISAHKAN</option>
					 <option value="10">DANA ALOKASI KHUSUS</option>
					</select>
				</td>  
            </tr>            
            
			<tr>  
				<td>Tanggal</td>
				<td></td>
				<td><input type="text" id="tgl_ttd1" style="width: 100px;" /></td>                       
            </tr> 
			
            <tr>
                <td>Nilai</td>
                <td></td>
                <td><input type="text" id="nilai" style="width: 140px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td> 
            </tr>
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Simpan</a>[Enter]
				<a id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>
</body>

</html>