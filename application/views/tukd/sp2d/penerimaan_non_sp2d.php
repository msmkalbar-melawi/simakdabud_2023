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
			get_nourut();
		
        });    
     
     $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_penerimaan_nonsp2d',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        nowrap:"true",                       
        columns:[[
    	    {field:'nomor',
    		title:'NOMOR',
    		width:20,
            align:"center"},
            {field:'tanggal',
    		title:'TANGGAL',
    		width:30,
            align:"center"},
			{field:'keterangan',
    		title:'URAIAN',
    		width:100,
            align:"center"},
            {field:'nilai',
    		title:'Nilai',
    		width:25,
            align:"center"}
        ]],
        onSelect:function(rowIndex,rowData){
          nomor = rowData.nomor;
          tanggal = rowData.tanggal;
		  keterangan = rowData.keterangan;
          lcnilai = rowData.nilai;
          lcjns = rowData.jns;
          lcidx = rowIndex;
          get(nomor,tanggal,keterangan,lcnilai,lcjns);   
                                       
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

       
    function get(nomor,tanggal,keterangan,lcnilai,lcjns){
        $("#nomor").attr("value",nomor);
        $("#tgl_ttd1").datebox("setValue",tanggal);
		$("#keterangan").attr("value",keterangan);
        $("#nilai").attr("value",lcnilai);
        $("#jns").attr("value",lcjns);          
    }
    
	function seting_tombol(){
			$('#tambah').linkbutton('disable');
			$('#simpan').linkbutton('disable');
			$('#hapus').linkbutton('disable');
	}
	
    function kosong(){
        		
        $("#keterangan").attr("value",'');
        $("#nilai").attr("value",'');                
        $("#jns").attr("value",'');                

    }
    
	function get_nourut()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd/no_urut_ppkd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        		$("#nomor").attr("value",data.no_urut);
        							  }                                     
        	});  
        }
	
    function simpan(){
        var nomor = document.getElementById('nomor').value;
		var ctglttd = $('#tgl_ttd1').datebox('getValue');
		var keterangan = document.getElementById('keterangan').value;
        var lntotal = angka(document.getElementById('nilai').value);
        var jns   = document.getElementById('jns').value;     
        
		if(ctglttd==''){
			alert('Pilih Tanggal dulu')
			exit()
		}
		
		
        if(lcstatus=='tambah'){ 
            
            lcinsert = "(nomor,tanggal,keterangan,nilai,jenis)";
            lcvalues = "('"+nomor+"','"+ctglttd+"','"+keterangan+"','"+lntotal+"','"+jns+"')";

            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/tukd/simpan_bkp2',
                    data: ({tabel:'penerimaan_non_sp2d',kolom:lcinsert,nilai:lcvalues,cid:'nomor',lcid:nomor}),
                    dataType:"json",
                    success:function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Simpan..!!');
                            exit();
                        }else{
                            alert('Data Tersimpan..!!');
                            $("#nilai").attr("value",'');
							$("#nomor").focus();
                        }
                    }
                });
            });    
         
          } else{
            
            lcquery = "UPDATE penerimaan_non_sp2d SET   tanggal='"+ctglttd+"', keterangan='"+keterangan+"', nilai='"+lntotal+"', jenis='"+jns+"' where nomor='"+nomor+"'";
            
            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/tukd/update_penerimaan_nonsp2d',
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
		$('#del').linkbutton('enable');
        document.getElementById("nomor").disabled=true;
    }    
        
    
    function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Buku Kas Penerimaan';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
		$('#del').linkbutton('disable');
		document.getElementById("hidethis").style.display = 'none';
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
            var nomor = document.getElementById('nomor').value;
			var ctglttd = $('#tgl_ttd1').datebox('getValue');
			//var keterangan = document.getElementById('keterangan').value;
			//var lntotal = angka(document.getElementById('nilai').value);
            var urll= '<?php echo base_url(); ?>/index.php/tukd/hapus_penerimaan_non_sp2d';             			    
         	if (nomor !=''){
				var del=confirm('Anda yakin akan menghapus Nomor '+nomor+'  ?');
				if  (del==true){
					$(document).ready(function(){
                    $.post(urll,({no:nomor,tanggal:ctglttd}),function(data){
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
<h3 align="center"><u><b><a href="#" id="section1">BUKU KAS PEMBANTU PENERIMAAN NON</a></b></u></h3>
    <div>
    <p align="right">         
        <a id="tambah" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a>            
        <table id="dg" title="Listing BUKU KAS PEMBANTU PENERIMAAN NON" style="width:870px;height:280px;" >  
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
				<td>Nomor</td>
				<td></td>
				<td><input type="text" id="nomor" style="width: 100px;" disabled /></td>                       
            </tr>
            <tr width="100px;">  
				<td>Jenis</td>
				<td></td>
				<td><select name="jns" id="jns">    
						 <option value="1"> Deposito </option> 
						 <option value="2"> Penerimaan Non SP2D </option> 
			</td>                       
            </tr> 
			<tr>  
				<td>Tanggal</td>
				<td></td>
				<td><input type="text" id="tgl_ttd1" style="width: 100px;" /></td>                       
            </tr> 
			
			<tr>  
				<td>Uraian</td>
				<td></td>
				<td><textarea name="keterangan" id="keterangan" cols="30" rows="1" ></textarea></td>                       
            </tr>
			
            <tr>
                <td>Nilai</td>
                <td></td>
                <td><input type="text" id="nilai" style="width: 140px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td> 
            </tr>
            <tr>
                <td colspan="3" align="center"><a id="simpan" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Simpan</a>[Enter]
				<a id="hapus" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>
</body>

</html>