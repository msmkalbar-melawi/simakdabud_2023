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
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.min.js"></script>
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.css" />
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
    
	<style>    
    #tagih {
        position: relative;
        width: 500px;
        height: 70px;
        padding: 0.4em;
    }  
    
     .satu{
            width: 157px;
     }

     .dua{
            width: 170px;
     }
    
    </style>
    <script type="text/javascript">
    
    var kode  = '';
    var giat  = '';
    var jenis = '';
    var nomor = '';
    var cid   = 0;
                      
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 750,
                width: 1100,
                modal: true,
                autoOpen:false                
            });         
            $( "#dialog-modal_edit" ).dialog({
				height: 200,
				width: 700,
				modal: true,
				autoOpen:false
			});           
			get_tahun();
			$('#save_all').linkbutton('disable');
        });    
     
    $(function(){ 
      $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/tukd/load_tglstsz',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'ck',title:'',checkbox:'true',width:20},
			{field:'no_sts',title:'No STS',width:35},
            {field:'tgl_sts',title:'Tanggal',width:22 },
            {field:'kd_skpd',title:'Kode SKPD',width:23,align:"left"},
            {field:'keterangan',title:'Keterangan',width:80,align:"left"}
        ]],
		onSelect:function(rowIndex,rowData){
          no_sts      = rowData.no_sts;
          tgl_sts     = rowData.tgl_sts;
          kd_skpd     = rowData.kd_skpd;
          keterangan  = rowData.keterangan;
          total       = rowData.total;
          kd_kegiatan = rowData.kd_kegiatan;
          jns_trans   = rowData.jns_trans;
          jns_cp      = rowData.jns_cp;
          pot_khusus  = rowData.pot_khusus;
          nm_skpd     = rowData.nm_skpd;
		  status      = rowData.status;

          get(no_sts,tgl_sts,kd_skpd,keterangan,total,kd_kegiatan,jns_trans,jns_cp,pot_khusus,nm_skpd,status);   
          load_detail();
        },
        onDblClickRow:function(rowIndex,rowData){         
            section2();                  
        }
    });
    
    $('#dg1').edatagrid({  
		toolbar:'#toolbar',
		rownumbers:"true", 
		fitColumns:"true",
		singleSelect:"true",
		autoRowHeight:"false",
		loadMsg:"Tunggu Sebentar....!!",            
		nowrap:"true",
            columns:[[
				{field:'no_sts',title:'No STS',hidden:"true",width:30},
				{field:'kd_skpd',title:'SKPD',hidden:"true",width:30},
				{field:'kd_rek5',title:'REK',align:"center",width:12},
				{field:'nm_rek4',title:'JENIS REK',align:"center",width:30},
				{field:'nm_rek',title:'NAMA REK',align:"left",width:50},
				{field:'rupiah',title:'NILAI',align:"right",width:20},
				{field:'sumber',title:'SUMBER',hidden:"true",width:30},
				{field:'nmsumber',title:'NAMA SUMBER',align:"center",width:30}
            ]],
            
           onDblClickRow:function(rowIndex,rowData){
           idx = rowIndex; 
           lcrekedt = rowData.kd_rek5;
           lcnmrekedt = rowData.nm_rek;
           lcnmrek4edt = rowData.nm_rek4;
           lcnilaiedt = rowData.rupiah; 
           lcnmsumberedt = rowData.nmsumber; 
           get_edt(lcrekedt,lcnmrekedt,lcnilaiedt,lcnmrek4edt,lcnmsumberedt); 
           
			}
        }); 

         $('#tglvoucher').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();    
            	return y+'-'+m+'-'+d;
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
		
		$('#kd_skpd').combogrid({  
           panelWidth:700,  
           idField:'kd_skpd',  
           textField:'kd_skpd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/tarik_skpd',  
           columns:[[  
               {field:'kd_skpd',title:'Kode SKPD',width:100},  
               {field:'nm_skpd',title:'Nama SKPD',width:900}    
           ]],  
           onSelect:function(rowIndex,rowData){
               kode = rowData.kd_skpd; 
			   $("#nm_skpd").attr("value",rowData.nm_skpd.toUpperCase());
           }  
       });
 
	});

	function get_tahun() {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd/config_tahun',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        			tahun_anggaran = data;
        			}                                     
        	});
             
        }
               
    function load_detail(){
        var kk = no_sts;
        var skpd = kd_skpd;
        var ctgl = $('#tanggal').datebox('getValue');
        
           $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/tukd/load_dtglsts',
                data: ({no:kk,kd_skpd:skpd,ctgl:ctgl}),
                dataType:"json",
                success:function(data){                                          
					$('#dg1').datagrid('loadData',[]);     
					$('#dg1').edatagrid('reload');   
					$.each(data,function(i,n){                                    
                    no_sts      = n['no_sts'];
                    kd_skpd  = n['kd_skpd'];                                                                    
                    kd_rek5 = n['kd_rek5'];
                    nm_rek  = n['nm_rek'];
                    nmrek   = n['nm_rek5'];
                    rupiah  = number_format(n['rupiah'],2,'.',',');
					sumber = n['sumber'];
                    nmsumber = n['nmsumber'];
					nm_rek4 = n['nm_rek4'];
					
					$('#dg1').edatagrid('appendRow',{no_sts:no_sts,kd_skpd:kd_skpd,kd_rek5:kd_rek5,nm_rek:nm_rek,rupiah:rupiah,sumber:sumber,nmsumber:nmsumber,nm_rek4:nm_rek4});                                      
                    });                                                                           
                }
            });
           });                
           set_grid();                                                  
    }

    function set_grid(){
        $('#dg1').edatagrid({           
            columns:[[
				{field:'no_sts',title:'No STS',hidden:"true",width:30},
				{field:'kd_skpd',title:'SKPD',hidden:"true",width:30},
				{field:'kd_rek5',title:'REK',align:"center",width:12},
				{field:'nm_rek4',title:'JENIS REK',align:"center",width:30},
				{field:'nm_rek',title:'NAMA REK',align:"left",width:50},
				{field:'rupiah',title:'NILAI',align:"right",width:20},
				{field:'sumber',title:'SUMBER',hidden:"true",width:30},
				{field:'nmsumber',title:'NAMA SUMBER',align:"center",width:30}
            ]]
        });                 
    }

    function section1(){
         $(document).ready(function(){    
             $('#section1').click();                                               
         });
         set_grid();
         cari_tgl();        
     }
     
	function section2(){
         $(document).ready(function(){                
             $('#section2').click(); 
         });                 
         set_grid();
    }
     
	function get(no_sts,tgl_sts,kd_skpd,keterangan,total,kd_kegiatan,jns_trans,jns_cp,pot_khusus,nm_skpd,status){
        
		$("#nomor").attr("value",no_sts);
        $("#tanggal").datebox("setValue",tgl_sts);
		$("#skpd").attr("value",kd_skpd);
		$("#nmskpd").attr("value",nm_skpd);
        $("#keterangan").attr("value",keterangan);        
        $("#jns_trans").attr("value",jns_trans);        
        $("#kd_kegiatan").attr("value",kd_kegiatan);        
        $("#total").attr("value",number_format(total,2,'.',','));
	}
	
	function simpan_sts(){
        
		var cnokas = 1;
        var cnosimpan = '';
        var ctglkas = $('#tanggal').datebox('getValue');
        
		var cno = document.getElementById('nomor').value;
        
		var cbank = '';
        var ctgl = $('#tanggal').datebox('getValue');
		
        var cskpd = document.getElementById('skpd').value;
		
        var lcket = document.getElementById('keterangan').value;
		
        var sumber = '';
        var cjnsrek = document.getElementById('jns_trans').value;
		
        var cgiat = document.getElementById('kd_kegiatan').value;
		
        var lcrekbank ='';
        var lntotal = angka(document.getElementById('total').value);
		
       
	   //alert(ctglkas+'/'+cno+'/'+cskpd+'/'+lcket+'/'+cjnsrek+'/'+cgiat+'/'+lntotal);
		
		$(document).ready(function(){
               	
        $('#dg1').datagrid('selectAll');
        var rows = $('#dg1').datagrid('getSelections');           
		lcval_det = '';
        for(var i=0;i<rows.length;i++){
            cnosts   = rows[i].no_sts;
            ckdrek  = rows[i].kd_rek5;              
            cnilai  = angka(rows[i].rupiah);  
            csumber  = rows[i].sumber; 
            
            if(i>0){
				lcval_det = lcval_det+",('"+cskpd+"','"+cnosts+"','"+ckdrek+"','"+cnilai+"','"+cnokas+"','"+cgiat+"','"+csumber+"')";
			}else{
				lcval_det = lcval_det+"('"+cskpd+"','"+cnosts+"','"+ckdrek+"','"+cnilai+"','"+cnokas+"','"+cgiat+"','"+csumber+"')";
			}              
		}
        
        $('#dg1').datagrid('unselectAll'); 
        $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/tukd/simpan_sts_kasda',
                data: ({tabel:'trhkasin_ppkd',nokas:cnokas,tglkas:ctglkas,no:cno,bank:cbank,tgl:ctgl,skpd:cskpd,ket:lcket,jnsrek:cjnsrek,giat:cgiat,rekbank:lcrekbank,total:lntotal,value_det:lcval_det,sumber:sumber}),
                dataType:"json",
                success:function(data){
                    status = data.pesan;
                    if(status=='0'){
                          alert('gagal');
                    } else{
							alert("Data Berhasil disimpan");
							section1();
							cari_tgl();
					}
                }
            });
        });
        
		});
        
            
         
					
	}			

	function simpan_all(){
        
        var ctgl = $('#tglvoucher').datebox('getValue');
        var cskpd = $('#kd_skpd').combogrid('getValue');
		 
        $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/tukd/simpan_sts_kasda_all',
                data: ({tabel:'trhkasin_ppkd',tgl:ctgl,skpd:cskpd}),
                dataType:"json",
                success:function(data){
                    status = data.pesan;
                    if(status=='0'){
                          alert('gagal');
                    } else{
							alert("Data Berhasil disimpan");
							section1();
							cari_tgl();
					}
                }
            });
       
		});
        
            
         
					
	}			

	
	
	function get_edt(lcrekedt,lcnmrekedt,lcnilaiedt,lcnmrek4edt,lcnmsumberedt){
        $("#rek_edt").attr("value",lcrekedt);
        $("#nmrek_edt").attr("value",lcnmrekedt);
        $("#nmrek4_edt").attr("value",lcnmrek4edt);
        $("#nilai_edt").attr("value",lcnilaiedt);
        $("#nilai_edth").attr("value",lcnilaiedt);
        $("#sumber_edth").attr("value",lcnmsumberedt);
        $("#dialog-modal_edit").dialog('open');
    } 
   
    function cari_tgl(){
        
    var kriteria = $('#tglvoucher').datebox('getValue'); 
    var kd_skpd  = $('#kd_skpd').combogrid('getValue');
    var cek = 1;
	if(kriteria==''){alert('Tanggal Tidak Boleh Kosong'); exit();}else{  
    set_grid();  
    
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_tglsts',
        queryParams:({cari:kriteria,kd_skpd:kd_skpd})
        });        
     });
     }
	 
	 validate_tombol(cek);
    }
    
	function validate_tombol(cek){
       
		if (cek==1){
			$('#save_all').linkbutton('enable');
		}else{
			$('#save_all').linkbutton('disable');
			
		}
		
    }
    
	
    function load_tgltrans(){
        
    var kriteria = $('#tglvoucher').datebox('getValue'); 
    if(kriteria==''){
        
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1;
    var yyyy = today.getFullYear();
    if(dd<10){
    dd='0'+dd;
    } 
    if(mm<10){
        mm='0'+mm;
    } 
    var kriteria = yyyy+'-'+mm+'-'+dd;
	
    }else{  
       
    reload_datag1(); 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_tglsts',
        queryParams:({cari:kriteria})
        });        
     });
     }
    }
    
    function runEffect() {
        var selectedEffect = 'blind';            
        var options = {};                      
        $( "#tagih" ).toggle( selectedEffect, options, 500 );
        $("#notagih").combogrid("setValue",'');
        $("#tgltagih").attr("value",'');
        //$("#skpd").combogrid("setValue",'');
        $("#keterangan").attr("value",'');
        $("#beban").attr("value",'');
        
    };              
    
    function reload_data() {
    $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/tukd/load_tglstsz',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'ck',title:'',checkbox:'true',width:20},
			{field:'no_sts',title:'No STS',width:35},
            {field:'tgl_sts',title:'Tanggal',width:22 },
            {field:'kd_skpd',title:'Kode SKPD',width:23,align:"left"},
            {field:'keterangan',title:'Keterangan',width:80,align:"left"}
        ]],
		onSelect:function(rowIndex,rowData){
          no_sts      = rowData.no_sts;
          tgl_sts     = rowData.tgl_sts;
          kd_skpd     = rowData.kd_skpd;
          keterangan  = rowData.keterangan;
          total       = rowData.total;
          kd_kegiatan = rowData.kd_kegiatan;
          jns_trans   = rowData.jns_trans;
          jns_cp      = rowData.jns_cp;
          pot_khusus  = rowData.pot_khusus;
          nm_skpd     = rowData.nm_skpd;
		  status      = rowData.status;

          get(no_sts,tgl_sts,kd_skpd,keterangan,total,kd_kegiatan,jns_trans,jns_cp,pot_khusus,nm_skpd,status);   
          load_detail();
        },
        onDblClickRow:function(rowIndex,rowData){         
            section2();                  
        }
    });
    }

    function reload_datag1() {
    $('#dg').edatagrid({
		columns:[[
		{field:'ck',
    		title:'',
			checkbox:'true',
    		width:20},
			{field:'no_sts',
    		title:'Nomor',
    		width:20},
            {field:'tgl_sts',
    		title:'Tanggal',
    		width:50},
            {field:'nm_skpd',
    		title:'Nama SKPD',
    		width:90,
            align:"left"},
            {field:'keterangan',
    		title:'Keterangan',
    		width:100,
            align:"left"}
        ]]
    });
    }  
   
   </script>

