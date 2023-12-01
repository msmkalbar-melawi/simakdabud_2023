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
        position: relative;
        width: 700px;
        height: 70px;
        padding: 0.4em;
    }  
    </style>
    
    <script type="text/javascript"> 
    var nl       = 0;
	var tnl      = 0;
	var idx      = 0;
	var tidx     = 0;
	var oldRek   = 0;
    var rek      = 0;
    var kode     = '';
    var pidx     = 0;  
    var frek     = '';             
    var rek5     = '';
    var edit     = '';
    var lcstatus = '';
	
	// created by tox
	function Left(str, n){
		str=str.trim();
	if (n <= 0)
	    return "";
	else if (n > String(str).length)
	    return str;
	else
	    return String(str).substring(0,n);
	}
     
	function Right(str, n){
		str=str.trim();
    if (n <= 0)
       return "";
    else if (n > String(str).length)
       return str;
    else {
       var iLen = String(str).length;
       return String(str).substring(iLen, iLen - n);
    }
	}
// end of note created by tox
                    
$(document).ready(function(){
    $("input").on({
/*         mouseenter: function(){
            $(this).css("background-color", "lightgray");
        }, */  
        mouseleave: function(){
            $(this).css("background-color", "white");
        }, 
        click: function(){
            $(this).css("background-color", "#99FF99");
        }  
    });
});
					
					
    $(document).ready(function() {
            $("#accordion").accordion({
            height: 500
            });
            $("#lockscreen").hide();                        
            $("#frm").hide();
            $( "#dialog-modal" ).dialog({
            height: 200,
            width: 700,
            modal: true,
            autoOpen:false
        });
        $( "#dialog-modal-rek" ).dialog({
            height: 320,
            width: 900,
            modal: true,
            autoOpen:false
        });
            $("#tagih").hide();
            get_skpd();
      });
    
        
        $(function(){
       	     $('#dd_sp2d').datebox({  
                required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return y+'-'+m+'-'+d;
                }
            });
			
			 $('#dd_spm').datebox({  
                required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return y+'-'+m+'-'+d;
                }
            });
			
			 $('#dd').datebox({  
                required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return y+'-'+m+'-'+d;
                }
            });
			
			$('#tgl_mulai').datebox({  
                required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return y+'-'+m+'-'+d;
                }
            });
			
			$('#ttd').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/pilih_ttd_bud',  
                    idField:'nip',                    
                    textField:'nama',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'nip',title:'NIP',width:160},  
                        {field:'nama',title:'NAMA',align:'left',width:200}
                    ]],
                    onSelect:function(rowIndex,rowData){
                    nip = rowData.nip;
                    }   
                });
			
			$('#tgl_akhir').datebox({  
                required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return y+'-'+m+'-'+d;
                }
            });
		
			 $('#tglspd').datebox({  
                required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return d+'-'+m+'-'+y;
                }
            });
                
                
                
				
        $(function(){
            $('#csp2d').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/load_sp2d_manual',
				queryParams:({tanda_m:'1'}),				
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
					tampil_perusahaan();
                    }   
                });
           });
                
                $('#notagih').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/load_no_penagihan',  
                    idField:'no_tagih',  
                    textField:'no_tagih',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'no_tagih',title:'No Penagihan',width:140},  
                           {field:'tgl_tagih',title:'Tanggal',width:140},
                           {field:'kd_skpd',title:'SKPD',width:140}
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    var ststagih='1';
					no_tagih = rowData.no_tagih;
                    $("#tgltagih").attr("value",rowData.tgl_tagih);
                    $("#nil").attr("value",rowData.nila);
                    $("#ni").attr("value",rowData.nil);
					detail_tagih(no_tagih);
					$("#rektotal_ls").attr('value',rowData.nila);
                    $("#rektotal1_ls").attr('value',rowData.nila);
					get_skpd();
                    }   
                });
                   
			$('#bank1').combogrid({  
                panelWidth:200,  
                url: '<?php echo base_url(); ?>/index.php/tukd/config_bank2',  
                    idField:'kd_bank',  
                    textField:'kd_bank',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'kd_bank',title:'Kd Bank',width:40},  
                           {field:'nama_bank',title:'Nama',width:140}
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    //$("#kode").attr("value",rowData.kode);
                    $("#nama_bank").attr("value",rowData.nama_bank);
                    }   
                });
                    
                    
                    $('#sp2d').edatagrid({
            		url: '<?php echo base_url(); ?>/index.php/tukd/load_sp2d_manual',
					queryParams:({tanda_m:'1'}),
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
			checkbox:'true',
    		width:40},
                	    {field:'no_sp2d',
                		title:'Nomor SP2D',
                		width:40},
                        {field:'tgl_sp2d',
                		title:'Tanggal',
                		width:25},
                        {field:'kd_skpd',
                		title:'Nama SKPD',
                		width:25,
                        align:"left"},
                        {field:'keperluan',
                		title:'Keterangan',
                		width:140,
                        align:"left"}
                    ]],
                    onSelect:function(rowIndex,rowData){
                      no_sp2d  = rowData.no_sp2d;         
                      no_spm   = rowData.no_spm;         
                      no_spp   = rowData.no_spp;         
                      kode     = rowData.kd_skpd;
                      sp       = rowData.no_spd;          
                      bl       = rowData.bulan;
					  tg_sp2d       = rowData.tgl_sp2d;
					  tg_spm       = rowData.tgl_spm;
                      tg       = rowData.tgl_spp;
                      jn       = rowData.jns_spp;
                      kep      = rowData.keperluan;
                      np       = rowData.npwp;
                      rekan    = rowData.nmrekan;
                      bk       = rowData.bank;
                      ning     = rowData.no_rek;
                      status   = rowData.status;
                      kegi     = rowData.kd_kegiatan;
                      nm       = rowData.nm_kegiatan;
                      kprog    = rowData.kd_program;
                      nprog    = rowData.nm_program;
                      dir      = rowData.dir;
                      notagih  = rowData.no_tagih;
                      tgltagih = rowData.tgl_tagih;
                      sttagih  = rowData.sts_tagih;         
                      alamat  = rowData.alamat;         
                      kontrak  = rowData.kontrak;         
                      lanjut  = rowData.lanjut;         
                      tgl_mulai  = rowData.tgl_mulai;         
                      tgl_akhir  = rowData.tgl_akhir;
                      no_spd  = rowData.no_spd;
					  get(no_sp2d,no_spm,no_spp,kode,sp,tg_sp2d,tg_spm,tg,bl,jn,kep,np,rekan,bk,ning,status,kegi,nm,kprog,nprog,dir,notagih,tgltagih,sttagih,alamat,kontrak,lanjut,tgl_mulai,tgl_akhir,no_spd);					
					  det();       
                      detail_trans_3();   
                      validate_kegiatan() ;
                      load_sum_spp(); 
					   load_sum_pot();
                      edit = 'T' ;
                      lcstatus = 'edit';
					  },
                    onDblClickRow:function(rowIndex,rowData){
                        section2();   
                    }
                });
                
                
                
                $('#sp').combogrid({
                panelWidth:400,                 			
                    idField:'no_spd',  
                    textField:'no_spd',
                    mode:'remote',  
                    fitColumns:true,                    
                    columns:[[  
                        {field:'no_spd',title:'No SPD',width:230},  
                        {field:'tgl_spd',title:'Tanggal',align:'left',width:70}                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    spd = rowData.no_spd;
					tglspd = rowData.tgl_spd;
					$("#tglspd").datebox("setValue",tglspd);
                    validate_kegi(spd);                                                        
                    }    
                });
                
                
                $('#kg').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/kegi',  
                    idField:'kd_kegiatan',  
                    textField:'kd_kegiatan',
                    mode:'remote',  
                    fitColumns:true,                       
                    columns:[[  
                        {field:'kd_kegiatan',title:'Kode',width:30},  
                        {field:'nm_kegiatan',title:'Nama',align:'left',width:70}                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    kegi   = rowData.kd_kegiatan;
                    nmkegi = rowData.nm_kegiatan;
                    $("#nm_kg").attr("value",rowData.nm_kegiatan);
                    prog = rowData.kd_program;
                    $("#kp").attr("value",rowData.kd_program);
                    nmprog = rowData.nm_program;
                    $("#nm_kp").attr("value",rowData.nm_program);
                    nilai= rowData.nilai;                   
                    //det_tox();                                                        
                    det();
					s()					
                    }    
                });
                
                
                $('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data2',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true",
			});
            
                
                $('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true",
			});
            
            
                $('#dgsppls').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"false",
                 singleSelect:"true",
                 nowrap:"false",
                 columns:[[
                    {field:'idx',title:'idx',width:100,align:'left',hidden:'true'},               
                    {field:'kdkegiatan',title:'Kegiatan',width:150,align:'left'},
					{field:'kdrek5',title:'Rekening',width:70,align:'left'},
					{field:'nmrek5',title:'Nama Rekening',width:280},
                    {field:'nilai1',title:'Nilai',width:140,align:'right'},
                    {field:'hapus',title:'Hapus',width:100,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                    }
                    }
				]]	
           }); 
            

           
		   
           $('#skpd').combogrid({  
           panelWidth:700,  
           idField:'kd_skpd',  
           textField:'kd_skpd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/skpd_2',  
           columns:[[  
               {field:'kd_skpd',title:'Kode SKPD',width:100},  
               {field:'nm_skpd',title:'Nama SKPD',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               kode = rowData.kd_skpd ;
			   v_ats_beban = document.getElementById('ats_beban').value;  
				$("#nm_skpd").attr("value",rowData.nm_skpd.toUpperCase());			   
				$("#rek_skpd").combogrid("setValue",rowData.kd_skpd);
				val_ttd(kode);
				tampil_perusahaan();
				$("#rek_nmskpd").attr("value",rowData.nm_skpd.toUpperCase());    
				$('#sp').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/spd1',
                                   queryParams:({kode:kode,v_ats_beban:v_ats_beban})
                                   });
								   
			   
			  			   
           }  
           });
		  
		  
           $('#rek_skpd').combogrid({  
           panelWidth:700,  
           idField:'kd_skpd',  
           textField:'kd_skpd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/skpd_2',  
           columns:[[  
               {field:'kd_skpd',title:'Kode SKPD',width:100},  
               {field:'nm_skpd',title:'Nama SKPD',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               kode = rowData.kd_skpd ;               
               $("#rek_nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
           }  
           });
           
           
           $('#rek_kegi').combogrid({  
           panelWidth:700,  
           idField:'kd_kegiatan',  
           textField:'kd_kegiatan',  
           mode:'remote',
           columns:[[  
               {field:'kd_kegiatan',title:'Kode Kegiatan',width:150},  
               {field:'nm_kegiatan',title:'Nama Kegiatan',width:700}    
           ]]  
           });
           
           
           $('#rek_reke').combogrid({  
           panelWidth:700,  
           idField   :'kd_rek5',  
           textField :'kd_rek5',  
           mode      :'remote',
           columns   :[[  
               {field:'kd_rek5',title:'Kode Rekening',width:150},  
               {field:'nm_rek5',title:'Nama Rekening',width:700}    
           ]]  
           });
		   
		   
                $('#rekpajak').combogrid({  
                   panelWidth : 700,  
                   idField    : 'kd_rek5',  
                   textField  : 'kd_rek5',  
                   mode       : 'remote',
                   url        : '<?php echo base_url(); ?>index.php/tukd/rek_pot',  
                   columns:[[  
                       {field:'kd_rek5',title:'Kode Rekening',width:100},  
                       {field:'nm_rek5',title:'Nama Rekening',width:700}    
                   ]],  
                   onSelect:function(rowIndex,rowData){
                       $("#nmrekpajak").attr("value",rowData.nm_rek5.toUpperCase());
                   }  
                   });
		   
			$('#dgpajak').edatagrid({
    			     url            : '<?php echo base_url(); ?>/index.php/tukd/pot',
                     idField        : 'id',
                    // toolbar        : "#toolbar",              
                     rownumbers     : "true", 
                     fitColumns     : false,
                     autoRowHeight  : "true",
                     singleSelect   : false,
                     columns:[[
                        {field:'id',title:'id',width:100,align:'left',hidden:'true'}, 
                        {field:'kd_rek5',title:'Rekening',width:100,align:'left'},			
    					{field:'nm_rek5',title:'Nama Rekening',width:317},
    					{field:'nilai',title:'Nilai',width:250,align:"right"},
                        {field:'hapus',title:'Hapus',width:100,align:"center",
                        formatter:function(value,rec){ 
                        return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail_pajak();" />';
                        }
                        }
        			]]	
        			});

        });
        function tampil_perusahaan(){
        $('#rekanan').combogrid({  
                panelWidth:200,  
                url: '<?php echo base_url(); ?>/index.php/tukd/perusahaan',  
                    idField:'nmrekan',  
                    textField:'nmrekan',
                    mode:'remote',  
                    fitColumns:true,
					queryParams:({kode:kode}),					
                    columns:[[  
                           {field:'nmrekan',title:'Perusahaan',width:40} 
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    //$("#kode").attr("value",rowData.kode);
                    $("#dir").attr("value",rowData.pimpinan);
                    $("#npwp").attr("value",rowData.npwp);
					
                    }   
                });
		}
		
        function get_skpd()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
  								      $("#skpd").combogrid("setValue",data.kd_skpd);
        							  $("#nm_skpd").attr("value",data.nm_skpd);
                                      $("#rek_skpd").combogrid("setValue",data.kd_skpd);
                                      $("#rek_nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
                                      kode = data.kd_skpd;
                                      validate_spd(kode);
        	}  
            });
        }
        
        function detail_tagih(no_tagih){
		//alert("aaa");
        $(function(){
			$('#dgsppls').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data_tagih',
                queryParams    : ({ no:no_tagih }),
                 idField       : 'idx',
                 toolbar       : "#toolbar",              
                 rownumbers    : "true", 
                 fitColumns    : false,
                 autoRowHeight : "false",
                 singleSelect  : "true",
                 nowrap        : "true",
                 onLoadSuccess : function(data){                      
                 },
                onSelect:function(rowIndex,rowData){
                    
                    kd          = rowIndex ;  
                    idx         =  rowData.idx ;
                    tkdkegiatan = rowData.kdkegiatan ;
                    tkdrek5     = rowData.kdrek5 ;
                    tnmrek5     = rowData.nmrek5 ;
                    tnilai1     = rowData.nilai1 ;
                                                               
                },
                 columns:[[
                     {field:'idx',
					 title:'idx',
					 width:100,
					 align:'left',
                     hidden:'true'
					 },               
                     {field:'kdkegiatan',
					 title:'Sub Kegiatan',
					 width:180,
					 align:'left'
					 },
					{field:'kdrek5',
					 title:'Rekening',
					 width:70,
					 align:'left'
					 },
					{field:'nmrek5',
					 title:'Nama Rekening',
					 width:280
					 },
                    {field:'nilai1',
					 title:'Nilai',
					 width:140,
                     align:'right'
                     },
                    {field:'hapus',title:'Hapus',width:100,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                    }
                    }
				]]	
			});
		});
        }
		
		 function tampil_sp2d(){
            $('#csp2d').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/load_sp2d_manual',
				queryParams:({tanda_m:'1'}),				
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
                   
                    }   
                });
		 };		
		
        function validate_kegiatan(){
            var kode_s = $('#skpd').combogrid('getValue'); 
			
            $(function(){
              $('#rek_kegi').combogrid({  
              panelWidth:700,  
              idField   :'kd_kegiatan',  
              textField :'kd_kegiatan',  
              mode      :'remote',
              url       :'<?php echo base_url(); ?>index.php/tukd/load_trskpd_ar_2',  
              queryParams:({kdskpd:kode_s}), 
              columns   :[[  
               {field:'kd_kegiatan',title:'Kode Kegiatan',width:150},  
               {field:'nm_kegiatan',title:'Nama Kegiatan',width:700}    
               ]],
               onSelect:function(rowIndex,rowData){      
               $("#nm_rek_kegi").attr("value",rowData.nm_kegiatan); 
               $("#rek_reke").combogrid("setValue",''); 

			   kd_kegia = rowData.kd_kegiatan;
               var tgl_spd   = $('#tglspd').datebox('getValue');      
					 $.ajax({
                                type     : "POST",
                        		dataType : "json",   
                                data     : ({kegiatan:kd_kegia,kd_skpd:kode_s,tglspd:tgl_spd}), 
                        		url      : '<?php echo base_url(); ?>index.php/tukd/total_spd',
                        		success  : function(data){
                        		      $.each(data, function(i,n){
                                        $("#total_spd").attr("Value",n['nilai']);
                                        var n_totalspd  = n['nilai'] ;
                                       // var n_sisa = angka(n_ang) - angka(n_spp) ;
                                       // $("#rek_nilai_sisa").attr("Value",number_format(n_sisa,2,'.',','));
                                    });
        						}                                     
        	               });
						   
				   
					 $.ajax({
                                type     : "POST",
                        		dataType : "json",   
                                data     : ({kegiatan:kd_kegia,kd_skpd:kode_s}), 
                        		url      : '<?php echo base_url(); ?>index.php/tukd/pakai_spd',
                        		success  : function(data){
                        		      $.each(data, function(i,n){
                                        $("#nilai_spd_lalu").attr("Value",n['nilai']);
                                        var n_spdlalu  = n['nilai'] ;
										var total_spd = document.getElementById('total_spd').value;
                                        var n_sisaspd = angka(total_spd) - angka(n_spdlalu) ;
                                        $("#nilai_sisa_spd").attr("Value",number_format(n_sisaspd,2,'.',','));
                                    });
        						}                                     
        	               });		   
						   
               validate_rekening(); 
               }              
           });
           });
        }    
        
 
        function validate_rekening(){
           $('#dgsppls').datagrid('selectAll');
           var rows = $('#dgsppls').datagrid('getSelections');     
           frek  = '' ;
           rek5  = '' ;
           for ( var p=0; p < rows.length; p++ ) { 
           rek5 = rows[p].kdrek5;                                       
           if ( p > 0 ){   
                  frek = frek+','+rek5;
              } else {
                  frek = rek5;
              }
           }
		   
		       $('#dgpajak').datagrid('selectAll');
           var rows = $('#dgpajak').datagrid('getSelections');
                
           frek  = '' ;
           rek5  = '' ;
           for ( var p=0; p < rows.length; p++ ) { 
           rek5 = rows[p].kd_rek5;                                       
           if ( p > 0 ){   
                  frek = frek+','+rek5;
              } else {
                  frek = rek5;
              }
           }
           
		   
                var kode_s   = $('#skpd').combogrid('getValue');
                var kode_keg = $('#rek_kegi').combogrid('getValue') ;
               // var kode_keg = '1.20.1.20.12.01.00.51';
                var nospp    = document.getElementById('no_spp').value ;
                $(function(){
                  $('#rek_reke').combogrid({  
                  panelWidth:700,  
                  idField   :'kd_rek5',  
                  textField :'kd_rek5',  
                  mode      :'remote',
                  url       :'<?php echo base_url(); ?>index.php/tukd/load_rek_ar',  
                  queryParams:({kdkegiatan:kode_keg,kdrek:frek}), 
                  columns:[[  
                   {field:'kd_rek5',title:'Kode Rekening',width:150},  
                   {field:'nm_rek5',title:'Nama Rekening',width:700}    
                   ]],
                   onSelect:function(rowIndex,rowData){      
				   $("#nm_rek_reke").attr("value",rowData.nm_rek5); 
                           var koderek = rowData.kd_rek5 ;
							document.getElementById("rek_nilai").select();
                           $.ajax({
                                type     : "POST",
                        		dataType : "json",   
                                data     : ({kegiatan:kode_keg,kdrek5:koderek,kd_skpd:kode_s,no_spp:nospp}), 
                        		url      : '<?php echo base_url(); ?>index.php/tukd/jumlah_ang_spp',
                        		success  : function(data){
                        		      $.each(data, function(i,n){
                                        $("#rek_nilai_ang").attr("Value",n['nilai']);
                                        $("#rek_nilai_spp").attr("Value",n['nilai_spp_lalu']);
                                        
                                        var n_ang  = n['nilai'] ;
                                        var n_spp  = n['nilai_spp_lalu'] ;
                                        var n_sisa = angka(n_ang) - angka(n_spp) ;
                                        $("#rek_nilai_sisa").attr("Value",number_format(n_sisa,2,'.',','));
                                    });
        						}                                     
        	               });
                   }                
               });
               });
				$('#dgsppls').datagrid('unselectAll');
				$('#dgpajak').datagrid('unselectAll');  
        }
           
        
        /* 
        function val_ttd(dns){
           $(function(){
            $('#ttd').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/pilih_ttd/'+dns,  
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
         } */
         		 
        function val_ttd(dns){
           $(function(){			  
            $('#bend_ttd').combogrid({  
                panelWidth:700,  
                url: '<?php echo base_url(); ?>/index.php/tukd/pilih_ttd_tox/'+dns,  
				queryParams:({kd:'BK'}),  				
                    idField:'nip',                    
                    textField:'nip',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'nip',title:'NIP',width:200},  
                        {field:'nama',title:'NAMA',align:'left',width:300},
                        {field:'npwp',title:'NPWP',align:'left',width:200}
                    ]],
                    onSelect:function(rowIndex,rowData){
                    nip = rowData.nip;					
					$("#bend_nama").attr("value",rowData.nama);
					$("#npwp").attr("value",rowData.npwp);					
                    }   
                });
           });              
         }
        
    function validate_spd(kode){
           $(function(){
            $('#sp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/spd1/'+kode,  
                    idField:'no_spd',  
                    textField:'no_spd',
                    mode:'remote',  
                    fitColumns:true
                });
           });
        }
	
     
    function validate_kegi(spd){
           $(function(){
            $('#kg').combogrid({  
                panelWidth:500,
                url: '<?php echo base_url(); ?>/index.php/tukd/kegi',
                 queryParams:({spd:spd}),  
                    idField:'kd_kegiatan',  
                    textField:'kd_kegiatan',
                    mode:'remote',  
                    fitColumns:true
                });
           });
        }

   
    function det(){   
          $(function(){            
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data2',
                queryParams:({giat:kegi}),
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,
                 onLoadSuccess:function(data){
                      detail1();                                           
                    },								
                 onClickRow:function(rowIndex, rowData){                                
								keg=rowData.kd_kegiatan;
								rk=rowData.kd_rek5;
                                nkeg=rowData.nm_kegiatan;
                                nrek=rowData.nm_rek5;
                                ang=rowData.a;
                                kel=rowData.b;
                                sisa=ang - kel;
								simpan(keg,rk,nkeg,nrek,sisa);
                                detail1();
								},				 				 
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
                     {field:'pilih',
					 title:'pilih',
					 width:20,
                     align:'center',
					 checkbox:true,
                     hidden:true
                     },
                     {field:'kd_kegiatan',
					 title:'Kegiatan',
					 width:150,
					 align:'left'
					},                    
					{field:'kd_rek5',
					 title:'Rekening',
					 width:70,
					 align:'left'
					},
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:300
					},
                    {field:'a',
					 title:'Nilai Anggaran',
					 width:100,
                     align:'right',
                      hidden:true
                     },
                    {field:'b',
					 title:'SPP Lalu',
					 width:100,
                     align:'right',
                     hidden:true
                     },
                    {field:'nilai',
					 title:'Nilai Anggaran',
					 width:100,
                     align:'right'
                     },
                    {field:'total',
					 title:'SPP Lalu',
					 width:100,
                     align:'right'
                     }
				]]	
			});
            });
        }

        
		 function det_toxzz(){   
          $(function(){
           $('#rek_reke').combogrid({  
           panelWidth:700,
			url: '<?php echo base_url(); ?>/index.php/tukd/select_data2',
                queryParams:({giat:kegi}),		   
           idField   :'kd_rek5',  
           textField :'kd_rek5',  
           mode      :'remote',
           columns   :[[  
               {field:'kd_rek5',title:'Kode Rekening',width:150},  
               {field:'nm_rek5',title:'Nama Rekening',width:700}    
           ]],
                    onSelect:function(rowIndex,rowData){
                    $("#rek_nilai_ang").attr("value",rowData.nilai);
                    $("#rek_nilai_spp").attr("value",rowData.total);
					sisa=rowData.total-rowData.nilai;
                    $("#rek_nilai_sisa").attr("value",n['sisa']);
           }				
            });
			 });
        }

		
        function det_baru(){   
	   	  var kegi='';
          $(function(){            
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data2',
                queryParams:({giat:kegi}),
                 idField:'id',
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
                     {field:'pilih',
					 title:'pilih',
					 width:20,
                     align:'center',
					 checkbox:true,
                     hidden:true
                     },
                     {field:'kd_kegiatan',
					 title:'Kegiatan',
					 width:150,
					 align:'left'
					},                    
					{field:'kd_rek5',
					 title:'Rekening',
					 width:70,
					 align:'left'
					},
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:300
					},
                    {field:'a',
					 title:'Nilai Anggaran',
					 width:100,
                     align:'right'
                     },
                    {field:'b',
					 title:'SPP Lalu',
					 width:100,
                     align:'right'
                     }
				]]	
			});
            });
        }


        function detail1(){
        $(function(){
	   	    var spp = document.getElementById('no_spp').value;            
			$('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                queryParams:({spp:spp}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,
                 onLoadSuccess:function(data){                      
                      //load_sum_spp();                        
                    },
                onSelect:function(rowIndex,rowData){
                kd = rowIndex;                                               
                },   
                 onAfterEdit:function(rowIndex, rowData, changes){								
								kegiatan=rowData.kdkegiatan;
                                nkegiatan=rowData.nmkegiatan;
								rekeing=rowData.kdrek5;
                                nrekeing=rowData.nmrek5;
                                nilai=rowData.nilai1;
                                si=rowData.sis;
                                kd=rowIndex;
								dsimpan(kegiatan,rekeing,nkegiatan,nrekeing,nilai,si,kd);       	                                  
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
                     align:'right',                    
					 editor:{type:"numberbox"					     
							}
                     }
				]]	
			});
		});
        }


     function get(no_sp2d,no_spm,no_spp,kd_skpd,no_spd,tgl_sp2d,tgl_spm,tgl_spp,bulan,jns_spp,keperluan,npwp,rekanan,bank,rekening,status,giat,nmgiat,prog,nmprog,pim,notagih,tgltagih,ststagih,alamat,kontrak,lanjut,tgl_mulai,tgl_akhir,no_spd){
		 //alert(bank);

		 $("#no_sp2d").attr("value",no_sp2d);
        $("#no_sp2d_hide").attr("value",no_sp2d);
        $("#no_spm").attr("value",no_spm);
        $("#no_spm_hide").attr("value",no_spm);
        $("#no_spp").attr("value",no_spp);
        $("#no_spp_hide").attr("value",no_spp);
		$("#no_simpan").attr("value",no_spp);
        $("#sp").combogrid("setValue",no_spd);
        $("#dd_sp2d").datebox("setValue",tgl_sp2d);
        $("#dd_spm").datebox("setValue",tgl_spm);
        $("#dd").datebox("setValue",tgl_spp);
        $("#tgl_mulai").datebox("setValue",tgl_mulai);
        $("#tgl_akhir").datebox("setValue",tgl_akhir);
        $("#kebutuhan_bulan").attr("Value",bulan);
        $("#skpd").combogrid("setValue",kd_skpd); // tox
        $("#nm_skpd").attr("setValue",nm_skpd); // tox
		$("#sp").combogrid("setValue",no_spd);
		$("#ketentuan").attr("Value",keperluan);
        $("#jns_beban").attr("Value",jns_spp);
        $("#npwp").attr("Value",npwp);
		var vrekan=Right(rekanan,21);
		if (vrekan=='BENDAHARA PENGELUARAN')
		{
		$("#bend_nama").attr("Value",rekanan);
		document.getElementById("ck_rekanan").checked = false;	
			 }
		else
		{			
	document.getElementById("ck_rekanan").checked = true;
        $("#rekanan").combogrid("setValue",rekanan);
        $("#dir").attr("Value",pim);
		};
		hidden_bend()	

        $("#alamat").attr("Value",alamat);
        $("#kontrak").attr("Value",kontrak);
        $("#lanjut").attr("Value",lanjut);
        $("#bank1").combogrid("setValue",bank);
        $("#rekening").attr("Value",rekening);
        $("#kg").combogrid("setValue",giat);
        $("#nm_kg").attr("Value",nmgiat);
        $("#kp").attr("setValue",prog);
        $("#nm_kp").attr("Value",nmprog);
        $("#notagih").combogrid("setValue",notagih);        
        $("#tgltagih").attr("Value",tgltagih);    
        $("#status").attr("checked",false);                  
        if (ststagih==1){            
            $("#status").attr("checked",true);
            $("#tagih").show();
        } else {
            $("#status").attr("checked",false);
            $("#tagih").hide();
        }
		tampil_potongan();   
         tombol(status);           
        }
		
    
    function kosong(){
		$("#no_sp2d").attr("value",'nomor otomatis');
		$("#no_sp2d_hide").attr("value",'');
        $("#no_spm").attr("value",'');
        $("#no_spm_hide").attr("value",'');
        $("#no_spp").attr("value",'');
        $("#no_spp_hide").attr("value",'');
        $("#sp").combogrid("setValue",'');
		$("#dd_sp2d").datebox("setValue",'');
        $("#dd_spm").datebox("setValue",'');
        $("#dd").datebox("setValue",'');
        $("#tgl_mulai").datebox("setValue",'');
        $("#tgl_akhir").datebox("setValue",'');
        $("#tglspd").datebox("setValue",'');
        $("#skpd").combogrid("setValue",'');
		$("#nm_skpd").attr("setValue",''); // tox;
        $("#sp").combogrid("setValue",'');
		$("#tgl_spd").datebox("setValue",'');
		$("#kebutuhan_bulan").attr("Value",'');
        $("#ketentuan").attr("Value",'');
        $("#jns_beban").attr("Value",'');
        $("#npwp").attr("Value",'');
        $("#rekanan").combogrid("setValue",'');
        $("#dir").attr("Value",'');
        $("#bend_ttd").combogrid("setValue",'');
        $("#bend_nama").attr("Value",'');
        $("#bank1").combogrid("setValue",'');
        $("#rekening").attr("Value",'');
        $("#kg").combogrid("setValue",'');
        $("#nm_kg").attr("Value",'');
        $("#kg").combogrid("setValue",'');
        $("#nm_kg").attr("Value",'');
        $("#nama_bank").attr("Value",'');
        $("#kontrak").attr("Value",'');
        $("#lanjut").attr("Value",'');
        $("#alamat").attr("Value",'');
        $("#kp").attr("setValue",'');
        $("#nm_kp").attr("Value",'');
// inputan pajak
        $("#rekpajak").combogrid("setValue",'');
        $("#nilairekpajak").attr("Value",0);
        $("#nmrekpajak").attr("value",'');
		
        document.getElementById("p1").innerHTML="";        
        $("#sp").combogrid("clear");
        $("#kg").combogrid("clear");
        det_baru();
        tombolnew();
        detail_kosong();
        detail_kosong_pajak(); 		
        validate_kegiatan();
		document.getElementById("ck_rekanan").checked = false;
		hidden_bend()		

        var pidx  = 0   ;     
        edit      = 'F' ;
        
        $("#rektotal_ls").attr("Value",0);
        $("#rektotal1_ls").attr("Value",0);
		$("#totalrekpajak").attr("value",0);
        
        lcstatus = 'tambah';
        
        }


	
    function getRowIndex(target){  
			var tr = $(target).closest('tr.datagrid-row');  
			return parseInt(tr.attr('datagrid-row-index'));  
		}  


    
    function simpan(giat,reke,nkeg,nrek,sisa){		
		var spp   = document.getElementById('no_spp').value;
		var cskpd = kode;
        var cspd  = spd;

        $(function(){      
            $.ajax({
            type: 'POST',
            data: ({cskpd:cskpd,cspd:spd,cspp:spp,cgiat:giat,crek:reke,cnmgiat:nkeg,cnmrek:nrek,sspp:sisa}),
            dataType:"json",
            url:'<?php echo base_url(); ?>/index.php/tukd/tsimpan'
         });
        });
		}       
        
       
    function cetak(){
		tampil_sp2d()
	var nom=document.getElementById("no_sp2d").value;
        $("#csp2d").combogrid("setValue",nom);
        $("#dialog-modal").dialog('open');
		
		} 
    
    
    function keluar(){
        $("#dialog-modal").dialog('close');
    } 
    
    
    function keluar_rek(){
        $("#dialog-modal-rek").dialog('close');
        $("#dgsppls").datagrid("unselectAll");

        $("#rek_nilai").attr("Value",0);
        $("#rek_nilai_ang").attr("Value",0);
        $("#rek_nilai_spp").attr("Value",0);
        $("#rek_nilai_sisa").attr("Value",0);
    }     
    
    
    function cari(){
     var kriteria = document.getElementById("txtcari").value; 
        $(function(){ 
            $('#sp2d').edatagrid({
	       url: '<?php echo base_url(); ?>/index.php/tukd/load_sp2d_manual',
        		queryParams:({cari:kriteria,tanda_m:'1'})
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

     
    function hsimpan(){        

        var a_no_sp2d       = document.getElementById('no_sp2d').value;
        var a_no_sp2d_hide  = document.getElementById('no_sp2d_hide').value;
        var a_no_spm     	= document.getElementById('no_spm').value;
        var a_no_spm_hide	= document.getElementById('no_spm_hide').value;
        var b_tgl_sp2d      = $('#dd_sp2d').datebox('getValue');      
        var b_tgl_spm       = $('#dd_spm').datebox('getValue');      
        var a       		= document.getElementById('no_spp').value;
        var a_hide  		= document.getElementById('no_spp_hide').value;
        var b_sp2d	= $('#dd_sp2d').datebox('getValue');		
        var b_spm	= $('#dd_spm').datebox('getValue');
        var b       = $('#dd').datebox('getValue');
		var spd		= $("#sp").combogrid('getValue');
        var c       = document.getElementById('jns_beban').value;
        var d       = document.getElementById('kebutuhan_bulan').value;
        var e       = document.getElementById('ketentuan').value;
if (document.getElementById("ck_rekanan").checked == true)
	{
        var f       = $("#rekanan").combogrid("getValue") ;
        var f1      = document.getElementById('dir').value;
	} else
	{
		var f       = document.getElementById('bend_nama').value+', BENDAHARA PENGELUARAN'
        var f1      = document.getElementById('bend_nama').value+', BENDAHARA PENGELUARAN';
	}			
        var g       = $("#bank1").combogrid("getValue") ;
        var h       = document.getElementById('npwp').value;
        var i       = document.getElementById('rekening').value;
        var j       = document.getElementById('nm_skpd').value;
        var k1		= document.getElementById('rektotal1_ls').value;
        var l       = document.getElementById('nm_kg').value;
        var m       = document.getElementById('kp').value;
        var n       = document.getElementById('nm_kp').value;
        var alamat       = document.getElementById('alamat').value;
        var kontrak      = document.getElementById('kontrak').value;
        var lanjut       = document.getElementById('lanjut').value;
        var tgl_mulai    = $('#tgl_mulai').datebox('getValue');      
        var tgl_akhir    = $('#tgl_akhir').datebox('getValue');
		
		// username dan lasupdate by tox
	
//		var usernm= $userdata('pcNama');
		var usernm= 'tox';
//		var last_update= date('Y-m-d H:i:s');		
		var last_update='';


		// end username dan lasupdate
  //     var o       = document.getElementById('status').checked; 
        //alert(c);
//jenis beban
		if (document.getElementById('jns_beban').value=='3') {
		var jns_c='TU';
		} else {
		var jns_c='LS'	;		
		}
// atas beban
		if (document.getElementById('ats_beban').value=='51') {
		var jns_bb='BTL';
		} else {
		var jns_bb='BL'	;		
		}
		var pcthn_anggaran='2015';

		var beat_sp2d='/'+jns_c+'/'+jns_bb+'/'+pcthn_anggaran;
		// utk sementara nomor manual jg by ToX
//		var a_no_sp2d_smtr_tox=a_no_spm+a+beat_sp2d+b_tgl_spm+b;
		var a_no_sp2d_smtr_tox=a_no_sp2d+beat_sp2d;

        var z       = $("#sp").combogrid("getValue") ; 
        var y       = $("#kg").combogrid("getValue") ; 
        var k 		= angka(k1);
	
		/* 
        if ( o == false ){
           o=0;
        }else{
            o=1;
        } */

		
        if ( a_no_spm == '' ){
            alert("Isi Nomor SPM Terlebih Dahulu...!!!") ;
            exit();
        }
		
		
        if ( b_tgl_spm == '' ){
            alert("Isi Tanggal SPM Terlebih Dahulu...!!!") ;
            exit();
        }
		
        if ( a == '' ){
            alert("Isi Nomor SPP Terlebih Dahulu...!!!") ;
            exit();
        }
        
        if ( b == '' ){
            alert("Isi Tanggal Terlebih Dahulu...!!!") ;
            exit();
        }
        
        if ( c == '' ){
            alert("Isi Jenis Beban Terlebih Dahulu...!!!") ;
            exit();
        }
        
        if ( d == '' ){
            alert("Isi Kebutuhan Bulan Terlebih Dahulu...!!!") ;
            exit();
        }
        
   
        if (spd == '' ){
            alert("Isi Nomor SPD Terlebih Dahulu...!!!") ;
            exit();
        }
        
        /* if ( q == '' ){
            alert("Isi Kode Kegiatan Terlebih Dahulu...!!!") ;
            exit();
        } */

		if(lcstatus == 'tambah'){
		$(document).ready(function(){
			
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:a_no_sp2d,tabel:'trhsp2d',field:'no_sp2d'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1){
						alert("Nomor Telah Dipakai!");
						document.getElementById("nomor").focus();
						exit();
						} 
						if(status_cek==0){
						alert("Nomor Bisa dipakai");
		
		//---------
		//lcinsert = "(no_spp,  kd_skpd,    keperluan, bulan,   no_spd,    jns_spp, bank,    nmrekan,  no_rek,  npwp,    nm_skpd,  tgl_spp, status, username,     last_update,         nilai,    no_bukti,     kd_kegiatan,  nm_kegiatan,  kd_program,  nm_program,  pimpinan,  no_tagih,    tgl_tagih,  sts_tagih, no_bukti2, no_bukti3, no_bukti4, no_bukti5, no_spd2, no_spd3, no_spd4 )"; 
            //lcvalues = "('"+a+"', '"+kode+"', '"+e+"',   '"+d+"', '"+spd+"', '"+c+"', '"+g+"', '"+f+"',  '"+i+"', '"+h+"', '"+j+"',  '"+b+"', '0',    '"+pcNama+"', date('d-m-y H:i:s'), '"+k+"',  '',           '"+kegi+"',   '"+l+"',      '"+m+"',     '"+n+"',     '"+f1+"',  '"+p+"',     '"+q+"',    '"+o+"',    '',       '',        '',        '',        '',      '',      ''      )";
            
            // kurang nya username dan lastupdate 

			lcinsert_sp2d = "(no_sp2d					, tgl_sp2d		  , no_spm		, tgl_spm		,  bulan, kd_skpd	,nm_skpd, no_spp	, tgl_spp	, no_spd	, username		, last_update		,keperluan	, status	, status_bud, jns_spp	, bank	, nmrekan, no_rek	, npwp	, nilai	)"; 
	
            lcvalues_sp2d = "('"+a_no_sp2d_smtr_tox+"'	,'"+b_tgl_sp2d+"','"+a_no_spm+"','"+b_tgl_spm+"','"+d+"', '"+kode+"','"+j+"', '"+a+"'	,'"+b+"'	,'"+spd+"'	,'"+usernm+"'	,'"+last_update+"','"+e+"'		,'0'		,'0'		,'"+c+"'	, '"+g+"','"+f+"','"+i+"'	,'"+h+"','"+k+"')";
					  
            lcinsert_spm = "(no_spm,			tgl_spm, 	no_spp, 	kd_skpd, nm_skpd, tgl_spp, 	no_spd, bulan, keperluan, username		, last_update		,status	, jns_spp, nmrekan	, npwp	, no_rek, bank	,  nilai)"; 
            lcvalues_spm = "('"+a_no_spm+"','"+b_tgl_spm+"','"+a+"', '"+kode+"', '"+j+"','"+b+"','"+spd+"','"+d+"','"+e+"'	,'"+usernm+"'	,'"+last_update+"'	,'0'	,'"+c+"','"+f+"'	,'"+h+"','"+i+"','"+g+"','"+k+"')";

            lcinsert = "(no_spp,  kd_skpd,    keperluan, bulan,   no_spd	,    jns_spp, bank	,    nmrekan,  no_rek,  npwp,    nm_skpd	, tgl_spp	, status, username	,last_update,   nilai,    no_bukti	,     kd_kegiatan	,  nm_kegiatan	,  kd_program	,  nm_program,  pimpinan,  no_tagih	,tgl_tagih	,  sts_tagih, no_bukti2	, no_bukti3, no_bukti4, no_bukti5, no_spd2, no_spd3, no_spd4 , alamat		, kontrak		, lanjut	, tgl_mulai		, tgl_akhir)"; 
            lcvalues = "('"+a+"', '"+kode+"', '"+e+"',   '"+d+"', '"+spd+"'	, '"+c+"'	, '"+g+"', '"+f+"'	, '"+i+"','"+h+"', '"+j+"'		,  '"+b+"'	, '0'	,  ''		,		  '','"+k+"',  ''			, '"+y+"'			,   '"+l+"'		,      '"+m+"'	,     '"+n+"',  '"+f1+"',  ''		,     ''	,    ''		,    ''		,       '',        '',        '',        '',      '',      '', '"+alamat+"'	, '"+kontrak+"'	,'"+lanjut+"','"+tgl_mulai+"','"+tgl_akhir+"' )";

//			 lcinsert = "(no_spp,  kd_skpd,    keperluan, bulan,   no_spd,    jns_spp, bank,    nmrekan,  no_rek,  npwp,    nm_skpd,  tgl_spp, status, username,     last_update,   nilai,    no_bukti,     kd_kegiatan,  nm_kegiatan,  kd_program,  nm_program,  pimpinan,  no_tagih,    tgl_tagih,  sts_tagih, no_bukti2, no_bukti3, no_bukti4, no_bukti5, no_spd2, no_spd3, no_spd4 , alamat, kontrak, lanjut, tgl_mulai, tgl_akhir)"; 
//            lcvalues = "('"+a+"', '"+kode+"', '"+e+"',   '"+d+"', '"+spd+"', '"+c+"', '"+g+"', '"+f+"',  '"+i+"', '"+h+"', '"+j+"',  '"+b+"', '0',    '',           '',            '"+k+"',  '',           '"+y+"',   '"+l+"',      '"+m+"',     '"+n+"',     '"+f1+"',  '"+p+"',     '"+q+"',    '"+o+"',    '',       '',        '',        '',        '',      '',      '',      '"+alamat+"', '"+kontrak+"','"+lanjut+"','"+tgl_mulai+"','"+tgl_akhir+"' )";

			 
            $(document).ready(function(){
				
                $.ajax({
                    type     : "POST",
                    url      : '<?php echo base_url(); ?>/index.php/tukd/simpan_sp2d_tukd',
				data     : ({tabel:'trhspp',tabel_2:'trhspm',tabel_3:'trhsp2d',kolom:lcinsert,nilai:lcvalues,kolom_spm:lcinsert_spm,nilai_spm:lcvalues_spm,kolom_sp2d:lcinsert_sp2d,nilai_sp2d:lcvalues_sp2d,cid:'no_spp',lcid:a,cid_spm:'no_spm',lcid_spm:a_no_spm,cid_sp2d:'no_sp2d',lcid_sp2d:a_no_sp2d,no_smtr:a_no_sp2d_smtr_tox,sp2d_blk:beat_sp2d}),                    
				dataType : "json",
                    success  : function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Simpan..!!');
                            exit();
                        } else if(status=='1'){
                                  alert('Data Sudah Ada..!!');
                                  exit();
                               } else {
                                  detsimpan();
								  detsimpan_pajak()
                                  alert('Data Tersimpan..!!');
								   $("#no_sp2d_hide").attr("value",a_no_sp2d);
									 $("#no_simpan").attr("value",a_no_sp2d);
                                  lcstatus = 'edit';
                                  exit();
                               }
                    }
                });
            });   
           
		//----------
		
		}
		}
		});
		});
		
        
            
        } else {
//alert(z);
//update data
			$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:a,tabel:'trhsp2d',field:'no_sp2d'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1 && a_no_sp2d!=a_no_sp2d_hide){
						alert("Nomor Telah Dipakai!");
						exit();
						} 
						if(status_cek==0 || a==a_hide){
						alert("Nomor Bisa dipakai");
			
		//---------
		lcquery = " UPDATE trhspp SET kd_skpd='"+kode+"', keperluan='"+e+"', bulan='"+d+"', no_spd='"+z+"', jns_spp='"+c+"', bank='"+g
		+"', nmrekan='"+f+"', no_rek='"+i+"', npwp='"+h+"', nm_skpd='"+j+"', tgl_spp='"+b+"', status='0', nilai='"+k+"', kd_kegiatan='"+kegi
		+"', nm_kegiatan='"+l+"', kd_program='"+m+"', nm_program='"+n+"', pimpinan='"+f1+"', no_tagih='', tgl_tagih='', sts_tagih='', no_spp='"+a+"',alamat ='"+alamat+"', kontrak='"+kontrak+"',lanjut='"+lanjut+"',tgl_mulai='"+tgl_mulai+"',tgl_akhir='"+tgl_akhir+"' where no_spp='"+a_hide+"' "; 
		
		lcquery_spm = " UPDATE trhspm SET no_spm='"+a_no_spm+"', tgl_spm='"+b_tgl_spm+"', no_spp='"+a+"', kd_skpd='"+kode+"', nm_skpd='"+j+"', tgl_spp='"+b
		+"', no_spd='"+spd+"', bulan='"+d+"', keperluan='"+e+"', username='"+usernm+"', last_update='"+last_update+"', status='0', jns_spp='"+c
		+"', nmrekan='"+f+"', npwp='"+h+"', no_rek='"+i+"', bank='"+g+"',nilai='"+k+"' where no_spm='"+a_no_spm_hide+"' ";

		// untuk sementara no_sp2d langsung di update by tox
		lcquery_sp2d = " UPDATE trhsp2d SET  no_sp2d='"+a_no_sp2d_smtr_tox+"',tgl_sp2d='"+b_tgl_sp2d+"', no_spm='"+a_no_spm+"', tgl_spm='"+b_tgl_spm+"', bulan='"+d+"', kd_skpd='"+kode
		+"', nm_skpd='"+j+"', no_spp='"+a+"', tgl_spp='"+b+"', no_spd='"+spd+"', username='"+usernm+"', status='0', last_update='"+last_update
		+"', keperluan='"+e+"', jns_spp='"+c+"', no_rek='"+i+"', bank='"+g+"',nmrekan='"+f+"',npwp='"+h+"',nilai='"+k+"' where no_sp2d='"+a_no_sp2d_hide+"' "; 

//			alert(lcquery);
//exit();
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/tukd/update_sp2d_tukd',
                data     : ({st_query:lcquery,st_query_spm:lcquery_spm,st_query_sp2d:lcquery_sp2d,tabel:'trhspp',tabel2:'trhspm',tabel3:'trhsp2d',cid:'no_spp',cid_spm:'no_spm',cid_sp2d:'no_sp2d',lcid:a,lcid_h:a_hide,lcid_spm:a_no_spm,lcid_h_spm:a_no_spm_hide,lcid_sp2d:a_no_sp2d,lcid_h_sp2d:a_no_sp2d_hide,no_smtr:a_no_sp2d_smtr_tox,sp2d_blk:beat_sp2d}),
                dataType : "json",
                success  : function(data){
                           status=data ;
                                                		
                        if ( status=='1' ){
							//alert("aaaa");
                            alert('Nomor SPP Sudah Terpakai...!!!,  Ganti Nomor SPP...!!!');
                            exit();
                        }
                        
						   if ( status=='4' ){
							//alert("aaaa");
                            alert('Nomor SPM Sudah Terpakai...!!!,  Ganti Nomor SPM...!!!');
                            exit();
                        }
						   if ( status=='5' ){
							//alert("aaaa");
                            alert('Nomor SP2D Sudah Terpakai...!!!,  Ganti Nomor SP2d...!!!');
                            exit();
                        }
						
                        if ( status=='2' ){

						detsimpan() ;
						detsimpan_pajak();
							
                            alert('Data Tersimpan...!!!');
                            lcstatus = 'edit';
							$("#no_sp2d_hide").attr("value",a_no_sp2d);
							$("#no_simpan").attr("value",a_no_sp2d);
                            exit();
                        }
                        
                        if ( status=='0' ){
                            alert('Gagal Simpan...!!!');
                            exit();
                        }
                        
                    }
            });
            });
		
		//-----------
				}
			}
		});
     });
	
	
			
			
        }
        
    }
    
    
    function dsimpan(kegiatan,rekening,nkegiatan,nrekening,nilai,sis,kd){
        var a = document.getElementById('no_spp').value;
        $jak  = eval(sis);
        $son  = eval(nilai);       
        if ($son > $jak){
            alert('nilai melebihi anggaran')
        } else {
        $(function(){      
         $.ajax({
            type     : 'POST',
            data     : ({cno_spp:a,cskpd:kode,cgiat:kegiatan,crek:rekening,ngiat:nkegiatan,nrek:nrekening,nilai:nilai,sis:sis,kd:kd}),
            dataType :"json",
            url      :"<?php echo base_url(); ?>index.php/tukd/dsimpan"            
         });
        });
        }
    } 
    
    
    function detsimpan(){

        var a         = document.getElementById('no_spp').value; 
        var kode      = $("#rek_skpd").combogrid("getValue") ;
        var cnmgiat   = document.getElementById('nm_rek_kegi').value;
        var cnobukti1 = '' ;
        var a_hide    = document.getElementById('no_spp_hide').value; 
        
        $(document).ready(function(){      
           $.ajax({
           type     : 'POST',
           url      : "<?php  echo base_url(); ?>index.php/tukd/dsimpan_hapus",
           data     : ({cno_spp:a_hide,lcid:a,lcid_h:a_hide}),
           dataType : "json",
           success  : function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Hapus Detail Old');
                            exit();
                        } 
                        }
                        });
        });
        
        
        $('#dgsppls').datagrid('selectAll');
        var rows = $('#dgsppls').datagrid('getSelections');
        
        for(var i=0;i<rows.length;i++){            
            cidx      = rows[i].idx;
            ckdgiat   = rows[i].kdkegiatan;
            ckdrek    = rows[i].kdrek5;
            cnmrek    = rows[i].nmrek5;
            cnilai    = angka(rows[i].nilai1);
                       
            no        = i + 1 ;      
            $(document).ready(function(){      
                $.ajax({
                type     : 'POST',
                url      : "<?php  echo base_url(); ?>index.php/tukd/dsimpan",
                data     : ({cno_spp:a,cskpd:kode,cgiat:ckdgiat,crek:ckdrek,ngiat:cnmgiat,nrek:cnmrek,nilai:cnilai,sis:0,kd:no,no_bukti1:cnobukti1}),
                dataType : "json"
                });
            });
        }
        $("#no_spp_hide").attr("Value",a) ;
        $('#dgsppls').edatagrid('unselectAll');
    } 
    
    
    function hapus(){				
                var spp = document.getElementById("no_spp").value;                
                var nospp =spp.split("/").join("123456789");
				var giat=getSelections();
                var rek=getSelections1();
				if (rek !=''){
				var del=confirm('Anda yakin akan menghapus rekening '+rek+' kegiatan'+giat+ ' ?');
				if  (del==true){
					$(function(){
						$('#dg1').edatagrid({
							 url: '<?php echo base_url(); ?>/index.php/tukd/thapus/'+nospp+'/'+giat+'/'+rek,
							 idField:'id',
							 toolbar:"#toolbar",              
							 rownumbers:"true", 
							 fitColumns:"true",
							 singleSelect:"true"
						});
					});
				
				}
				}
		}
          
        
        function hhapus(){				
            
            var spp = document.getElementById("no_spp").value;  
            var urll= '<?php echo base_url(); ?>/index.php/tukd/hhapus';             			    
         	if (spp !=''){
				var del=confirm('Anda yakin akan menghapus SPP '+spp+'  ?');
				if  (del==true){
					$(document).ready(function(){
                    $.post(urll,({no:spp}),function(data){
                    status = data;                        
                    });
                    });				
				}
				} 
		}
        
        
        function getSelections(idx){
			var ids = [];
			var rows = $('#dg1').edatagrid('getSelections');
			for(var i=0;i<rows.length;i++){
				ids.push(rows[i].kdkegiatan);
			}
			return ids.join(':');
		}
        
        function getSelections1(idx){
			var ids = [];
			var rows = $('#dg1').edatagrid('getSelections');
			for(var i=0;i<rows.length;i++){
				ids.push(rows[i].kdrek5);
			}
			return ids.join(':');
		}
    

    function kembali(){
        $('#kem').click();
    }                
    

    function load_sum_spp(){
		var spp = document.getElementById('no_spp').value;
        var nospp =spp.split("/").join("123456789");  	
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({spp:nospp}),
            url:"<?php echo base_url(); ?>index.php/tukd/load_sum_spp",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
					var vnilai=angka(n['rektotal']);
					var vnilai7=number_format(vnilai,2,'.',',')
                    $("#rektotal_ls").attr('value',vnilai7);
                    $("#rektotal1_ls").attr('value',vnilai7);
					});
            }
         });
        });
    }

    
     function load_sum_pot(){                
		var spm = document.getElementById('no_spm').value;              
        $(function(){      
         $.ajax({
            type      : 'POST',
            data      : ({spm:spm}),
            url       : "<?php echo base_url(); ?>index.php/tukd/load_sum_pot",
            dataType  : "json",
            success   : function(data){ 
                $.each(data, function(i,n){
                    //$("#totalrekpajak").attr("value",number_format(n['rektotal'],2,'.',','));
                    $("#totalrekpajak").attr("value",n['rektotal']);
                });
            }
         });
        });
    }
	
    function tombol(st){  
    if (st=='1'){
    $('#save').linkbutton('disable');
    $('#del').linkbutton('disable');
    $('#sav').linkbutton('disable');
    $('#dele').linkbutton('disable'); 
    $('#det').linkbutton('disable');
    document.getElementById("p1").innerHTML="Sudah di Cairkan...!!!";
    } else {
     $('#save').linkbutton('enable');
     $('#del').linkbutton('enable');
     $('#sav').linkbutton('enable');
     $('#dele').linkbutton('enable');
     $('#det').linkbutton('enable'); 
    document.getElementById("p1").innerHTML="";
    }
    }
    
    
    function tombolnew(){  
     $('#save').linkbutton('enable');
     $('#del').linkbutton('enable');
     $('#det').linkbutton('enable');     
     $('#sav').linkbutton('enable');
     $('#dele').linkbutton('enable');
    }
		
