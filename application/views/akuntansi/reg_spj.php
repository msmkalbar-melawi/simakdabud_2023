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
            height: 400,
            width: 600,
            modal: true,
            autoOpen:false
        });
        });    
     
     $(function(){ 
        $('#kode').combogrid({  
           panelWidth:700,  
           idField:'kd_skpd',  
           textField:'kd_skpd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/rka/skpd',  
           columns:[[  
               {field:'kd_skpd',title:'Kode SKPD',width:100},  
               {field:'nm_skpd',title:'Nama SKPD',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               kd = rowData.kd_skpd;               
               $("#nmskpd").attr("value",rowData.nm_skpd.toUpperCase());                
           }  
       });
	   
        $('#tgl_terima').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            },
            onSelect: function(date){
		      jaka = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
	       }
        });
		
		$('#tgldpasempurna').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            },
            onSelect: function(date){
		      jaka = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
	       }
        });
		
         $('#tgldppa').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            },
            onSelect: function(date){
		      jaka = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
	       }
        });
		
		
     $('#dg').edatagrid({
//url: '<?php echo base_url(); ?>/index.php/akuntansi/load_reg_spj',
       idField:'id',            
		rownumbers:"true", 
		fitColumns:"true",
		singleSelect:"true",
		autoRowHeight:"false",
		loadMsg:"Tunggu Sebentar....!!",
		pagination:"true",
		nowrap:"true",                  
        columns:[[
            {field:'kd_skpd',
    		title:'Kode SKPD',
    		width:5,
            align:"center"},
    	    {field:'real_up',
    		title:'UP/GU/TU',
    		width:5,
            align:"center"},
			{field:'real_gj',
    		title:'Gaji',
    		width:5,
            align:"center"},
            {field:'real_brg',
    		title:'LS- BrJs',
    		width:5,
			align:"center"},
			{field:'tgl_terima',
    		title:'Tgl Terima',
    		width:7,
			align:"center"},
		/*	{field:'spj',
    		title:'SPJ',
    		width:5,
			align:"center"},
			{field:'bku',
    		title:'BKU',
    		width:10,
			align:"center"},
			{field:'koran',
    		title:'Rek. Koran',
    		width:5,
			align:"center"},
			{field:'pajak',
    		title:'BP Pajak',
    		width:10,
			align:"center"},
			{field:'sts',
    		title:'STS',
    		width:5,
			align:"center"},
		*/	{field:'ket',
    		title:'Ket',
    		width:10,
			align:"left"},
			{field:'cek',
    		title:'Cek',
    		width:3,
			align:"center"}
        ]],
		
        onSelect:function(rowIndex,rowData){
          ckd_skpd  = rowData.kd_skpd;
          creal_up  = rowData.real_up;
          creal_gj  = rowData.real_gj;
          creal_brg = rowData.real_brg;
		 
		  ctgl_terima = rowData.tgl_terima;
		  cspj = rowData.spj;
          cbku = rowData.bku;
          ckoran = rowData.koran;
          cpajak = rowData.pajak;
		  csts = rowData.sts;
		  cket = rowData.ket;
		  ccek = rowData.cek;
          get(ckd_skpd,creal_up,creal_gj,creal_brg,ctgl_terima,cspj,cbku,ckoran,cpajak,csts,cket,ccek); 
          lcidx = rowIndex;                           
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Register SPJ'; 
           edit_data();   
        }
        });		
		
    });        

	function validate1(){
        var bln1 = document.getElementById('bulan1').value;
			$('#dg').edatagrid({url:'<?php echo base_url(); ?>/index.php/akuntansi/load_reg_spj/'+bln1
                                   });
		
	}
	
	
    function get(ckd_skpd,creal_up,creal_gj,creal_brg,ctgl_terima,cspj,cbku,ckoran,cpajak,csts,cket,ccek){
	
        $("#kode").combogrid("setValue",ckd_skpd);
        $("#tgl_terima").datebox("setValue",ctgl_terima);
        $("#real_gj").attr("value",creal_gj);
        $("#real_up").attr("value",creal_up);
        $("#real_brg").attr("value",creal_brg);
		

        if (cspj==1){            
            $("#spj").attr("checked",true);
        } else {
            $("#spj").attr("checked",false);
        }
		
		if (cbku==1){            
            $("#bku").attr("checked",true);
        } else {
            $("#bku").attr("checked",false);
        }
		
        if (ckoran==1){            
            $("#koran").attr("checked",true);
        } else {
            $("#koran").attr("checked",false);
        }
		
		if (cpajak==1){            
            $("#pajak").attr("checked",true);
        } else {
            $("#pajak").attr("checked",false);
        }
		
		if (csts==1){            
            $("#sts").attr("checked",true);
        } else {
            $("#sts").attr("checked",false);
        }
		
        $("#ket").attr("value",cket);

		if (ccek==1){            
            $("#cek").attr("checked",true);
        } else {
            $("#cek").attr("checked",false);
        }
	}
  
    function kosong(){
        $("#kode").combogrid("setValue",'');
	    $("#nmskpd").attr("value",'')
		$("#stsdpa").attr("checked",false);
		$("#stsdpasempurna").attr("checked",false);
		$("#stsdppa").attr("checked",false);		
        $("#dpa").attr("value",'');
        $("#tgldpa").datebox("setValue",'');
		$("#dpasempurna").attr("value",'');
        $("#tgldpasempurna").datebox("setValue",'');
        $("#dppa").attr("value",'');
        $("#tgldppa").datebox("setValue",'');
    }
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
	var bln1 = document.getElementById('bulan1').value;
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/akuntansi/load_reg_spj/'+bln1,
        queryParams:({cari:kriteria})
        });        
     });
    }
	
       function simpan_pengesahan(){
		var cbulan = document.getElementById('bulan1').value;
        var ckd = $('#kode').combogrid('getValue');
		var ctgl_terima = $('#tgl_terima').datebox('getValue');
		var real_gj = angka(document.getElementById('real_gj').value);
        var real_up = angka(document.getElementById('real_up').value);
        var real_brg = angka(document.getElementById('real_brg').value);
		
		var cspj = document.getElementById('spj').checked;
        if (cspj==false){
           cspj=0;
        }else{
            cspj=1;
        }

		var cbku = document.getElementById('bku').checked;
        if (cbku==false){
           cbku=0;
        }else{
            cbku=1;
        }

		var ckoran = document.getElementById('koran').checked;
        if (ckoran==false){
           ckoran=0;
        }else{
            ckoran=1;
        }
		
		var cpajak = document.getElementById('pajak').checked;
        if (cpajak==false){
           cpajak=0;
        }else{
            cpajak=1;
        }
		
		var csts = document.getElementById('sts').checked;
        if (csts==false){
           csts=0;
        }else{
            csts=1;
        }
		var ccek = document.getElementById('cek').checked;
        if (ccek==false){
           ccek=0;
        }else{
            ccek=1;
        }
		 tot=cspj+cbku+ckoran+cpajak+csts;
		 
		 if((tot!=5) && (ccek==1)){
			alert('Persyaratan belum lengkap. Tidak bisa mencentang Cek');
            exit(); 
		 }
		
		var cket = document.getElementById('ket').value;
        if (ckd==''){
            alert('SKPD Tidak Boleh Kosong');
            exit();
        }
		
		
		
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/akuntansi/simpan_pengesahan',
                    data: ({tabel:'trhspj_ppkd',kdskpd:ckd,real_gj:real_gj,real_up:real_up,real_brg:real_brg,tgl_terima:ctgl_terima,spj:cspj,bku:cbku,koran:ckoran,pajak:cpajak,sts:csts,ket:cket,cek:ccek,bulan:cbulan}),
                    dataType:"json"
                });
            });

        alert("Data Berhasil disimpan");
        //$("#dialog-modal").dialog('close');
        //$('#dg').edatagrid('reload');
		
    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Pengesahan DPA & DPPA';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("kode").disabled=true;
        }    
		
     function keluar(){
        $("#dialog-modal").dialog('close');
		lcstatus = 'edit';
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
  
    function cetak(ctk)
        {
			var cbulan = document.getElementById('bulan1').value;
			if(cbulan==''){
			alert('Bulan tidak boleh kosong!');
			exit();
			}
			var url = "<?php echo site_url(); ?>/akuntansi/ctk_register_spj";  
			window.open(url+'/'+cbulan+'/'+ctk, '_blank');
			window.focus();
        }
  
   </script>

</head>
<body>

<div id="content"> 
<h3 align="center"><u><b><a>REG. SPJ</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:400px;" border="0">
        <tr>
        	
        <td width="50%"><B>BULAN</B>
		<?php echo $this->rka_model->combo_bulan('bulan1','onchange="javascript:validate1();"'); ?></td><td>
        <input type="text" value="" id="txtcari" style="width:270px;"/> 
		<a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>&nbsp;&nbsp;
		<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak(1);"></a>
		<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak(2);"></a>
		<a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak(3);"></a>
		</td>

        </tr>
        <tr>
        <td colspan="4">
        <table id="dg" title="LISTING DATA PENGESAHAN" style="width:900px;height:500px;" >  
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
                <td width="30%">SKPD</td>
                <td width="1%">:</td>
                <td><input id="kode" style="width:100px;"/><input type="text" id="nmskpd" style="border:0:width:315px;"/></td>
            </tr> 
			<tr>
                <td width="30%">TGL Terima</td>
                <td width="1%">:</td>
                <td><input type="text" id="tgl_terima" style="width:100px;" /></td>  
            </tr>
			<tr>
                <td width="30%">Realisasi Gaji</td>
                <td width="1%">:</td>
                <td><input type="text" id="real_gj" style="width:150px;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>  
            </tr>
			<tr>
                <td width="30%">Realisasi UP/GU/TU</td>
                <td width="1%">:</td>
                <td><input type="text" id="real_up" style="width:150px;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>  
            </tr>
			<tr>
                <td width="30%">Realisasi Barang</td>
                <td width="1%">:</td>
                <td><input type="text" id="real_brg" style="width:150px;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>  
            </tr>
			
			<tr>
			<td width="30%">SPJ</td>
			<td width="1%">:</td>
			<td><input type="checkbox" id="spj" /></td>
			</tr>
			<tr>
			<td width="30%">BKU</td>
			<td width="1%">:</td>
			<td><input type="checkbox" id="bku" /></td>
			</tr>
			<tr>
			<td width="30%">Rek Koran</td>
			<td width="1%">:</td>
			<td><input type="checkbox" id="koran"  /></td>
			</tr>
			<tr>
			<td width="30%">BP Pajak</td>
			<td width="1%">:</td>
			<td><input type="checkbox" id="pajak"  /></td>
			</tr>
			<tr>
			<td width="30%">STS</td>
			<td width="1%">:</td>
			<td><input type="checkbox" id="sts"  /></td>
			</tr>
			<tr>
                <td width="30%">Keterangan</td>
                <td width="1%">:</td>
                <td><input type="text" id="ket" style="width:300px;"/></td>  
            </tr>
			<tr>
			<td width="30%">CEK</td>
			<td width="1%">:</td>
			<td><input type="checkbox" id="cek" /></td>
			</tr>
            <tr>
            <td colspan="5">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="5" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_pengesahan();">Simpan</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>

</body>

</html>