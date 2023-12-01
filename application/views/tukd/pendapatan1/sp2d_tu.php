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
    
    var nl =0;
	var tnl =0;
	var idx=0;
	var tidx=0;
	var oldRek=0;
    var rek=0;
	var lcstatus = '';
    
    $(function(){
   	     $('#dd').datebox({  
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
            $('#csp2d').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/pilih_sp2d',  
                    idField:'no_sp2d',                    
                    textField:'no_sp2d',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_sp2d',title:'SP2D',width:60},  
                        {field:'kd_skpd',title:'SKPD',align:'left',width:60},
                        {field:'no_spm',title:'SPM',width:60} 
                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    kode = rowData.no_sp2d;
                    dns = rowData.kd_skpd;
                    val_ttd(dns);
                    }   
                });
           });
        $(function(){   
		$('#cc').combobox({
					url:'<?php echo base_url(); ?>/index.php/tukd/load_jenis_beban',
					valueField:'id',
					textField:'text',
					onSelect:function(rowIndex,rowData){
					validate_tombol();
                    }
				});	
			 });   
			 
        function val_ttd(dns){
           $(function(){
            $('#ttd').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/pilih_ttd_bud',  
                    idField:'nip',                    
                    textField:'nama',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'nip',title:'NIP',width:60},  
                        {field:'nama',title:'NAMA',align:'left',width:100}
                        
                        
                    ]],
                    onSelect:function(rowIndex,rowData){
                    nip = rowData.nip;
                    
                    }   
                });
           });              
         }
		 
    $(function(){ 
     $('#sp2d').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_sp2d_tu',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
			{field:'ck',
    		title:'',
    		width:20,
			checkbox:'true'},
    	    {field:'no_sp2d',
    		title:'Nomor SP2D',
    		width:40},
            {field:'no_spm',
    		title:'Nomor SPM',
    		width:40},
            {field:'tgl_sp2d',
    		title:'Tanggal',
    		width:25},
            {field:'kd_skpd',
    		title:' SKPD',
    		width:25,
            align:"left"},
            {field:'keperluan',
    		title:'Keterangan',
    		width:140,
            align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
			no_sp2d = rowData.no_sp2d;
			tgl_sp2d = rowData.tgl_sp2d;
			no_spm = rowData.no_spm
			tgl_spm = rowData.tgl_spm
			no_spp = rowData.no_spp;         
			tgl_spp = rowData.tgl_spp;         
			kd_skpd  = rowData.kd_skpd;
			nm_skpd  = rowData.nm_skpd;
			jns_spp  = rowData.jns_spp;          
			keperluan  = rowData.keperluan;
			bulan  = rowData.bulan;
			no_spd  = rowData.no_spd;
			bank  = rowData.bank;
			nmrekan  = rowData.nmrekan;
			no_rek  = rowData.no_rek;
			npwp  = rowData.npwp;
			sp2d_batal  = rowData.sp2d_batal;
			status  = rowData.status;
			jns_bbn  = rowData.jenis_beban;
			lcstatus = 'edit';
			jns_spd  = rowData.jns_spd;

			
			getdata(no_sp2d,tgl_sp2d,no_spm,tgl_spm,no_spp,tgl_spp,kd_skpd,nm_skpd,jns_spp,keperluan,bulan,no_spd,bank,nmrekan,no_rek,npwp,jns_bbn,status,jns_spd);
			detail();
            pot();
        },
        onDblClickRow:function(rowIndex,rowData){
            section2();   
        }
    });
    }); 
        
              
    $(function(){		
            $('#nospm').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/nospm_tu',  
                    idField:'no_spm',                    
                    textField:'no_spm',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_spm',title:'No',width:60},  
                        {field:'kd_skpd',title:'SKPD',align:'left',width:80} 
                          
                    ]],
                     onSelect:function(rowIndex,rowData){
                        no_spm = rowData.no_spm
                        $("#nospm1").combogrid("setValue",no_spm);
                                                  
                    }  
                });
           });
    
		 $(function(){
            $('#nospm1').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/nospm1',  
                    idField:'no_spm',                    
                    textField:'no_spm',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_spm',title:'No',width:60},  
                        {field:'kd_skpd',title:'SKPD',align:'left',width:80} 
                          
                    ]],
                     onSelect:function(rowIndex,rowData){
                        no_spm = rowData.no_spm
                        tgspm = rowData.tgl_spm
                        no_spp = rowData.no_spp;         
                        dn  = rowData.kd_skpd;
                        sp  = rowData.no_spd;          
                        bl  = rowData.bulan;
                        tg  = rowData.tgl_spp;
                        jn  = rowData.jns_spp;
                        jns_bbn  = rowData.jns_beban;
						jns_spd  = rowData.jns_spd;
                        kep  = rowData.keperluan;
                        np  = rowData.npwp;
                        rekan  = rowData.nmrekan;
                        bk  = rowData.bank;
                        ning  = rowData.no_rek;
                        nm  = rowData.nm_skpd;
								
						if (jn=='4') {
							//alert ('beat');
							document.getElementById('no_sp2d').disabled=false;
						}else{
							//alert (jn);
					document.getElementById('no_sp2d').disabled=true;
													}

                       get(no_spm,tgspm,no_spp,dn,sp,tg,bl,jn,kep,np,rekan,bk,ning,nm,jns_bbn,jns_spd);
                       detail();
                       pot();                                                              
                    }  
                });
           }); 
           
 
             
        $(function(){
		$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true"
                                  
			});
		}); 
        
        
        
        $(function(){
			$('#pot').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/pot',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true",
                                  
			});
		}); 
		
     $(function(){
  $('#bank1').combogrid({  
                panelWidth:250,  
                url: '<?php echo base_url(); ?>/index.php/tukd/config_bank2',  
                    idField:'kd_bank',  
                    textField:'kd_bank',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'kd_bank',title:'Kd Bank',width:70},  
                           {field:'nama_bank',title:'Nama',width:180}
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    //$("#kode").attr("value",rowData.kode);
                    $("#nama_bank").attr("value",rowData.nama_bank);
                    }   
                });
                    
        }); 
		
        function detail(){
        $(function(){            
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                queryParams:({spp:no_spp}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,
                 onLoadSuccess:function(data){                      
                      load_sum_spm();
                      },                                  			 				 
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},                     
                     {field:'kdkegiatan',
					 title:'Kegiatan',
					 width:150,
					 align:'left'
					},
					{field:'kdrek5',
					 title:'Rekening',
					 width:70,
					 align:'left'
					},
					{field:'nmrek5',
					 title:'Nama Rekening',
					 width:400					 
					},
                    {field:'nilai1',
					 title:'Nilai',
					 width:130,
                     align:'right'
                     }
                      
				]]	
			
			});
  	

		});
        }
        
        function detail1(){
        $(function(){ 
            var no_spp='';
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                queryParams:({spp:no_spp}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,                                                   			 				 
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},                     
                     {field:'kdkegiatan',
					 title:'Kegiatan',
					 width:150,
					 align:'left'
					},
					{field:'kdrek5',
					 title:'Rekening',
					 width:70,
					 align:'left'
					},
					{field:'nmrek5',
					 title:'Nama Rekening',
					 width:300					 
					},
                    {field:'sisa',
					 title:'Sisa',
					 width:100,
                     align:'right'				 
                     },
                    {field:'nilai1',
					 title:'Nilai',
					 width:100,
                     align:'right'
                     }
                      
				]]	
			
			});
  	

		});
        }
        
        
        
         function pot(){
        $(function(){
	   	    //alert(no_spm);                         
			$('#pot').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/pot',
                queryParams:({spm:no_spm}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,
                 onLoadSuccess:function(data){                      
                      load_sum_pot();
                      },                              			 				 
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},                    
					{field:'kd_rek5',
					 title:'Rekening',
					 width:100,
					 align:'left'
					},
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:500
					},                    
                    {field:'nilai',
					 title:'Nilai',
					 width:100,
                     align:'right'
                     },
                     {field:'pot',
					 title:'ket',
					 width:30,
                     align:'center'
                     }
                      
				]]	
			
			});
  	

		});
        }
        
        function pot1(){
        $(function(){
	   	    var no_spm='';                         
			$('#pot').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/pot',
                queryParams:({spm:no_spm}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,                                              			 				 
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},                    
					{field:'kd_rek5',
					 title:'Rekening',
					 width:100,
					 align:'left'
					},
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:500
					},                    
                    {field:'nilai',
					 title:'Nilai',
					 width:100,
                     align:'right'
                     },
                     {field:'pot',
					 title:'ket',
					 width:30,
                     align:'center'
                     }
                      
				]]	
			
			});
  	

		});
        }
		
		
		function getdata(no_sp2d,tgl_sp2d,no_spm,tgl_spm,no_spp,tgl_spp,kd_skpd,nm_skpd,jns_spp,keperluan,bulan,no_spd,bank,nmrekan,no_rek,npwp,jns_bbn,status,jns_spd){
			//alert(status);
			$("#no_sp2d").attr("value",no_sp2d);
			$("#csp2d").combogrid("setValue",no_sp2d);
			$("#dd").datebox("setValue",tgl_sp2d);
			$("#nospm").combogrid("setValue",no_spm);
			$("#tgl_spm").attr("value",tgl_spm);
			$("#nospp").attr("value",no_spp);
			$("#tgl_spp").attr("value",tgl_spp);
			$("#dn").attr("value",kd_skpd);
			$("#nmskpd").attr("Value",nm_skpd);
			$("#jns_beban").attr("Value",jns_spp);
			$("#ketentuan").attr("Value",keperluan);
			$("#kebutuhan_bulan").attr("Value",bulan);
			$("#sp").attr("value",no_spd); 
            $("#bank1").combogrid("setValue",bank);
			$("#jns_spd").attr("value",jns_spd);
			$("#rekanan").attr("Value",nmrekan);
			$("#npwp").attr("Value",npwp);
			$("#rekening").attr("Value",no_rek);
			//alert(jenis_beban);
			validate_jenis_edit(jns_bbn);

        }
		
              
        function get(no_spm,tgspm,no_spp,kd_skpd,no_spd,tgl_spp,bulan,jns_spp,keperluan,npwp,rekanan,bank,rekening,nm_skpd,jns_bbn,jns_spd){
			$("#no_spm").attr("value",no_spm);
			$("#tgl_spm").attr("value",tgspm);
			$("#nospp").attr("value",no_spp);
			$("#dn").attr("value",kd_skpd);
			$("#sp").attr("value",no_spd);        
			$("#jns_spd").attr("value",jns_spd);        
			$("#tgl_spp").attr("value",tgl_spp);
			$("#kebutuhan_bulan").attr("Value",bulan);
			$("#ketentuan").attr("Value",keperluan);
			$("#jns_beban").attr("Value",jns_spp);
			$("#npwp").attr("Value",npwp);
			$("#rekanan").attr("Value",rekanan);
             $("#bank1").combogrid("setValue",bank);
			$("#rekening").attr("Value",rekening);
			$("#nmskpd").attr("Value",nm_skpd);
			validate_jenis_edit(jns_bbn);

        }
                  
        function getspm(no_sp2d,no_spm,tgl_sp2d,status,s_batal){
			//alert("sasa");
        $("#no_sp2d").attr("value",no_sp2d);
		$("#csp2d").combogrid("setValue",no_sp2d);
		$("#nospm").combogrid("setValue",no_spm);
        $("#dd").datebox("setValue",tgl_sp2d);
		$("#nospm1").combogrid("setValue",no_spm);
		status_terima = status;
        tombol(status);
        tombol_batal(s_batal);
        }

		function validate_jenis_edit($jns_bbn){
        var beban   = document.getElementById('jns_beban').value;
		$('#cc').combobox({url:'<?php echo base_url(); ?>/index.php/tukd/load_jenis_beban/'+beban,
		});

		$('#cc').combobox('setValue', jns_bbn);
	}
        function kosong(){
        cdate = '<?php echo date("Y-m-d"); ?>';
        $("#no_sp2d").attr("value",'');
        $("#dd").datebox("setValue",cdate);
        $("#nospm").combogrid("setValue",'');
        $("#nospm1").combogrid("setValue",'');
        $("#nospp").attr("value",'');
        $("#dn").attr("value",'');
        $("#sp").attr("value",'');        
        $("#jns_spd").attr("value",'');        
        $("#tgl_spp").attr("value",'');
        $("#tgl_spm").attr("value",'');
        $("#kebutuhan_bulan").attr("Value",'');
        $("#ketentuan").attr("Value",'');
        $("#jns_beban").attr("Value",'');
        $("#npwp").attr("Value",'');
        $("#rekanan").attr("Value",'');
        $("#bank1").combogrid("setValue",'');
        $("#rekening").attr("Value",'');
        $("#nmskpd").attr("Value",'');   
        document.getElementById("p1").innerHTML="";
        detail1();
        pot1();
		$("#no_sp2d_save").attr("value",'');
		
        $("#nospm").combogrid("clear");
        $("#nospm1").combogrid("clear");
        //tombolnew();      
 lcstatus = 'tambah';
        }


        $(document).ready(function() {
            $("#accordion").accordion();
            $("#lockscreen").hide();                        
            $("#frm").hide();
            $("#dialog-modal").dialog({
            height: 250,
            width: 700,
            modal: true,
            autoOpen:false
        });
            $("#dialog-batal").dialog({
            height: 300,
            width: 700,
            modal: true,
            autoOpen:false
        });
		get_tahun();
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
     function cetak(){
        $("#dialog-modal").dialog('open');
    } 
     function form_batal(){
		$("#no_sp2d_batal").attr("value",document.getElementById("no_sp2d").value);
		document.getElementById("no_spm_batal").value= $("#nospm").combogrid("getValue") ;
		$("#no_spp_batal").attr("value",document.getElementById("nospp").value);		
		 document.getElementById("ket_batal").value=''
        $("#dialog-batal").dialog('open');
    } 
    
    function keluar(){
        $("#dialog-modal").dialog('close');
    }   
    function keluar_batal(){
        $("#dialog-batal").dialog('close');
    }   
	
     function cari(){
     var kriteria = document.getElementById("txtcari").value; 
        $(function(){ 
            $('#sp2d').edatagrid({
	       url: '<?php echo base_url(); ?>/index.php/tukd/load_sp2d_tu',
         queryParams:({cari:kriteria})
        });        
     });
    }
        
 function simpan_sp2d(){        
        var no1 = document.getElementById('no_sp2d').value;
        var b1 = $('#dd').datebox('getValue');     
        var no_spm   = $("#nospm").combogrid("getValue") ; 
        var cc   = $("#cc").combobox("getValue") ; 
        var b2 =document.getElementById('tgl_spm').value;
        var b = document.getElementById('tgl_spp').value;      
        var c = document.getElementById('jns_beban').value; 
        var d = document.getElementById('kebutuhan_bulan').value;
        var e = document.getElementById('ketentuan').value;
        var f = document.getElementById('rekanan').value;
        var g  = $("#bank1").combogrid("getValue") ; 
        var h = document.getElementById('npwp').value;
        var i = document.getElementById('rekening').value;
        var j = document.getElementById('nmskpd').value;
        var k = document.getElementById('dn').value;
        var l = document.getElementById('sp').value;
        var total = document.getElementById('rekspm').value;
		var m = angka(total);
			
//jenis beban

switch (document.getElementById('jns_beban').value) {
	case '1':
       var jns_c='UP'	;	
        break;
	case '2':
       var jns_c='GU'	;	
        break;
	case '3':
       var jns_c='TU'	;	
        break;
	case '4':
       var jns_c='LS'	;	
        break;
	 default:
        var jns_c='LS'	;	
}
/* 		if (document.getElementById('jns_beban').value=='4' || document.getElementById('jns_beban').value=='5' || document.getElementById('jns_beban').value=='6')
		{
		var jns_c='LS'	;				
		} else {
			var jns_c=document.getElementById('jns_beban').value;
		} */
		
		var jns_bb=document.getElementById('jns_spd').value;

		// atas beban
		var beat_sp2d='/'+jns_c+'/'+jns_bb+'/'+tahun_anggaran;
		// utk sementara nomor manual jg by ToX
		var a_no_sp2d_smtr_tox=no_spm+beat_sp2d;
		var tahun_input = b1.substring(0, 4);
		//alert(tahun_input);
		//alert(tahun_anggaran);
		if (tahun_input != tahun_anggaran){
			alert('Tahun tidak sama dengan tahun Anggaran');
			exit();
		}
		if (no_spm=="") {
		alert ("Nomor SPM Tidak Boleh Kosong");
		exit();
		}
		
		if (b2>b1){
		alert("Tanggal SP2D tidak boleh lebih kecil dari Tanggal SPM");
		exit();
		}
		             
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({cskpd:k,cspd:l,no_sp2d:a_no_sp2d_smtr_tox,tgl_sp2d:b1,no_spm:no_spm,tgl_spm:b2,no_spp:no_spp,tgl_spp:b,jns_spp:c,bulan:d,keperluan:e,nmskpd:j,rekanan:f,bank:g,npwp:h,rekening:i,nilai:m,jenis:cc,sp2d_blk:beat_sp2d,lcstatus_input:lcstatus,no_sp2d_tag:no1}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/tukd/simpan_sp2d",
            success:function(data){
				if (data==2){                    			
                    alert('Data Gagal Tersimpan');
					document.getElementById('no_sp2d').value=data;					
                }else{					
					document.getElementById('no_sp2d_save').value=data;
					alert('Data Berhasil Tersimpan');
                    $('#sp2d').edatagrid('reload');
					spm_combo();
					section1();
                }
            }
         });
        });
		
		
        }
    
  
        /*           
         function hhapus(){				
          var sp2d = document.getElementById("no_sp2d").value;
          var no_spm   = $("#nospm").combogrid("getValue") ;
		  if(status_terima=='Sudah Diterima') {
		 alert("SP2D telah diterima SKPD. Batalkan Penerimaan terlebih dahulu");
		  exit();
		  }
            //alert(sp2d+no_spm);             
            var urll= '<?php echo base_url(); ?>/index.php/tukd/hapus_sp2d';             			    
         	if (sp2d !=''){
				var del=confirm('Anda yakin akan menghapus SP2D: '+sp2d+'  ?');
				if  (del==true){
					//ini untuk delete
					$(document).ready(function(){
                    $.post(urll,({no:sp2d,spm:no_spm}),function(data){
                    status = data;
                    spm_combo(); 
				
				}
				} 
		} */
        

        
		  function batal(){				
          var sp2d = document.getElementById("no_sp2d_batal").value;
          var no_spp = document.getElementById("no_spp_batal").value;
          var ket = document.getElementById("ket_batal").value;
		  var user_nm=''
		  if(status_terima=='Sudah Diterima') {
		 alert("SP2D telah diterima SKPD. Batalkan Penerimaan terlebih dahulu");
		  exit();
		  }
            //alert(sp2d+no_spm);             
            var urll= '<?php echo base_url(); ?>/index.php/tukd/batal_sp2d';             			    
           // var urll= '<?php echo base_url(); ?>/index.php/tukd/hapus_sp2d';             			    
         	if (sp2d !=''){
				var del=confirm('Anda yakin akan Membatalkan SP2D: '+sp2d+'  ?');
				if  (del==true){
					/*ini untuk delete
					$(document).ready(function(){
                    $.post(urll,({no:sp2d,spm:no_spm}),function(data){
                    status = data;
                    spm_combo(); */
					if (ket=='')
					{ alert('Keterangan harus diisi');
						exit();
					}
					$(document).ready(function(){
                    $.post(urll,({no:no_spp,ket:ket,user_nm:user_nm}),function(data){
                    status = data;
					keluar_batal();
					
					$('#sp2d').edatagrid('reload');
					section1();
                    });
                    });
				
				}
				} 
		}
        
        function load_sum_spm(){           
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({spp:no_spp}),
            url:"<?php echo base_url(); ?>index.php/tukd/load_sum_spm",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rekspm").attr("value",n['rekspm']);
                    $("#rekspm1").attr("value",n['rekspm1']);
                });
            }
         });
        });
    }         
        
        function load_sum_pot(){                
		//var spm = document.getElementById('no_spm').value;              
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({spm:no_spm}),
            url:"<?php echo base_url(); ?>index.php/tukd/load_sum_pot",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rektotal").attr("value",n['rektotal']);
                });
            }
         });
        });
    }
     function section1(){
         $(document).ready(function(){    
             $('#section1').click();                                               
         });
     }
     function section2(){
         $(document).ready(function(){    
             $('#section2').click();                                               
         });
     }
     function section3(){
         $(document).ready(function(){    
             $('#section3').click();                                               
         });
     }
     
     function tombol(st){  
     if (st=='Sudah Diterima'){
     $('#save').linkbutton('disable');
     $('#del').linkbutton('disable');
     $('#poto').linkbutton('disable');       
     document.getElementById("p1").innerHTML="Sudah di Terima SP2D!!";
     } else {
     $('#save').linkbutton('enable');
     $('#del').linkbutton('enable');
     $('#poto').linkbutton('enable');     
     document.getElementById("p1").innerHTML="";
     }
    }
    
	function tombol_batal(st_b){  
     if (st_b=='1'){
		 $('#save').linkbutton('disable');
		 $('#del').linkbutton('disable');
		 $('#poto').linkbutton('disable');
		 $('#cetak').linkbutton('disable'); 	 
		 document.getElementById("p1").innerHTML=">-|-|-|-|-|-|-|-|->  SP2D INI TELAH DIBATALKAN!!";
     } else {
		 $('#save').linkbutton('enable');
		 $('#del').linkbutton('enable');
		 $('#poto').linkbutton('enable'); 
		 $('#cetak').linkbutton('enable'); 
		 document.getElementById("p1").innerHTML="";
     }
    }
	
    function tombolnew(){  
    
     $('#save').linkbutton('enable');
     $('#del').linkbutton('enable');   
    }
    
    function openWindow(url) {
        var no =kode.split("/").join("123456789");
		var baris = document.getElementById('baris').value;
	    var vttd  = $("#ttd").combogrid("getValue"); 
		if(vttd==''){
			alert('Pilih Penandatangan dulu!');
			exit();
		}
	    var vttd =vttd.split(" ").join("abc");
		var jns_cetak = document.getElementById('jns_cetak').value;
        window.open(url+'/'+no+'/'+dns+'/'+vttd+'/'+baris+'/'+jns_cetak, '_blank');
        window.focus();
        }
		
    function cek(){
     /*    var lcno = document.getElementById('no_sp2d').value;
            if(lcno !=''){ */
               simpan_sp2d();               
            /* } else {

			alert('Nomor SP2D Tidak Boleh kosong')
                document.getElementById('no_sp2d').focus();
                exit();
            } */
if (jns_bbn=='4') {
	var lcno = document.getElementById('no_sp2d').value;
	 if(lcno ==''){
		 alert('Nomor SP2D Tidak Boleh kosong')
                document.getElementById('no_sp2d').focus();
                exit();
	 }
		
}
    
}
function spm_combo(){
			  $('#nospm').combogrid("setValue",'');
		$('#nospm').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/nospm_tu'
                                   });
	}
    </script>
    <STYLE TYPE="text/css"> 