function opt(){        
		var kode = $('#skpd').combogrid('getValue') ;
		v_ats_beban = document.getElementById('ats_beban').value;  
		$('#sp').combogrid("setValue",'');
		$('#sp').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/spd1',
                                   queryParams:({kode:kode,v_ats_beban:v_ats_beban})
                                   });
		   
		}
		
		
    function cetak_spp3(){ 
            var urll= '<?php echo base_url(); ?>/index.php/tukd/cetakspp3';             			    
         	if (spp !=''){
				var del=confirm('Anda yakin akan mencetak SPP '+nomer+'  ?');
				if  (del==true){
					$(document).ready(function(){
                    $.post(urll,({no:nomer}),function(data){
                    status = data;                        
                    });
                    });				
				}
				} 
	}
        
   
   function openWindowxxx( url )
        {
        var jns = document.getElementById('jns_beban').value; 
        var no =nomer.split("/").join("123456789");
		alert(no);
        window.open(url+'/'+no+'/'+kode+'/'+jns, '_blank');
        window.focus();
        }
        
   
    function openWindow( url )
        {
       var no =kode.split("/").join("123456789");
//       alert(no);
        window.open(url+'/'+no+'/'+dns, '_blank');
        window.focus();
        }
   
   function detail(){
        var lcno = document.getElementById('no_spp').value;
            if(lcno !=''){
               section3();               
            } else {
                alert('Nomor SPP Tidak Boleh kosong')
                document.getElementById('no_spp').focus();
                exit();
            }
    }    
    
    
    function runEffect() {
        var selectedEffect = 'explode';            
        var options = {};                      
        $( "#tagih" ).toggle( selectedEffect, options, 500 );
        $("#notagih").combogrid("setValue",'');
        $("#tgltagih").attr("value",'');
        $("#nm_skpd").attr("value",'');
        $("#nil").attr("value",'');
        $("#ni").attr("value",'');
    };        
    
    
    function detail_trans_3(){
        $(function(){
//				alert(no_spp);
			$('#dgsppls').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                queryParams    : ({ spp:no_spp }),
                 idField       : 'idx',
                 toolbar       : "#toolbar",              
                 rownumbers    : "true", 
                 fitColumns    : false,
                 autoRowHeight : "false",
                 singleSelect  : "true",
                 nowrap        : "true",
                 onLoadSuccess : function(data){                      
                 },
                onSelect:function(rowIndex,rowData){
                    
                    kd          = rowIndex ;  
                    idx         =  rowData.idx ;
                    tkdkegiatan = rowData.kdkegiatan ;
                    tkdrek5     = rowData.kdrek5 ;
                    tnmrek5     = rowData.nmrek5 ;
                    tnilai1     = rowData.nilai1 ;
                                                               
                },
                 columns:[[
                     {field:'idx',
					 title:'idx',
					 width:100,
					 align:'left',
                     hidden:'true'
					 },               
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
					 width:280
					 },
                    {field:'nilai1',
					 title:'Nilai',
					 width:140,
                     align:'right'
                     },
                    {field:'hapus',title:'Hapus',width:100,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                    }
                    }
				]]	
			});
		});
        }
        
        
        function detail_kosong(){            
        var no_spp = '' ; 
        $(function(){
			$('#dgsppls').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                queryParams:({ spp:no_spp }),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"false",
                 singleSelect:"true",
                 nowrap:"true",
                 onLoadSuccess:function(data){   
                 },
                onSelect:function(rowIndex,rowData){
                kd  = rowIndex ;  
                idx =  rowData.idx ;                                           
                },
                 columns:[[
                     {field:'idx',
					 title:'idx',
					 width:100,
					 align:'left',
                     hidden:'true'
					 },               
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
					 width:280
					 },
                    {field:'nilai1',
					 title:'Nilai',
					 width:140,
                     align:'right'
                     },
                    {field:'hapus',title:'Hapus',width:100,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                    }
                    }
				]]	
			});
		});
        }
        
		
        function detail_kosong_pajak(){            
        var no_spp = '' ; 
        $(function(){
			$('#dgpajak').edatagrid({
    			     url            : '<?php echo base_url(); ?>/index.php/tukd/pot',
                     idField        : 'id',
                    // toolbar        : "#toolbar",              
                     rownumbers     : "true", 
                     fitColumns     : false,
                     autoRowHeight  : "true",
                     singleSelect   : false,
                     columns:[[
                        {field:'id',title:'id',width:100,align:'left',hidden:'true'}, 
                        {field:'kd_rek5',title:'Rekening',width:100,align:'left'},			
    					{field:'nm_rek5',title:'Nama Rekening',width:317},
    					{field:'nilai',title:'Nilai',width:250,align:"right"},
                        {field:'hapus',title:'Hapus',width:100,align:"center",
                        formatter:function(value,rec){ 
                        return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail_pajak();" />';
                        }
                        }
        			]]	
        			});
		});
        }
        
        function tambah(){
            
           var cek_kegi = $("#kg").combogrid('getValue') ;
           if ( cek_kegi == '') {
                alert('Isi Kode Kegiatan Terlebih Dahulu....!!!') ;
                exit() ;
           } 
           $("#dialog-modal-rek").dialog('open'); 
           $("#rek_skpd").combogrid("disable");
           $("#rek_kegi").combogrid("disable");
           $("#rek_kegi").combogrid("setValue",'');
           $("#nm_rek_kegi").attr("Value",'');
           $("#rek_reke").combogrid("setValue",'');
           $("#nm_rek_reke").attr("Value",'');
           
           var kegi_tmb    = $("#kg").combogrid('getValue') ;
           var nm_kegi_tmb = document.getElementById('nm_kg').value ;
           
           $("#rek_kegi").combogrid("setValue",kegi_tmb);
           $("#nm_rek_kegi").attr("Value",nm_kegi_tmb);
           
           $("#rek_nilai").attr("Value",0);
           $("#rek_nilai_ang").attr("Value",0);
           $("#rek_nilai_spp").attr("Value",0);
           $("#rek_nilai_sisa").attr("Value",0);
        
        }
        
       
       function append_save() {
        
            $('#dgsppls').datagrid('selectAll');
            var rows  = $('#dgsppls').datagrid('getSelections') ;
                jgrid = rows.length ;
        
            var jumtotal  = document.getElementById('rektotal_ls').value ;
                jumtotal  = angka(jumtotal);
        
            var vrek_skpd = $('#rek_skpd').combobox('getValue');
            var vrek_kegi = $('#rek_kegi').combobox('getValue');
            var vrek_reke = $('#rek_reke').combobox('getValue');
            var cnil      = document.getElementById('rek_nilai').value;
            var cnilai    = cnil;      
            
            
            var cnil_sisa   = angka(document.getElementById('rek_nilai_sisa').value) ;
            var cnil_sisa_spd   = angka(document.getElementById('nilai_sisa_spd').value) ;
            var cnil_input  = angka(document.getElementById('rek_nilai').value) ;
            
            if ( cnil_input > cnil_sisa ){
                 alert('Nilai Melebihi Sisa Anggaran...!!!, Cek Lagi...!!!') ;
                 exit();
            }
			 if ( cnil_input > cnil_sisa_spd){
                 alert('Nilai Melebihi Sisa SPD...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( cnil_input == 0 ){
                 alert('Nilai Nol.....!!!, Cek Lagi...!!!') ;
                 exit();
            }
            
            
            var vnm_rek_reke = document.getElementById('nm_rek_reke').value;
            
            if ( edit == 'F' ){
                pidx = pidx + 1 ;
                }
                
            if ( edit == 'T' ){
                pidx = jgrid ;
                pidx = pidx + 1 ;
                }

            $('#dgsppls').edatagrid('appendRow',{kdkegiatan:vrek_kegi,kdrek5:vrek_reke,nmrek5:vnm_rek_reke,nilai1:cnilai,idx:pidx});
            $("#dialog-modal-rek").dialog('close'); 
            
            jumtotal = jumtotal + angka(cnil) ;
			
			get_total();
            $("#rektotal_ls").attr('value',number_format(jumtotal,2,'.',','));
            $("#rektotal1_ls").attr('value',number_format(jumtotal,2,'.',','));
            $("#rektotal_ls").attr('value',number_format(jumtotal,2,'.',','));
            $("#rektotal1_ls").attr('value',number_format(jumtotal,2,'.',','));
            $("#dgsppls").datagrid("unselectAll");
            
       }
       
       
       function hapus_detail(){
        
        var a          = document.getElementById('no_spp').value;
        var rows       = $('#dgsppls').edatagrid('getSelected');
        var ctotalspp  = document.getElementById('rektotal_ls').value ;
        
        bkdrek      = rows.kdrek5;
        bkdkegiatan = rows.kdkegiatan;
        bnilai      = rows.nilai1;
        bbukti      = '';
        
        ctotalspp   = angka(ctotalspp) - angka(bnilai) ;
        
        var idx = $('#dgsppls').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, Rekening : '+bkdrek+'  Nilai :  '+bnilai+' ?');
        
        if ( tny == true ) {
            
            $('#dgsppls').datagrid('deleteRow',idx);     
            $('#dgsppls').datagrid('unselectAll');
			
            $("#rektotal_ls").attr("Value",number_format(ctotalspp,2,'.',','));
            $("#rektotal1_ls").attr("Value",number_format(ctotalspp,2,'.',','));
              
           /*   var urll = '<?php  echo base_url(); ?>index.php/tukd/dsimpan_spp';
             $(document).ready(function(){
             $.post(urll,({cnospp:a,ckdgiat:bkdkegiatan,ckdrek:bkdrek,cnobukti:bbukti}),function(data){
             status = data;
                if (status=='0'){
                    alert('Gagal Hapus..!!');
                    exit();
                } else {
                    alert('Data Telah Terhapus..!!');
                    exit();
                }
             });
             } 
			 );  */   
        }     
        }
		
		
    function append_save_pajak() {
        
            $('#dgpajak').datagrid('selectAll');
            var rows  = $('#dgpajak').datagrid('getSelections');
            jgrid     = rows.length ; 
        
            var rek_pajak    = $("#rekpajak").combogrid("getValue") ;
            var nm_rek_pajak = document.getElementById("nmrekpajak").value ;
            var nilai_pajak  = document.getElementById("nilairekpajak").value ;
            var nil_pajak    = angka(nilai_pajak);
            var dinas        = kode;  
			var vnospm       = document.getElementById('no_spm').value;
            var cket         = '0' ;
            var jumlah_pajak = document.getElementById('totalrekpajak').value ;   
                jumlah_pajak = angka(jumlah_pajak);        
            
            if ( rek_pajak == '' ){
                alert("Isi Rekening Terlebih Dahulu...!!!");
                exit();
                }
            
            if ( nilai_pajak == 0 ){
                alert("Isi Nilai Terlebih Dahulu...!!!");
                exit();
                }
            
            pidx = jgrid + 1 ;

            $('#dgpajak').edatagrid('appendRow',{kd_rek5:rek_pajak,nm_rek5:nm_rek_pajak,nilai:nilai_pajak,id:pidx});
/*             $(document).ready(function(){      
                $.ajax({
                type     : 'POST',
                url      : "<?php  echo base_url(); ?>index.php/tukd/dsimpan_pot_ar",
                data     : ({cskpd:dinas,spm:vnospm,kd_rek5:rek_pajak,nmrek:nm_rek_pajak,nilai:nil_pajak,ket:cket}),
                dataType : "json"
                });
            }); */            
            $("#rekpajak").combogrid("setValue",'');
            $("#nmrekpajak").attr("value",'');
            $("#nilairekpajak").attr("value",0);
            jumlah_pajak = jumlah_pajak + nil_pajak ;
            $("#totalrekpajak").attr('value',number_format(jumlah_pajak,2,'.',','));
            validate_rekening();
    
    }
	
	
    function hapus_detail_pajak(){
        
        var vnospm        = document.getElementById('no_spm').value;        
        var rows          = $('#dgpajak').edatagrid('getSelected');
        var ctotalpotspm  = document.getElementById('totalrekpajak').value ;
        
        bkdrek            = rows.kd_rek5;
        bnilai            = rows.nilai;
        
        var idx = $('#dgpajak').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, Rekening : '+bkdrek+'  Nilai :  '+bnilai+' ?');
        
        if ( tny == true ) {
            
            $('#dgpajak').datagrid('deleteRow',idx);     
            $('#dgpajak').datagrid('unselectAll');
           /*    
             var urll = '<?php  echo base_url(); ?>index.php/tukd/dsimpan_pot_delete_ar';
             $(document).ready(function(){
             $.post(urll,({cskpd:dinas,spm:vnospm,kd_rek5:bkdrek}),function(data){
             status = data;
                if (status=='0'){
                    alert('Gagal Hapus..!!');
                    exit();
                } else {
                    alert('Data Telah Terhapus..!!');
                    exit();
                }
             });
             }
			 ); */    
             
             ctotalpotspm = angka(ctotalpotspm) - angka(bnilai) ;
             $("#totalrekpajak").attr("Value",number_format(ctotalpotspm,2,'.',','));
             //validate_rekening();
             }     
        }
        
		
    function load_sum_pot(){                
		var spm = document.getElementById('no_spm').value;              
        $(function(){      
         $.ajax({
            type      : 'POST',
            data      : ({spm:spm}),
            url       : "<?php echo base_url(); ?>index.php/tukd/load_sum_pot",
            dataType  : "json",
            success   : function(data){ 
                $.each(data, function(i,n){
                    //$("#totalrekpajak").attr("value",number_format(n['rektotal'],2,'.',','));
                    $("#totalrekpajak").attr("value",n['rektotal']);
                });
            }
         });
        });
    }

    function detsimpan_pajak(){
        var kode      = $("#rek_skpd").combogrid("getValue") ;
        var vnospm    = document.getElementById('no_spm').value;
        $(document).ready(function(){
           $.ajax({
           type     : 'POST',
           url      : "<?php  echo base_url(); ?>index.php/tukd/dsimpan_hapus_pajak",
           data     : ({cno_spm:vnospm,kode:kode}),
           dataType : "json",
           success  : function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Hapus Detail pajak');
                            exit();
                        } 
                        }
                        });
        });
		           
        $('#dgpajak').datagrid('selectAll');
        var rows = $('#dgpajak').datagrid('getSelections');
        
        for(var i=0;i<rows.length;i++){            
            cidx      = rows[i].idx;
            rek_pajak   = rows[i].kd_rek5;
            nm_rek_pajak    = rows[i].nm_rek5;
            nilai_pajak    = angka(rows[i].nilai);
				alert(rek_pajak.substring(0,3));

//			if (rek_pajak.substring(0,3)=='213') {				
			if (Left(rek_pajak,3)=='213') {				
				jns_pot=1;

			} else{				
			jns_pot=0;
			};
			
            no        = i + 1 ;      
            $(document).ready(function(){      
                $.ajax({
                type     : 'POST',
                url      : "<?php  echo base_url(); ?>index.php/tukd/dsimpan_pajak",
				data     : ({cskpd:kode,spm:vnospm,kd_rek5:rek_pajak,nmrek:nm_rek_pajak,nilai:nilai_pajak,pot:jns_pot}),				
                dataType : "json",
				           success  : function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal SIMPAN');
                            exit();
                        } 
                        }
                });
            });
        }
		
        $('#dgpajak').edatagrid('unselectAll');
    } 
    
    function hidden_bend(){
	if (document.getElementById("ck_rekanan").checked == true)
	{

	document.getElementById("bend_nama").style.visibility = "hidden";	
	document.getElementById("lbl_bend").style.visibility = "hidden";
       $("#bend_ttd").combogrid("setValue",'');
        $("#bend_nama").attr("Value",'');	
	document.getElementById("dir").style.visibility = "visible";
	document.getElementById("lbl_rekan").style.visibility = "visible";	
	}else{
	document.getElementById("dir").style.visibility = "hidden";
	document.getElementById("lbl_rekan").style.visibility = "hidden";	
       $("#rekanan").combogrid("setValue",'');
        $("#dir").attr("Value",'');
	document.getElementById("lbl_bend").style.visibility = "visible";	
	document.getElementById("bend_nama").style.visibility = "visible";	
	};
	}
        
    function tampil_potongan () {
        
            var vnospm = document.getElementById('no_spm').value ;
        
            $(function(){
			$('#dgpajak').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/pot',
                queryParams    : ({ spm:vnospm }),
                idField       : 'id',
//                toolbar       : "#toolbar",              
                rownumbers    : "true", 
                fitColumns    : false,
                autoRowHeight : "false",
                singleSelect  : "true",
                nowrap        : "true",
      			columns       :
                     [[
                        {field:'id',title:'id',width:100,align:'left',hidden:'true'}, 
                        {field:'kd_rek5',title:'Rekening',width:100,align:'left'},			
    					{field:'nm_rek5',title:'Nama Rekening',width:317},
    					{field:'nilai',title:'Nilai',width:250,align:"right"},
                        {field:'hapus',title:'Hapus',width:100,align:"center",
                        formatter:function(value,rec){ 
                        return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail_pajak();" />';
                        }
                        }
        			]]	
                  });
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
    
    <div style="height:350px;">
    <p align="right">         
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section2();kosong();">Tambah</a>
        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a>              
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="sp2d" title="List SPP" style="width:870px;height:650px;" >  
        </table>
    </p> 
    </div>

<h3><a href="#" id="section2">Input SP2D</a></h3>
   
   <div  style="height:350px;">
   <p id="p1" style="font-size: x-large;color: red;"></p>

   <fieldset style="width:850px;height:950px;border-color:white;border-style:hidden;border-spacing:0;padding:0;">            
   <table border='1' style="font-size:11px"  >
<!--
   <tr style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;">
                <td style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;" colspan="5"><b>P E N A G I H A N </b><input type="checkbox" id="status"  onclick="javascript:runEffect();"/>
				<div id="tagih">
                        <table>
                            <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">No.Penagihan</td>
                                <td style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><input type="text" id="notagih"/></td>
                            
                                <td style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Tgl Penagihan</td>
                                <td style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><input type="text" id="tgltagih" style="width: 140px;" /></td>
								<td style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Nilai </td>
                                <td style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><input type="text" id="nil" style="width: 140px;" />
                                <input type="hidden" id="ni" style="width: 140px;" /></td>   
                            </tr>
                        </table> 
                    </div>
                
                </td>                
   </tr>
   -->
  <tr>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><i>No. Tersimpan<i></td>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><input type="text" id="no_simpan" style="border:0;width: 200px;" ;/></td>
				<td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;">&nbsp;&nbsp;</td>
				<td style="border-bottom: double 1px red;border-top: double 1px red;" colspan = "2"><i>Tidak Perlu diisi atau di Edit</i></td>                    
            </tr>
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;</td>
 </tr>  

 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >   
   <td width="8%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >No SP2D</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;<input type="text" name="no_sp2d" id="no_sp2d" onclick="javascript:select();"  style="width:250px"  /><input type="hidden" name="no_sp2d_hide" id="no_sp2d_hide" style="width:140px"/></td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Tanggal</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input id="dd_sp2d" name="dd_sp2d" type="text" /></td>   
 </tr>
 
  <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >   
   <td width="8%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >No SPM</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;<input type="text" name="no_spm" id="no_spm" onclick="javascript:select();"  style="width:250px"/><input type="hidden" name="no_spm_hide" id="no_spm_hide" style="width:140px"/></td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Tanggal</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input id="dd_spm" name="dd_spm" type="text" /></td>   
 </tr>
 
  <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >   
   <td width="8%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >No SPP</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;<input type="text" name="no_spp" id="no_spp" onclick="javascript:select();"  style="width:250px"/><input type="hidden" name="no_spp_hide" id="no_spp_hide" style="width:140px"/></td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Tanggal</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input id="dd" name="dd" type="text" /></td>   
 </tr>
 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">SKPD</td>
   <td width="53%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >     
      &nbsp;<input id="skpd" name="skpd" style="width:130px; border: 0;"/> </td>
   <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Bulan</td>
   <td width="31%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><select  name="kebutuhan_bulan" id="kebutuhan_bulan" >
     <option value="">...Pilih Kebutuhan Bulan... </option>
     <option value="1">1  | Januari</option>
     <option value="2">2  | Februari</option>
     <option value="3">3  | Maret</option>
     <option value="4">4  | April</option>
     <option value="5">5  | Mei</option>
     <option value="6">6  | Juni</option>
     <option value="7">7  | Juli</option>
     <option value="8">8  | Agustus</option>
     <option value="9">9  | September</option>
     <option value="10">10 | Oktober</option>
     <option value="11">11 | November</option>
     <option value="12">12 | Desember</option>
   </select></td> 
 </tr>
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
			<textarea name="nm_skpd" id="nm_skpd" cols="60" rows="5" style="border: 0;"  ></textarea></td>
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Keperluan</td>
   <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><textarea name="ketentuan" id="ketentuan" cols="30" rows="1" ></textarea></td>
 </tr>
  <tr>
   <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Atas Beban</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >&nbsp;
   <select name="ats_beban" id="ats_beban" onchange="opt();" style="height: 27px; width: 190px;">
     <option value="51">Belanja Tidak Langsung</option>     
     <option value="52">Belanja Langsung</option>
	<td width="110px" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" ><p id="lbl_bend">Bendahara:</p></td>
            <td  width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><input id="bend_ttd" name="bend_ttd" style="width:200px"/>
			<input id="bend_nama" name="bend_nama" style="width:190px" readonly="true"/></td>
	 </select>
   </td>
 </tr>
 <tr>
   <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">No SPD</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;
   <input id="sp" name="sp" style="width:300px" />&nbsp;<input id="tglspd" name="tglspad" type="text" style="width:100px"/></td></td>
   <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" align='center'>
   <input type="checkbox" id="ck_rekanan" name="ck_rekanan" onClick="hidden_bend()" /><br><p id="lbl_rekan">Rekanan/Dir.</p></td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><input id="rekanan" name="rekanan" style="width:190px"/>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   <input id="dir" name="dir" style="width:190px"/></td>
 </tr>
 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Kegiatan</td>
   <td colspan="3" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" >&nbsp;<input id="kg" name="kg" style="width:190px" />
   &nbsp;<input type ="hidden" id="kp" name="kp" style="width:160px" />
    &nbsp;&nbsp;<input id="nm_kg" name="nm_kg" style="width:500px;border: 0;"/>
      <input type ="hidden" id="nm_kp" name="nm_kp" /></td>
 </tr>
 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Beban</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><select name="jns_beban" id="jns_beban" style="height: 27px; width: 190px;">
     <option value="">...Pilih Jenis Beban... </option>     
     <option value="3">TU</option>
    
     <option value="5">LS PPKD</option>
     <option value="6">LS Barang Jasa</option>
   </td>
   <td width="8%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >BANK</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input type="text" name="bank1" id="bank1" />
    &nbsp;<input type ="input"  style="border:hidden" id="nama_bank" name="nama_bank" style="width:150" /></td>
 
 </tr>
 
 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">NPWP</td>
   <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;<input type="text" name="npwp" id="npwp" value="" style="width:190px"/></td>
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Rekening</td>
   <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input type="text" name="rekening" id="rekening"  value="" style="width:190px"/></td>
 </tr>
 
 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Alamat Perusahaan</td>
   <td width='92%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   &nbsp;<textarea name="alamat" id="alamat" cols="60" rows="2" ></textarea></td>
   <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Tanggal Mulai/Akhir</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><input id="tgl_mulai" name="tgl_mulai" style="width:190px"/>
   &nbsp;
   <input id="tgl_akhir" name="tgl_akhir" style="width:190px"/>
 </tr>
 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Lanjut</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><select name="lanjut" id="lanjut" style="height: 27px; width: 190px;">
     <option value="">...Pilih ... </option>     
     <option value="1">IYA</option>
     <option value="2">TIDAK</option>
   </td>
   <td width="8%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >Nomor Kontrak</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input type="text" name="kontrak" id="kontrak" />
   </td>
 
 </tr>

     <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
       <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
       <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
       <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
       <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;</td>
     </tr>  

       <tr style="border-spacing: 3px;padding:3px 3px 3px 3px;">
                <td colspan="4" align='right' style="border-bottom-color:black;border-spacing: 3px;padding:3px 3px 3px 3px;" >
                <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Baru</a>
                <a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true"  onclick="javascript:hsimpan();">Simpan</a>
                <a id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hhapus();javascript:section1();">Hapus</a>
                <!--<a id="det" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:detail();">Detail</a>-->
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>
                <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a></td>                
       </tr>
</table>

    
        <!------------------------------------------------------------------------------------------------------------------>
        
        <table id="dgsppls" title="Input Detail SP2D" style="width:850%;height:300%;" > 		
        </table>
        
        <div id="toolbar" align="left">
    		<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Tambah Rekening</a>
        </div>
  
        <table border='0' style="width:100%;height:5%;"> 
             <td width='39%'></td>
             <td width='15%'><input class="right" type="hidden" name="rektotal1_ls" id="rektotal1_ls"  style="width:140px" align="right"  ></td>
             <td width='9%'><B>Total</B></td>
             <td width='32%'><input class="right" type="text" name="rektotal_ls" id="rektotal_ls"  style="width:140px" align="right"  ></td>
        </table>
		
		
	<!--dari sini -->
	
	 <fieldset>
       <table border='0' style="font-size:11px"> 
           
           <tr>
                <td>Rekening Potongan</td>
                <td>:</td>
                <td><input type="text" id="rekpajak"   name="rekpajak" style="width:200px;"/></td>
                <td><input type="text" id="nmrekpajak" name="nmrekpajak" style="width:400px;border:0px;"/></td>
           </tr>
           <tr>
                <td align="left">Nilai</td>
                <td>:</td>
                <td><input type="text" id="nilairekpajak" name="nilairekpajak" style="width:200px;text-align:right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
                <td></td>
           </tr>
           <tr>
             <td colspan="4" align="center" > 
                 <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:append_save_pajak();" >Simpan</a>
                 <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();" >Kembali</a>
             </td>
           </tr>
       </table>
       </fieldset>
       
      &nbsp;&nbsp; 
       
       <table id="dgpajak" title="List Potongan" style="width:850px;height:300px;">  
       </table>   
       
       <table border='0' style="font-size:11px;width:850px;height:30px;"> 
           <tr>
                <td width='50%'></td>
                <td width='20%' align="right">Total</td>
                <td width='30%'><input type="text" id="totalrekpajak" name="totalrekpajak" style="width:250px;text-align:right;"/></td>
           </tr>
       </table>
	
	
	<!--Sampai sini -->
	
		
        </fieldset>
		
		
        <!------------------------------------------------------------------------------------------------------------------>
   </div>

</div>
</div> 

<div id="dialog-modal" title="CETAK SP2D">
    <p class="validateTips">SILAHKAN PILIH SP2D</p>  
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
    </table>  
    </fieldset>
    <div>
    </div>    
	<a href="<?php echo site_url(); ?>/tukd/cetak_sp2d/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:openWindow(this.href);return false;">Cetak Pdf</a>
	<br/>
	<a href="<?php echo site_url(); ?>/tukd/cetak_sp2d/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:openWindow(this.href);return false;">Cetak</a>
	&nbsp;&nbsp;&nbsp;<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>  
</div>


<div id="dialog-modal-rek" title="Input Rekening">
    <p class="validateTips"></p>  
    <fieldset>
    <table align="center" style="width:100%;" border="0">
       
            <tr>
                <td width='15%'>SKPD</td>
                <td width='3%'>:</td>
                <td colspan="4" width='82%'><input id="rek_skpd" name="rek_skpd" style="width: 200px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="rek_nmskpd" style="border:0;width: 300px;"  readonly="true"/></td>                            
            </tr>

            <tr>
                <td>KEGIATAN</td>
                <td>:</td>
                <td colspan="4"><input id="rek_kegi" name="rek_kegi" style="width: 200px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="nm_rek_kegi" style="border:0;width: 300px;"  readonly="true"/></td>                            
            </tr>

            <tr>
                <td>REKENING</td>
                <td>:</td>
                <td colspan="4"><input id="rek_reke" name="rek_reke" style="width: 200px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="nm_rek_reke" style="border:0;width: 300px;"  readonly="true"/></td>                            
            </tr>

            <tr>
                <td>TOTAL SPD</td>
                <td>:</td>
                <td><input type="text" id="total_spd" style="width: 196px; text-align: right; "  readonly="true" /></td> 
				<td>ANGGARAN</td>
                <td>:</td>
                <td><input type="text" id="rek_nilai_ang" style="width: 196px; text-align: right; "  readonly="true" /></td> 

            </tr>
            
            <tr>
                <td>SPD TERPAKAI</td>
                <td>:</td>
                <td><input type="text" id="nilai_spd_lalu" style="width: 196px; text-align: right; " readonly="true"/></td> 
                <td>JUMLAH SPP LALU</td>
                <td>:</td>
                <td><input type="text" id="rek_nilai_spp" style="width: 196px; text-align: right; " readonly="true"/></td> 
            </tr>

            <tr>
                <td>SISA SPD</td>
                <td>:</td>
                <td><input type="text" id="nilai_sisa_spd" style="width: 196px; text-align: right; "  readonly="true"/></td>
				<td>SISA ANGGARAN</td>
                <td>:</td>
                <td><input type="text" id="rek_nilai_sisa" style="width: 196px; text-align: right; "  readonly="true" /></td>
            </tr>

            <tr>
                <td>NILAI</td>
                <td>:</td>
                <td><input type="text" id="rek_nilai" style="background-color:#99FF99; width: 196px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td> 
            </tr>
            
            <tr>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td> 
            </tr>
            
            <tr>
                <td colspan="3" align="center">
                <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:append_save();">Simpan</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar_rek();">Keluar</a>  
                </td>
            </tr>
            
    </table>
	
    </fieldset>
	
</div>
</body>
</html>