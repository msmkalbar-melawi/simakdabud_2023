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
		url: '<?php echo base_url(); ?>/index.php/tukd/load_penerimaan_lain_ppkd2',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'no_sts',
    		title:'NOMOR KAS',
    		width:10,
            align:"center"},
            {field:'tgl_kas',
    		title:'TANGGAL',
    		width:20,
            align:"left"},
            {field:'total',
    		title:'Nilai',
    		width:20,
            align:"right"},
			{field:'keterangan',
    		title:'KETERANGAN',
    		width:40,
            align:"center"}
        ]],
        onSelect:function(rowIndex,rowData){
          no_kas = rowData.no_sts;
		  tgl_kas = rowData.tgl_kas;
		  uraian = rowData.keterangan;
          lctotal = rowData.total;
          lckdrek5 = rowData.kd_rek6;
          lcnmrek5 = rowData.nm_rek6;
          sumber = rowData.sumber;
          lcidx = rowIndex;
          get(no_kas,tgl_kas,uraian,lctotal,lckdrek5,lcnmrek5,sumber); 
		 
                                       
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
		
		
		$('#jenis').combogrid({
           panelWidth:700,  
           idField:'kd_rek6',  
           textField:'kd_rek6',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/load_rekening_ppkd2',             
           columns:[[  
               {field:'kd_rek6',title:'Kode Rekening',width:140},  
               {field:'nm_rek6',title:'Nama Rekening',width:700}
           ]],  
           onSelect:function(rowIndex,rowData){
               kd_rek6 = rowData.kd_rek6;
               $("#nmjenis").attr("value",rowData.nm_rek6);                                      
           }
              
        });
		
		$('#pengirim').combogrid({
           panelWidth:700,  
           idField:'kd_pengirim',  
           textField:'kd_pengirim',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/load_pengirim_ppkd',             
           columns:[[  
               {field:'kd_pengirim',title:'Kode Pengirim',width:140},  
               {field:'nm_pengirim',title:'Nama Pengirim',width:700}
           ]],  
           onSelect:function(rowIndex,rowData){
               kd_pengirim = rowData.kd_pengirim;
               $("#nmpengirim").attr("value",rowData.nm_pengirim);                                      
           }
              
        });
		
		$('#nilai').keypress(function(e) {
		if(e.which == 13) {
			simpan();
		}
		});
 
    });        

       
    function get(no_kas,tgl_kas,uraian,lctotal,lckdrek5,lcnmrek5,sumber){
        $("#no_kas").attr("value",no_kas);
		$("#tgl_ttd1").datebox("setValue",tgl_kas);
		$("#uraian").attr("value",uraian);
        $("#nilai").attr("value",lctotal); 
        $("#nmjenis").attr("value",lcnmrek5); 
        $("#jenis").combogrid("setValue",lckdrek5);
        $("#pengirim").combogrid("setValue",sumber);
    }
    
	function get_nourut(){
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd/no_urut_ppkd3',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#no_kas").attr("value",data.no_urut);
        							  }                                     
        	});  
    }
	
    function kosong(){
		$("#jenis").combogrid("setValue",'');
		$("#pengirim").combogrid("setValue",'');
        //$("#no_kas").attr("value",'');
		$("#uraian").attr("value",'');
        $("#nilai").attr("value",'');
		$("#nmjenis").attr("value",'');
		$("#nmpengirim").attr("value",'');

    }
    
    function simpan(){
		var no_kas  = document.getElementById('no_kas').value;
        var jenis   = $('#jenis').combogrid('getValue');
        var pengirim   = $('#pengirim').combogrid('getValue');
		var uraian  = document.getElementById('uraian').value;
		var ctglttd = $('#tgl_ttd1').datebox('getValue');
        var lntotal = angka(document.getElementById('nilai').value);
            lctotal = number_format(lntotal,2,'.',',');
        
		if(no_kas==''){
			alert('Pilih no kas dulu')
			exit()
		}
		if(ctglttd==''){
			alert('Pilih Tanggal dulu')
			exit()
		}
		
		
        if(lcstatus=='tambah'){ 
		$(document).ready(function(){
               
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:no_kas,tabel:'trhkasin_pkd',field:'no_kas'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan2',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1){
						alert("Nomor Telah Dipakai!");
						document.getElementById("nomor").focus();
						exit();
						} 
						if(status_cek==0){
						alert("Nomor Bisa dipakai");
		
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/tukd/simpan_kas_lainnya_ppkd',
                    data: ({tabel:'trhkasin_pkd',nokas:no_kas,jns:jenis,pengirim:pengirim,ket:uraian,ttd:ctglttd,nilai:lntotal}),
                    dataType:"json",
                    success:function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Simpan..!!');
                            exit();
                        }else{
                            alert('Data Tersimpan..!!');
                            $("#nilai").attr("value",'');
							$("#no_kas").attr("value",'');
							$("#uraian").attr("value",'');
							$("#nmjenis").attr("value",'');
							$("#jenis").combogrid("setValue",'');
							$("#pengirim").combogrid("setValue",'');
							$("#nmpengirim").attr("value",'');
							get_nourut();
                        }
                    }
                });
            //akhir
			});    
		   }
		   }
		  });
		  });
          } else{
          
            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/tukd/update_kas_lainnya_ppkd',
                data: ({nokas:no_kas,jns:jenis,pengirim:pengirim,ket:uraian,ttd:ctglttd,nilai:lntotal}),
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
        document.getElementById("no_kas").disabled=true;
    }    
        
    
    function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Kas Penerimaan Lainnya';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
		$('#del').linkbutton('disable');
        document.getElementById("no_kas").disabled=false;
        get_nourut();
		
    }
		
    function keluar(){
        $("#dialog-modal").dialog('close');
    }   

	function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_penerimaan_lain_ppkd2',
        queryParams:({cari:kriteria})
        });        
     });
    }
	
    function hapus(){				
            var no_kas = document.getElementById('no_kas').value;
			var ctglttd = $('#tgl_ttd1').datebox('getValue'); 
			//var lntotal = angka(document.getElementById('nilai').value);
            var urll= '<?php echo base_url(); ?>/index.php/tukd/hapus_penerimaan_lainnya_ppkd';             			    
         	if (no_kas !=''){
				var del=confirm('Anda yakin akan menghapus no_kas '+no_kas+'  ?');
				if  (del==true){
					$(document).ready(function(){
                    $.post(urll,({no_kas:no_kas,tgl_kas:ctglttd}),function(data){
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
<h3 align="center"><u><b><a href="#" id="section1">PENERIMAAN LAINNYA</a></b></u></h3>
    <div>
    <p align="right">         
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a>
		<a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
		<input type="text" value="" id="txtcari"/>
        <table id="dg" title="Listing PENERIMAAN LAINNYA" style="width:870px;height:480px;" >  
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
                <td>No Kas</td>
                <td></td>
                <td><input type="text" id="no_kas" style="width: 60px;"/></td> 
            </tr>           
            <tr>
				<td>Jenis</td>
				<td></td>
				<td><input id="jenis" name="jenis" style="width: 100px;" /></td>
			</tr>
			<tr>
				<td>Nama Jenis</td>
				<td></td>
				<td><input type="text" id="nmjenis" readonly="true" style="border:0;width: 600px;"/></td>
			</tr>
			<tr>
				<td>Pengirim</td>
				<td></td>
				<td><input id="pengirim" name="pengirim" style="width: 100px;" /></td>
			</tr>
			<tr>
				<td>Nama Pengirim</td>
				<td></td>
				<td><input type="text" id="nmpengirim" readonly="true" style="border:0;width: 600px;"/></td>
			</tr>
			<tr>  
				<td>Tanggal</td>
				<td></td>
				<td><input type="text" id="tgl_ttd1" style="width: 100px;" /></td>                       
            </tr> 
			
			<tr>  
				<td>Uraian</td>
				<td></td>
				<td><textarea name="uraian" id="uraian" cols="60" rows="1" ></textarea></td>                       
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