input.right{ 
         text-align:right; 
         } 
</STYLE> 

</head>
<body>



<div id="content">

<div id="accordion">

<h3><a href="#" id="section1" onclick="javascript:$('#sp2d').edatagrid('reload')">List SP2D</a></h3>
    <div>
    <p align="right"> Nomor SP2D yang tersimpan&nbsp;&nbsp;<b><input type="text" name="no_sp2d_save" id="no_sp2d_save" style="width:200px ;" readonly="true"/></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|         
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section2();kosong();">Tambah</a>
        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a>               
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="sp2d" title="List SP2D" style="width:870px;height:450px;" >  
        </table>
                  
        
    </p> 
    </div>

<h3><a href="#" id="section2" onclick="javascript:$('#dg').edatagrid('reload')" >Input SP2D</a></h3>
   <div  style="height: 350px;">
   <p id="p1" style="font-size: x-large;color: red;"></p>
   <p>
  <!-- <?php echo form_open('tukd/simpan', array('class' => 'basic')); ?> -->
               
<table border='1' style="font-size:11px" >

 <tr  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;</td>
 </tr>

 
 <tr  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >No SP2D</td>
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><input type="text" name="no_sp2d" id="no_sp2d" style="width:200px;" readonly="true"/></td>
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Tgl SP2D </td>
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><input id="dd" name="dd" type="text" style="width:200px ;"/></td>
 </tr>
 <tr  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">No SPM</td>
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><input type="text" name="nospm" id="nospm" style="width:200px ;" /></td>
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Tgl SPM </td>
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><input id="tgl_spm" name="tgl_spm" type="text" readonly="true" style="width:200px ;"/></td>
 </tr>
 <tr  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">   
   <td width="8%"  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >No SPP</td>
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><input id="nospp" name="nospp" style="width:200px" readonly="true" /></td>
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Tgl SPP </td>
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><input id="tgl_spp" name="tgl_spp" type="text" readonly="true" style="width:200px ;" /></td>   
    </tr>
 <tr  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">SKPD</td>
   <td width="53%"  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >     
      <input id="dn" name="dn" style="width:200px" readonly="true"/></td> 
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Bulan</td>
   <td width="31%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><select  name="kebutuhan_bulan" id="kebutuhan_bulan" style="width:200px ;" disabled>
     <option value="">...Pilih Kebutuhan Bulan... </option>
     <option value="1" >1 | Januari</option>
     <option value="2">2 | Februari</option>
     <option value="3">3 | Maret</option>
     <option value="4">4 | April</option>
     <option value="5">5 | Mei</option>
     <option value="6">6 | Juni</option>
     <option value="7">7 | Juli</option>
     <option value="8">8 | Agustus</option>
     <option value="9">9 | September</option>
     <option value="10">10 | Oktober</option>
     <option value="11">11 | November</option>
     <option value="12">12 | Desember</option>
   </select></td> 
 </tr>
 <tr  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><textarea name="nmskpd" id="nmskpd" cols="40" rows="1" style="border: 0;"  readonly="true"></textarea></td>
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Keperluan</td>
   <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><textarea name="ketentuan" id="ketentuan" cols="30" rows="1" ></textarea></td>
 </tr>
 <tr  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">No SPD</td>
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><input id="sp" name="sp" style="width:200px" readonly="true"/> | <input id="jns_spd" name="jns_spd" style="width:20px" readonly="true"/></td>
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Rekanan</td>
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><textarea id="rekanan" name="rekanan" cols="30" rows="1"> </textarea></td>
 </tr>
 
 <tr  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Beban</td>
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><select name="jns_beban" id="jns_beban" style="width:200px;" disabled>
     <option value="">...Pilih Jenis Beban... </option>
     <option value="1">UP</option>
     <option value="2">GU</option>
     <option value="3">TU</option>
     <option value="4">LS GAJI</option>
     <option value="5">LS PPKD</option>
     <option value="6">LS Barang Jasa</option>
	 </select>
    <td width="8%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >BANK</td>
   <td width="8%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><input type="text" name="bank1" id="bank1" />
    &nbsp;<input type ="input" readonly="true" style="border:hidden" id="nama_bank" name="nama_bank" style="width:150" /></td>
 </tr>
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">
   <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Jenis</td>
   <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;<input id="cc" name="dept" style="width: 190px;" value=" Pilih Jenis Beban" readonly="true"></td>
   <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;</td>
 </tr> 
 <tr  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">NPWP</td>
   <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><input type="text" name="npwp" id="npwp" value=""  style="width:200px ;"/></td>
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Rekening</td>
   <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><input type="text" name="rekening" id="rekening"  value="" style="width:200px ;" /></td>
 </tr>
 
  <tr  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;</td>
  </tr>
       
 
     <tr  style="border-spacing: 3px;padding:3px 3px 3px 3px;">
                <td colspan="4" align="right"  style="border-bottom-color:black;border-spacing: 3px;padding:3px 3px 3px 3px;">
                <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Baru</a>
                <a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:cek();">Simpan</a>
                <a id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:form_batal();">Batal SP2D</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>                
                <a id="cetak" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a></td>                
     </tr>
     
     </table>

    <table id="dg" title=" Detail SPM" style="width:850%;height:250%;" >  
    </table>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>Total</B>&nbsp;&nbsp;<input class="right" type="text" name="rekspm" id="rekspm"  style="width:140px" align="right" readonly="true" >
        <input class="right" type="hidden" name="rekspm1" id="rekspm1"  style="width:100px" align="right" readonly="true" ><br />
        <table id="pot" title="List Potongan" style="width:850px;height:150px;" >  
        </table>
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

      No SPM: 
		<input class="right" id="nospm1" name="nospm1" disabled /></td>
		&nbsp;&nbsp;&nbsp;&nbsp;<B>Total</B>&nbsp;&nbsp;<input class="right" type="text" name="rektotal" id="rektotal"  style="width:140px" align="right" readonly="true" >
    <!-- <?php echo form_close(); ?> -->
    

   </p>
    </div>
    

   
  