</head>
<body>



<div id="content">    
<div id="accordion">
<h3><a href="#" id="section1" >List Daftar STS</a></h3>
    <div>
	*) Khusus Rekening Pendapatan <b>Tanpa BPKPD, PPKD, RSUD SOEDARSO</b>
    <p align="left"> 
		S K P D
        <input id="kd_skpd" name="kd_skpd" style="width: 90px;" /><input type="text" id="nm_skpd" style="border:0;width: 300px;" readonly="true"/>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
		Tanggal
        <input name="tglvoucher" type="text" id="tglvoucher" style="width:100px; border: 0;"/>  
		&nbsp;
		<a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari_tgl();">Tampil</a><a id="save_all" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_all();">Simpan All</a>
        <table id="dg" title="List STS" style="width:870px;height:600px;" >  
        </table>                          
    </p> 
    </div>   

<h3><a href="#" id="section2">STS</a></h3>
   <div  style="height: 350px;">
   <p>       
   <div id="demo"></div> 
        <table align="center" style="width:100%;" >
            
            <tr>
                <td>No. STS</td>
                <td><input type="text" id="nomor" style="width: 400px;" /></td>
                <td>&nbsp;&nbsp;</td>
                <td>Tanggal</td>
                <td><input type="text" id="tanggal" style="width: 140px;" disabled /></td>     
            </tr>
                                   
            <tr>
                <td>O P D</td>
                <td colspan="4"><input id="skpd" name="skpd" style="width: 80px;" readonly="true" />&nbsp;<input type="text" id="nmskpd" style="border:0;width: 600px;" readonly="true"/></td>
                
            </tr>
            <tr>
                <td>Keterangan</td>
                <td colspan="4"><textarea id="keterangan" style="width: 650px; height: 40px;" readonly="true" ></textarea></td>
           </tr> 
		   <tr>
                <td>Jenis Transaksi</td>
                <td><input id="jns_trans" name="jns_trans" style="width: 140px;" readonly="true"/></td>
                <td></td>
                <td></td> 
                <td></td>                                
            </tr>
			<tr>
                <td>Kegiatan</td>
                <td><input id="kd_kegiatan" name="kd_kegiatan" style="width: 140px;" readonly="true"/></td>
                <td></td>
                <td></td> 
                <td></td>                                
            </tr>
            
            <tr>
               <td colspan="5" align="right">
					
                    <a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_sts();">Simpan</a>
                   
  		            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>                                   
                </td>
            </tr>
        </table>          
        <table id="dg1" title="Rekening" style="width:870px;height:450px;" >  
        </table>  
        <div id="toolbar" align="right">
        </div>
                
   </p>
    <table border="0" align="right" style="width:100%;"><tr>
   <td style="width:75%;" align="right"><B>JUMLAH</B></td>
   <td align="right"><input type="text" id="total" readonly="true" style="border:0; width: 200px;"/></td>
   </tr>
   </table>
   
 <div id="dialog-modal_edit" title="Edit Rekening">
    <fieldset>
    <table>
        <tr>
            <td width="110px">Kode Rekening:</td>
            <td><input type="text" id="rek_edt" readonly="true" style="width: 200px;" /></td>
        </tr>
		<tr>
            <td width="110px">Jenis:</td>
            <td><input type="text" id="nmrek4_edt" readonly="true" style="border:0;width: 400px;"/></td>
        </tr>
        <tr>
            <td width="110px">Nama Rekening:</td>
            <td><input type="text" id="nmrek_edt" readonly="true" style="border:0;width: 400px;"/></td>
        </tr>
		 <tr>
            <td width="110px">Nama Sumber:</td>
            <td><input type="text" id="sumber_edth" readonly="true" style="border:0;width: 400px;"/></td>
        </tr>
        <tr> 
           <td width="110px">Nilai:</td>
           <td><input type="text" id="nilai_edt" readonly="true" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/>
               <input type="hidden" id="nilai_edth"/> 
           </td>
        </tr>
    </table>  
    </fieldset>
</div>
</div>

</div>
</body>

</html>