</div>

</div> 

<div id="dialog-modal" title="CETAK SP2D">
    <p class="validateTips">SILAHKAN PILIH NO SP2D</p> 
    <fieldset>
    <table>
        <tr>
            <td width="110px">NO SP2D:</td>
            <td><input id="csp2d" name="csp2d" style="width: 170px;" /></td>
        </tr>
        <tr>
            <td width="110px">Penandatangan:</td>
            <td><input id="ttd" name="ttd" style="width: 170px;" /></td>
        </tr>
		 <tr>
            <td width="110px">Jenis:</td>
            <td><select name="jns_cetak" id="jns_cetak" style="width:200px;">
				 <option value="1">Normal </option>
				 <option value="2">Keterangan Panjang</option>
				 <option value="3">Baris Manual</option></td>
        </tr>
       <tr>
            <td width="110px">Baris:</td>
            <td><input type="number" id="baris" name="baris" style="width: 40px;"value="22" /></td>
        </tr>
    </table>  
    </fieldset>
	<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>/tukd/cetak_sp2d');return false;">Cetak SP2D</a>
	<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>/tukd/cetak_lamp_sp2d');return false;">Cetak Lampiran</a>
	<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>  
</div>


<div id="dialog-batal" title="KETERANGAN PEMBATALAN SP2D">
    <p class="validateTips">KETERANGAN PEMBATALAN SP2D</p> 
    <fieldset>
    <table>
        <tr>
            <td width="110px">NO SP2D:</td>
            <td><input id="no_sp2d_batal" name="no_sp2d_batal" style="width: 170px;" readonly="true"/></td>
        </tr>
        <tr>
            <td width="110px">NO SPM:</td>
            <td><input id="no_spm_batal" name="no_spM_batal" style="width: 170px;" readonly="true"/></td>
        </tr>
        <tr>
            <td width="110px">NO SPP:</td>
            <td><input id="no_spp_batal" name="no_spp_batal" style="width: 170px;" readonly="true"/></td>
        </tr>
        <tr>
            <td width="110px">KETERANGAN PEMBATALAN SP2D:</td>
            <td><textarea name="ket_batal" id="ket_batal" cols="70" rows="2" ></textarea></td>
        </tr>
       
    </table>  
    </fieldset>
    <a id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:batal();javascript:section1();">BATAL</a>
	<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar_batal();">Keluar</a>  
</div>
 	
</body>

</html>