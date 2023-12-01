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
                    
    $(document).ready(function() {
            $("#accordion").accordion({
            height: 500
            });
            $("#lockscreen").hide();                        
            $("#frm").hide();
            $( "#dialog-modal" ).dialog({
            height: 180,
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
			get_tahun();
			get_username();
      });
    
        
        $(function(){
       	     $('#dd').datebox({  
                required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return y+'-'+m+'-'+d;
                }, onSelect: function(date){
            	var m = date.getMonth()+1;
					$("#kebutuhan_bulan").attr('value',m);
				}
            });
			
			/* $('#tgl_mulai').datebox({  
                required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return y+'-'+m+'-'+d;
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
            }); */
			
			$('#tgl_ttd').datebox({  
                required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return y+'-'+m+'-'+d;
                }
            });
			
			$('#rekanan').combogrid({  
                panelWidth:200,  
                url: '<?php echo base_url(); ?>/index.php/xtukd_ppkd/perusahaan',  
                    idField:'nmrekan',  
                    textField:'nmrekan',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'nmrekan',title:'Perusahaan',width:40} 
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    //$("#kode").attr("value",rowData.kode);
                    $("#dir").attr("value",rowData.pimpinan);
                    $("#npwp").attr("value",rowData.npwp);
                    $("#alamat").attr("value",rowData.alamat);
					
                    }   
                });

		
			 $('#tglspd').datebox({  
                required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return y+'-'+m+'-'+d;
                }
            });
                
                
                $('#cspp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/spp/load_spp',  
                    idField:'no_spp',                    
                    textField:'no_spp',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_spp',title:'SPP',width:60},  
                        {field:'kd_skpd',title:'SKPD',align:'left',width:60},
                        {field:'tgl_spp',title:'Tanggal',width:60} 
                    ]],
                    onSelect:function(rowIndex,rowData){
                    nomer = rowData.no_spp;
                    kode = rowData.kd_skpd;
                    jns = rowData.jns_spp;
                    }   
                });
                
                /* $('#cc').combobox({
					url:'<?php echo base_url(); ?>/index.php/xtukd_ppkd/load_jenis_beban',
					valueField:'id',
					textField:'text',
					onSelect:function(rowIndex,rowData){
					validate_tombol();
                    }
				}); */
								
				
				$('#ttd1').combogrid({  
					panelWidth:600,  
					idField:'nip',  
					textField:'nip',  
					mode:'remote',
					url:'<?php echo base_url(); ?>index.php/xtukd_ppkd/load_ttd/BK',  
					columns:[[  
						{field:'nip',title:'NIP',width:200},  
						{field:'nama',title:'Nama',width:400}    
					]],
                    onSelect:function(rowIndex,rowData){
                    $("#nmttd1").attr("value",rowData.nama);
                    }  
				});          
        
				$('#ttd2').combogrid({  
					panelWidth:600,  
					idField:'nip',  
					textField:'nip',  
					mode:'remote',
					url:'<?php echo base_url(); ?>index.php/xtukd_ppkd/load_ttd/PPK',  
					columns:[[  
						{field:'nip',title:'NIP',width:200},  
						{field:'nama',title:'Nama',width:400}    
					]],
                    onSelect:function(rowIndex,rowData){
                    $("#nmttd2").attr("value",rowData.nama);
                    }  
  
				});
				
				$('#ttd3').combogrid({  
					panelWidth:600,  
					idField:'nip',  
					textField:'nip',  
					mode:'remote',
					url:'<?php echo base_url(); ?>index.php/xtukd_ppkd/load_ttd/PA',  
					columns:[[  
						{field:'nip',title:'NIP',width:200},  
						{field:'nama',title:'Nama',width:400}    
					]],
                    onSelect:function(rowIndex,rowData){
                    $("#nmttd3").attr("value",rowData.nama);
                    }  
  
				});
				
				$('#ttd4').combogrid({  
					panelWidth:600,  
					idField:'nip',  
					textField:'nip',  
					mode:'remote',
					url:'<?php echo base_url(); ?>index.php/xtukd_ppkd/load_ttd/PPKD',  
					columns:[[  
						{field:'nip',title:'NIP',width:200},  
						{field:'nama',title:'Nama',width:400}    
					]],
                    onSelect:function(rowIndex,rowData){
                    $("#nmttd4").attr("value",rowData.nama);
                    }  
  
				});
				
                $('#notagih').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/xtukd_ppkd/load_no_penagihan',  
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
                    $("#ketentuan").attr("value",rowData.ket);
                    $("#ni").attr("value",rowData.nil);
					detail_tagih(no_tagih);
					$("#rektotal_ls").attr('value',rowData.nila);
                    $("#rektotal1_ls").attr('value',rowData.nil);
					//get_skpd();
                    }   
                });
                   
			$('#bank1').combogrid({  
                panelWidth:200,  
                url: '<?php echo base_url(); ?>/index.php/spp/config_bank2',  
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
                    
                    
                    $('#spp').edatagrid({
					rowStyler:function(index,row){
            if (row.sts_setuju==1){
                return 'background-color:#4bbe68;color:white';
              }
					},	
            		url: '<?php echo base_url(); ?>/index.php/tukd/load_pengesahan_spp',
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
                	    {field:'no_spp',
                		title:'Nomor SPP',
                		width:120},
                        {field:'tgl_spp',
                		title:'Tanggal',
                		width:40},
                        {field:'kd_skpd',
                		title:'Nama SKPD',
                		width:30,
                        align:"left"},
                        {field:'keperluan',
                		title:'Keterangan',
                		width:90,
                        align:"left"}
                    ]],
                    onSelect:function(rowIndex,rowData){
                      no_spp   = rowData.no_spp;         
                      kode     = rowData.kd_skpd;
					  nmskpd   = rowData.nm_skpd;
                      sp       = rowData.no_spd;          
                      bl       = rowData.bulan;
                      tg       = rowData.tgl_spp;
                      jn       = rowData.jns_spp;
                      jns_bbn  = rowData.jns_beban;
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
                      sts_setuju  = rowData.sts_setuju;  
                      get(no_spp,kode,nmskpd,sp,tg,bl,jn,kep,np,rekan,bk,ning,status,kegi,nm,kprog,nprog,dir,notagih,tgltagih,sttagih,alamat,kontrak,lanjut,tgl_mulai,tgl_akhir,jns_bbn,sts_setuju);
                      det();       
                      detail_trans_3();   
                      validate_kegiatan() ;
                      load_sum_spp(); 
                      edit = 'T' ;
                      lcstatus = 'edit';
                    },
                    onDblClickRow:function(rowIndex,rowData){
                        section2();   
                    }
                });
                
                
                
                $('#sp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/spp/spd1',  
                    idField:'no_spd',  
                    textField:'no_spd',
                    mode:'remote',  
                    fitColumns:true,                    
                    columns:[[  
                        {field:'no_spd',title:'No SPD',width:70},  
                        {field:'tgl_spd',title:'Tanggal',align:'left',width:30}                          
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
                url: '<?php echo base_url(); ?>/index.php/spp/kegi',  
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
                    det();                                                        
                    }    
                });
                
                
                $('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/xtukd_ppkd/select_data2',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true",
			});
            
                
                $('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/spp/select_data1',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true",
			});
            
            
                $('#dgsppls').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/spp/select_data1',
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"false",
                 singleSelect:"true",
                 nowrap:"false",
                 columns:[[
                    {field:'idx',title:'idx',width:100,align:'left',hidden:'true'},               
                    {field:'kdsubkegiatan',title:'Kegiatan',width:150,align:'left'},
					{field:'kdrek6',title:'Rekening',width:70,align:'left'},
					{field:'nmrek6',title:'Nama Rekening',width:280},
                    {field:'nilai1',title:'Nilai',width:140,align:'right'},
                    {field:'hapus',title:'Hapus',width:100,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                    }
                    }
				]]	
           }); 
            
           
           $('#rek_skpd').combogrid({  
           panelWidth:700,  
           idField:'kd_skpd',  
           textField:'kd_skpd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/spp/skpd_2',  
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

        });
        
        
        function get_skpd()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
  								      $("#dn").attr("value",data.kd_skpd);
        							  $("#nmskpd").attr("value",data.nm_skpd);
                                      $("#rek_skpd").combogrid("setValue",data.kd_skpd);
                                      $("#rek_nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
                                      kode = data.kd_skpd;
                                      //validate_spd(kode);
        	}  
            });
        }
	    function get_tahun() {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/spp/config_tahun',
				
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        			tahun_anggaran = data;
        			}                                     
        	});
             
        }
        
        function detail_tagih(no_tagih){
		//alert("aaa");
        $(function(){
			$('#dgsppls').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/xtukd_ppkd/select_data_tagih',
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
                    tkdsubkegiatan = rowData.kdsubkegiatan ;
                    tkdrek6     = rowData.kdrek6 ;
                    tnmrek6     = rowData.nmrek6 ;
                    tnilai1     = rowData.nilai1 ;
                                                               
                },
                 columns:[[
                     {field:'idx',
					 title:'idx',
					 width:100,
					 align:'left',
                     hidden:'true'
					 },               
                     {field:'kdsubkegiatan',
					 title:'Sub Kegiatan',
					 width:180,
					 align:'left'
					 },
					{field:'kdrek6',
					 title:'Rekening',
					 width:70,
					 align:'left'
					 },
					{field:'nmrek6',
					 title:'Nama Rekening1',
					 width:280
					 },
                    {field:'nilai1',
					 title:'Nilai',
					 width:140,
                     align:'right'
                     }
				]]	
			});
		});
        }
		
        function validate_kegiatan(){
            
            var kode_s = document.getElementById('dn').value;
            $(function(){
              $('#rek_kegi').combogrid({  
              panelWidth:700,  
              idField   :'kd_kegiatan',  
              textField :'kd_kegiatan',  
              mode      :'remote',
              url       :'<?php echo base_url(); ?>index.php/spp/load_trskpd_ar_2',  
              queryParams:({kdskpd:kode_s}), 
              columns   :[[  
               {field:'kd_kegiatan',title:'Kode Kegiatan',width:150},  
               {field:'nm_kegiatan',title:'Nama Kegiatan',width:700}    
               ]],
               onSelect:function(rowIndex,rowData){      
               $("#nm_rek_kegi").attr("value",rowData.nm_kegiatan); 
               $("#rek_reke").combogrid("setValue",''); 
			   
			   //kd_kegia = rowData.kd_kegiatan;
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
           rek5 = rows[p].kdrek6;                                       
           if ( p > 0 ){   
                  frek = frek+','+rek5;
              } else {
                  frek = rek5;
              }
           }
         
                var beban   = document.getElementById('jns_beban').value;
                var kode_s   = document.getElementById('dn').value  ;
                var kode_keg = $('#rek_kegi').combogrid('getValue') ;
                var nospp    = document.getElementById('no_spp').value ;
                
                $(function(){
                  $('#rek_reke').combogrid({  
                  panelWidth:700,  
                  idField   :'kd_rek5',  
                  textField :'kd_rek5',  
                  mode      :'remote',
                  url       :'<?php echo base_url(); ?>index.php/xtukd_ppkd/load_rek_ar',  
                  queryParams:({kdsubkegiatan:kode_keg,kdrek:frek}), 
                  columns:[[  
                   {field:'kd_rek5',title:'Kode Rekening',width:150},  
                   {field:'nm_rek5',title:'Nama Rekening',width:700}    
                   ]],
                   onSelect:function(rowIndex,rowData){      
                   
                           $("#nm_rek_reke").attr("value",rowData.nm_rek5); 
                           var koderek = rowData.kd_rek5 ;
                   
                           $.ajax({
                                type     : "POST",
                        		dataType : "json",   
                                data     : ({kegiatan:kode_keg,kdrek6:koderek,kd_skpd:kode_s,no_spp:nospp}), 
                        		url      : '<?php echo base_url(); ?>index.php/xtukd_ppkd/jumlah_ang_spp',
                        		success  : function(data){
                        		      $.each(data, function(i,n){
                                        $("#rek_nilai_ang").attr("Value",n['nilai']);
                                        $("#rek_nilai_spp").attr("Value",n['nilai_spp_lalu']);
                                        
                                        var n_ang  = n['nilai'] ;
                                        var n_spp  = n['nilai_spp_lalu'] ;
                                        var n_sisa = angka(n_ang) - angka(n_spp) ;
                                        $("#rek_nilai_sisa").attr("Value",number_format(n_sisa,2,'.',','));
										
										var tgl_spd   = $('#tglspd').datebox('getValue');      
								 $.ajax({
											type     : "POST",
											dataType : "json",   
											data     : ({kegiatan:kode_keg,kd_skpd:kode_s,tglspd:tgl_spd,kdrek6:koderek,beban:beban}), 
											url      : '<?php echo base_url(); ?>index.php/xtukd_ppkd/total_spd',
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
											data     : ({kegiatan:kode_keg,kd_skpd:kode_s,kdrek6:koderek,kdrek6:koderek,beban:beban}), 
											url      : '<?php echo base_url(); ?>index.php/xtukd_ppkd/pakai_spd',
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
                                    });
        						}                                     
        	               });
                   }                
               });
               });
               $('#dgsppls').datagrid('unselectAll');
        }
           
        
        
       

        
    function validate_spd(kode){
           $(function(){
            $('#sp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/spp/spd1/'+kode,  
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
                url: '<?php echo base_url(); ?>/index.php/spp/kegi',
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
				url: '<?php echo base_url(); ?>/index.php/xtukd_ppkd/select_data2',
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

        
        function det_baru(){   
	   	  var kegi='';
          $(function(){            
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/xtukd_ppkd/select_data2',
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
				url: '<?php echo base_url(); ?>/index.php/spp/select_data1',
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
								kegiatan=rowData.kdsubkegiatan;
                                nkegiatan=rowData.nmkegiatan;
								rekeing=rowData.kdrek6;
                                nrekeing=rowData.nmrek6;
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
                     {field:'kdsubkegiatan',
					 title:'Kegiatan',
					 width:150,
					 align:'left'
					},
					{field:'kdrek6',
					 title:'Rekening',
					 width:70,
					 align:'left'
					},
					{field:'nmrek6',
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


     function get(no_spp,kd_skpd,nmskpd,no_spd,tgl_spp,bulan,jns_spp,keperluan,npwp,rekanan,bank,rekening,status,giat,nmgiat,prog,nmprog,pim,notagih,tgltagih,ststagih,alamat,kontrak,lanjut,tgl_mulai,tgl_akhir,jns_bbn,sts_setuju){

		$("#no_spp").attr("value",no_spp);
        $("#no_spp_hide").attr("value",no_spp);
		$("#no_simpan").attr("value",no_spp);
        $("#sp").combogrid("setValue",no_spd);
        $("#dd").datebox("setValue",tgl_spp);
  		$("#dn").attr("value",kd_skpd);
		$("#nmskpd").attr("value",nmskpd);
		// $("#tgl_mulai").datebox("setValue",tgl_mulai);
        //$("#tgl_akhir").datebox("setValue",tgl_akhir);
        $("#kebutuhan_bulan").attr("Value",bulan);
        $("#ketentuan").attr("Value",keperluan);
        $("#jns_beban").attr("Value",jns_spp);
        $("#npwp").attr("Value",npwp);
        $("#rekanan").combogrid("setValue",rekanan);
        $("#dir").attr("Value",pim);
        $("#alamat").attr("Value",alamat);
        //$("#kontrak").attr("Value",kontrak);
        //$("#lanjut").attr("Value",lanjut);
        $("#bank1").combogrid("setValue",bank);
        $("#rekening").attr("Value",rekening);
        $("#kg").combogrid("setValue",giat);
        $("#nm_kg").attr("Value",nmgiat);
        $("#kp").attr("setValue",prog);
        $("#nm_kp").attr("Value",nmprog);
        $("#notagih").combogrid("setValue",notagih);        
        $("#tgltagih").attr("Value",tgltagih);
		//validate_jenis_edit(jns_bbn);
		//validate_tombol();
        $("#status").attr("checked",false);                  
        if (ststagih==1){            
            $("#status").attr("checked",true);
            $("#tagih").show();
        } else {
            $("#status").attr("checked",false);
            $("#tagih").hide();
        }
         
		 tombol(status,sts_setuju);           
        }
    
    function kosong(){
		$("#notagih").combogrid("setValue",'');
		$("#tgltagih").attr("value",'');
		$("#nil").attr("value",'0');
		$("#status").attr("checked",false);
        $("#tagih").hide();
        $("#no_spp").attr("value",'');
        $("#no_spp_hide").attr("value",'');
        $("#no_simpan").attr("value",'');
        $("#sp").combogrid("setValue",'');
        $("#dd").datebox("setValue",'');
        //$("#tgl_mulai").datebox("setValue",'');
        //$("#tgl_akhir").datebox("setValue",'');
        $("#tglspd").datebox("setValue",'');
        $("#kebutuhan_bulan").attr("Value",'');
        $("#ketentuan").attr("Value",'');
        $("#jns_beban").attr("Value",'5');
        $("#npwp").attr("Value",'');
        $("#rekanan").combogrid("setValue",'');
        $("#dir").attr("Value",'');
        $("#bank1").combogrid("setValue",'');
        $("#rekening").attr("Value",'');
        $("#kg").combogrid("setValue",'');
        $("#nm_kg").attr("Value",'');
        $("#kg").combogrid("setValue",'');
        $("#nm_kg").attr("Value",'');
        $("#nama_bank").attr("Value",'');
        //$("#kontrak").attr("Value",'');
        //$("#lanjut").attr("Value",'');
        $("#alamat").attr("Value",'');
        $("#kp").attr("setValue",'');
        $("#nm_kp").attr("Value",'');
        document.getElementById("p1").innerHTML="";        
        $("#sp").combogrid("clear");
        $("#kg").combogrid("clear");
		$("#cc").attr("setValue",'3');

        det_baru();
        tombolnew();
        detail_kosong(); 
        validate_kegiatan();  

        var pidx  = 0   ;     
        edit      = 'F' ;
        
        $("#rektotal_ls").attr("Value",0);
        $("#rektotal1_ls").attr("Value",0);
        
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
            url:'<?php echo base_url(); ?>/index.php/xtukd_ppkd/tsimpan'
         });
        });
		}       
        
       
    function cetak(){
        var nom=document.getElementById("no_spp").value;
        $("#cspp").combogrid("setValue",nom);
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
            $('#spp').edatagrid({
	       url: '<?php echo base_url(); ?>/index.php/tukd/load_pengesahan_spp',
         queryParams:({cari:kriteria})
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
			$('#top_img').click();			 
         });
    }
     
     
    function section3(){
         $(document).ready(function(){    
             $('#section3').click();                                               
         });
    }

     
    function hsimpan(){        
        
        var a       = document.getElementById('no_spp').value;
        var a_hide  = document.getElementById('no_spp_hide').value;
        var b       = $('#dd').datebox('getValue');      
        var c       = document.getElementById('jns_beban').value; 
        var d       = document.getElementById('kebutuhan_bulan').value;
        var e       = document.getElementById('ketentuan').value;
        var f       = $("#rekanan").combogrid("getValue") ; 
        var f1      = document.getElementById('dir').value;
        var g       = $("#bank1").combogrid("getValue") ; 
        var h       = document.getElementById('npwp').value;
        var i       = document.getElementById('rekening').value;
        var j       = document.getElementById('nmskpd').value;
        var k1       = document.getElementById('rektotal1_ls').value;
        var l       = document.getElementById('nm_kg').value;
        var m       = document.getElementById('kp').value;
        var n       = document.getElementById('nm_kp').value;
        var alamat       = document.getElementById('alamat').value;
        var kontrak      = '';//document.getElementById('kontrak').value;
        var lanjut       = '';//document.getElementById('lanjut').value;
        var tgl_mulai    = '';//$('#tgl_mulai').datebox('getValue');      
        var tgl_akhir    = '';//$('#tgl_akhir').datebox('getValue');      
        var o       = document.getElementById('status').checked; 
        var jenis   = document.getElementById('cc').value;
        var z       = $("#sp").combogrid("getValue") ; 
        var y       = $("#kg").combogrid("getValue") ; 
        var k 		= angka(k1);       
		var last_update =  tox_tanggal();
        if ( o == false ){
           o=0;
        }else{
            o=1;
        }
        if ( a == '' ){
            alert("Isi Nomor SPP Terlebih Dahulu...!!!") ;
            exit();
        }
        
        if ( b == '' ){
            alert("Isi Tanggal Terlebih Dahulu...!!!") ;
            exit();
        }
		var tahun_input = b.substring(0, 4);
		if (tahun_input != tahun_anggaran){
			alert('Tahun tidak sama dengan tahun Anggaran');
			exit();
		}
        
        if ( c == '' ){
            alert("Isi Beban Terlebih Dahulu...!!!") ;
            exit();
        }
        
        if ( d == '' ){
            alert("Isi Kebutuhan Bulan Terlebih Dahulu...!!!") ;
            exit();
        }
        
        if ( p == '' ){
            alert("Isi Nomor SPD Terlebih Dahulu...!!!") ;
            exit();
        }
        
        if ( q == '' ){
            alert("Isi Kode Kegiatan Terlebih Dahulu...!!!") ;
            exit();
        }
		if ( jenis == '' ){
            alert("Isi Jenis Beban Terlebih Dahulu...!!!") ;
            exit();
        }

        var p = $('#notagih').combogrid('getValue');
        var q = document.getElementById('tgltagih').value; 
        
		if(lcstatus == 'tambah'){
		$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:a,tabel:'trhspp',field:'no_spp'}),
                    url: '<?php echo base_url(); ?>/index.php/xtukd_ppkd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1){
						alert("Nomor Telah Dipakai!");
						document.getElementById("nomor").focus();
						exit();
						} 
						if(status_cek==0){
						//alert("Nomor Bisa dipakai");
		
		//---------
		
            lcinsert = "(no_spp,  kd_skpd,    keperluan, bulan,   no_spd,    jns_spp, jns_beban, bank,    nmrekan,  no_rek,  npwp,    nm_skpd,  tgl_spp, status, username,     last_update,   nilai,    no_bukti,     kd_kegiatan,  nm_kegiatan,  kd_program,  nm_program,  pimpinan,  no_tagih,    tgl_tagih,  sts_tagih, no_bukti2, no_bukti3, no_bukti4, no_bukti5, no_spd2, no_spd3, no_spd4 , alamat, kontrak, lanjut, tgl_mulai, tgl_akhir)"; 
            lcvalues = "('"+a+"', '"+kode+"', '"+e+"',   '"+d+"', '"+spd+"', '"+c+"', '"+jenis+"', '"+g+"', '"+f+"',  '"+i+"', '"+h+"', '"+j+"',  '"+b+"', '0','"+usernm+"','"+last_update+"', '"+k+"',  '',           '"+y+"',   '"+l+"',      '"+m+"',     '"+n+"',     '"+f1+"',  '"+p+"',     '"+q+"',    '"+o+"',    '',       '',        '',        '',        '',      '',      '',      '"+alamat+"', '"+kontrak+"','"+lanjut+"','"+tgl_mulai+"','"+tgl_akhir+"' )";
            //lcupdate = " UPDATE trhtagih SET sts_tagih='1' where no_bukti='"+p+"' "; 
			
            $(document).ready(function(){
                $.ajax({
                    type     : "POST",
                    url      : '<?php echo base_url(); ?>/index.php/xtukd_ppkd/simpan_tukd',
                    data     : ({tabel:'trhspp',kolom:lcinsert,nilai:lcvalues,cid:'no_spp',lcid:a,tagih:p}),
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
                                  alert('Data Tersimpan..!!');
								   $("#no_spp_hide").attr("value",a);
									 $("#no_simpan").attr("value",a);
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
			$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:a,tabel:'trhspp',field:'no_spp'}),
                    url: '<?php echo base_url(); ?>/index.php/xtukd_ppkd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1 && a!=a_hide){
						alert("Nomor Telah Dipakai!");
						exit();
						} 
						if(status_cek==0 || a==a_hide){
						//alert("Nomor Bisa dipakai");
			
			
		//---------
		lcquery = " UPDATE trhspp SET kd_skpd='"+kode+"', keperluan='"+e+"', bulan='"+d+"', no_spd='"+z+"', jns_spp='"+c+"',jns_beban='"+jenis+"', bank='"+g+"', nmrekan='"+f+"', no_rek='"+i+"', npwp='"+h+"', nm_skpd='"+j+"', tgl_spp='"+b+"', status='0', nilai='"+k+"', kd_kegiatan='"+kegi+"', nm_kegiatan='"+l+"', kd_program='"+m+"', nm_program='"+n+"', pimpinan='"+f1+"', no_tagih='"+p+"', tgl_tagih='"+q+"', sts_tagih='"+o+"', no_spp='"+a+"',alamat ='"+alamat+"', kontrak='"+kontrak+"',lanjut='"+lanjut+"',tgl_mulai='"+tgl_mulai+"',tgl_akhir='"+tgl_akhir+"' where no_spp='"+a_hide+"' "; 

//			alert(lcquery);
//exit();
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/xtukd_ppkd/update_tukd',
                data     : ({st_query:lcquery,tabel:'trhspp',cid:'no_spp',lcid:a,lcid_h:a_hide}),
                dataType : "json",
                success  : function(data){
                           status=data ;
                                                		
                        if ( status=='1' ){
							//alert("aaaa");
                            alert('Nomor SPP Sudah Terpakai...!!!,  Ganti Nomor SPP...!!!');
                            exit();
                        }
                        
                        if ( status=='2' ){

						detsimpan() ;
							
                            alert('Data Tersimpan...!!!');
                            lcstatus = 'edit';
							$("#no_spp_hide").attr("value",a);
							$("#no_simpan").attr("value",a);
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
            url      :"<?php echo base_url(); ?>index.php/xtukd_ppkd/dsimpan"            
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
           url      : "<?php  echo base_url(); ?>index.php/xtukd_ppkd/dsimpan_hapus",
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
            ckdgiat   = rows[i].kdsubkegiatan;
            ckdrek    = rows[i].kdrek6;
            cnmrek    = rows[i].nmrek6;
            cnilai    = angka(rows[i].nilai1);
                       
            no        = i + 1 ;      
            $(document).ready(function(){      
                $.ajax({
                type     : 'POST',
                url      : "<?php  echo base_url(); ?>index.php/xtukd_ppkd/dsimpan",
                data     : ({cno_spp:a,cskpd:kode,cgiat:ckdgiat,crek:ckdrek,ngiat:cnmgiat,nrek:cnmrek,nilai:cnilai,kd:no,no_bukti1:cnobukti1}),
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
							 url: '<?php echo base_url(); ?>/index.php/xtukd_ppkd/thapus/'+nospp+'/'+giat+'/'+rek,
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
            var urll= '<?php echo base_url(); ?>/index.php/xtukd_ppkd/hhapus';             			    
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
				ids.push(rows[i].kdsubkegiatan);
			}
			return ids.join(':');
		}
        
        function getSelections1(idx){
			var ids = [];
			var rows = $('#dg1').edatagrid('getSelections');
			for(var i=0;i<rows.length;i++){
				ids.push(rows[i].kdrek6);
			}
			return ids.join(':');
		}
    

    function kembali(){
        $('#kem').click();
    }                
    

    function load_sum_spp(){                
		var nospp = document.getElementById('no_spp').value;
        //var nospp =spp.split("/").join("123456789");       
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({spp:nospp}),
            url:"<?php echo base_url(); ?>index.php/spp/load_sum_spp",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rektotal_ls").attr('value',number_format(n['rektotal'],2,'.',','));
                    $("#rektotal1_ls").attr('value',number_format(n['rektotal'],2,'.',','));
                });
            }
         });
        });
    }

    
    
    function tombol(st,sts_setuju){ 
	if(sts_setuju==''){
		sts_setuju=0;
	}
	
	if(st==''){
		st=0;
	}
	
	
    if ((st==1) && (sts_setuju==1)){
    $('#sah').linkbutton('disable');
    $('#btl').linkbutton('disable');
    document.getElementById("p1").innerHTML="Sudah disahkan dan dibuat SPM...!!!";
    } 
	else if ((st==1) && (sts_setuju==0)){
    $('#sah').linkbutton('disable');
    $('#btl').linkbutton('disable');
    document.getElementById("p1").innerHTML="Sudah dibuat SPM...!!!";
    } 
	else if ((st==0) && (sts_setuju==1)){
    $('#sah').linkbutton('disable');
    $('#btl').linkbutton('enable');
    document.getElementById("p1").innerHTML="Sudah disahkan...!!!";
    }
	else {
     $('#sah').linkbutton('enable');
     $('#btl').linkbutton('disable');
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
		

    function cetak_spp3(){ 
            var urll= '<?php echo base_url(); ?>/index.php/xtukd_ppkd/cetakspp3';             			    
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
        
   
   function openWindow( url )
        {
		var nomer	= $("#cspp").combogrid('getValue');
        var jns		= document.getElementById('jns_beban').value; 
        var no 		= nomer.split("/").join("123456789");
		/*var ttd1   	= $("#ttd1").combogrid('getValue');
		var ttd2   	= $("#ttd2").combogrid('getValue');
		var ttd4   	= $("#ttd4").combogrid('getValue');
		*/
		var tanpa	= document.getElementById('tanpa_tanggal').checked; 
		if ( tanpa == false ){
           tanpa=0;
        }else{
           tanpa=1;
        }
		/*
		if ( ttd1 =='' ){
			alert("Bendahara Pengeluaran tidak boleh kosong!");
			exit();
		}
		if ( ttd2 =='' ){
			alert("PPTK tidak boleh kosong!");
			exit();
		}
		if ( ttd4 =='' ){
			alert("PPKD tidak boleh kosong!");
			exit();
		}
        var ttd_1 =ttd1.split(" ").join("123456789");
        var ttd_2 =ttd2.split(" ").join("123456789");
        var ttd_4 =ttd4.split(" ").join("123456789");
		*/
		
        window.open(url+'/'+no+'/'+kode+'/'+jns+'/-/-/-/'+tanpa, '_blank');
        window.focus();
        }
    

	function openWindow2( url )
        {
		var nomer   = $("#cspp").combogrid('getValue');
        var jns = document.getElementById('jns_beban').value; 
        var no =nomer.split("/").join("123456789");
		var ttd3   = $("#ttd3").combogrid('getValue');
		var tanpa       = document.getElementById('tanpa_tanggal').checked; 
		if ( tanpa == false ){
           tanpa=0;
        }else{
           tanpa=1;
        }
		if ( ttd3 =='' ){
			alert("Bendahara Pengeluaran tidak boleh kosong!");
			exit();
		}
		
        var ttd_3 =ttd3.split(" ").join("123456789");

       // window.open(url+'/'+no+'/'+kode+'/'+jns+'/'+ttd_3+'/'+tanda, '_blank');
        window.open(url+'/'+no+'/'+kode+'/'+jns+'/'+ttd_3+'/'+tanpa, '_blank');
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
   
/*    function validate_jenis_edit(){
        var beban   = document.getElementById('jns_beban').value;
		$('#cc').combobox({url:'<?php echo base_url(); ?>/index.php/xtukd_ppkd/load_jenis_beban/'+beban,
		});
		$('#sp').combogrid({url:'<?php echo base_url(); ?>/index.php/xtukd_ppkd/spd1_ag/'+beban,
		});
		if (beban=='5'){
			$("#npwp").attr('disabled',false);
			//$("#tgl_mulai").datebox('enable');
			//$("#tgl_akhir").datebox('enable');
			$("#rekanan").combogrid('enable');
			$("#dir").attr('disabled',false);
			$("#alamat").attr('disabled',false);
			//$("#kontrak").attr('disabled',false);
			$("#bank1").combogrid('enable');
			$("#rekening").attr('disabled',false);

			/* } else {
			$("#npwp").attr('disabled',true);
			$("#tgl_mulai").datebox('disable');
			$("#tgl_akhir").datebox('disable');
			$("#rekanan").combogrid('disable');
			$("#dir").attr('disabled',true);
			$("#alamat").attr('disabled',true);
			$("#kontrak").attr('disabled',true);
			$("#bank1").combogrid('disable');
			$("#rekening").attr('disabled',true);
		
		}
		$('#cc').combobox('setValue', jns_bbn);
	} */
	
 /*    function validate_jenis(){
		var tanggal_spp = $('#dd').datebox('getValue');
		if(tanggal_spp == ''){
			alert("Isi Tanggal SPP Terlebih Dahulu!");
			$("#jns_beban").attr("Value",'');
			exit();
		}
        var beban   = document.getElementById('jns_beban').value;
		$('#cc').combobox({url:'<?php echo base_url(); ?>/index.php/xtukd_ppkd/load_jenis_beban/'+beban,
		});
		$('#sp').combogrid({url:'<?php echo base_url(); ?>/index.php/xtukd_ppkd/spd1_ag/'+beban+'/'+tanggal_spp,
		});
		if (beban=='5'){
			$("#npwp").attr('disabled',false);
			//$("#tgl_mulai").datebox('enable');
			//$("#tgl_akhir").datebox('enable');
			$("#rekanan").combogrid('enable');
			$("#dir").attr('disabled',false);
			$("#alamat").attr('disabled',false);
			
			//$("#kontrak").attr('style', 'visibility: hidden');
			$("#bank1").combogrid('enable');
			$("#rekening").attr('disabled',false);
		/* } else {
			$("#npwp").attr('disabled',true);
			$("#tgl_mulai").datebox('disable');
			$("#tgl_akhir").datebox('disable');
			$("#rekanan").combogrid('disable');
			$("#dir").attr('disabled',true);
			$("#alamat").attr('disabled',true);
			$("#kontrak").attr('disabled',true);
			$("#bank1").combogrid('disable');
			$("#rekening").attr('disabled',true);
		
		} 
	}  */
	
	/*  function validate_tombol(){
        var beban   = document.getElementById('jns_beban').value;
		var jenis   = $("#cc").combobox('getValue');
		if ((beban=='5') && (jenis=='3')){
			$("#npwp").attr('disabled',false);
			//$("#tgl_mulai").datebox('enable');
			//$("#tgl_akhir").datebox('enable');
			$("#rekanan").combogrid('enable');
			$("#dir").attr('disabled',false);
			$("#alamat").attr('disabled',false);
			//$("#kontrak").attr('disabled',false);
			$("#bank1").combogrid('enable');
			$("#rekening").attr('disabled',false);
		/* } else {
			$("#npwp").attr('disabled',true);
			//$("#tgl_mulai").datebox('disable');
			//$("#tgl_akhir").datebox('disable');
			$("#rekanan").combogrid('disable');
			$("#dir").attr('disabled',true);
			$("#alamat").attr('disabled',true);
			//$("#kontrak").attr('disabled',true);
			$("#bank1").combogrid('disable');
			$("#rekening").attr('disabled',true); 
		}
    } */
	
    function runEffect() {
        var selectedEffect = 'explode';            
        var options = {};                      
        $( "#tagih" ).toggle( selectedEffect, options, 500 );
        $("#notagih").combogrid("setValue",'');
        $("#tgltagih").attr("value",'');
        $("#nmskpd").attr("value",'');
        $("#nil").attr("value",'');
        $("#ni").attr("value",'');
    };        
    
    
    function detail_trans_3(){
        $(function(){
			$('#dgsppls').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/spp/select_data1',
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
                    tkdsubkegiatan = rowData.kdsubkegiatan ;
                    tkdrek6     = rowData.kdrek6 ;
                    tnmrek6     = rowData.nmrek6 ;
                    tnilai1     = rowData.nilai1 ;
                                                               
                },
                 columns:[[
                     {field:'idx',
					 title:'idx',
					 width:100,
					 align:'left',
                     hidden:'true'
					 },               
                     {field:'kdsubkegiatan',
					 title:'Kegiatan',
					 width:150,
					 align:'left'
					 },
					{field:'kdrek6',
					 title:'Rekening',
					 width:100,
					 align:'left'
					 },
					{field:'nmrek6',
					 title:'Nama Rekening',
					 width:340
					 },
                    {field:'nilai1',
					 title:'Nilai',
					 width:140,
                     align:'right'
                     }
				]]	
			});
		});
        }
        
        
        function detail_kosong(){
            
        var no_spp = '' ; 
        $(function(){
			$('#dgsppls').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/spp/select_data1',
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
                     {field:'kdsubkegiatan',
					 title:'Kegiatan',
					 width:150,
					 align:'left'
					 },
					{field:'kdrek6',
					 title:'Rekening',
					 width:70,
					 align:'left'
					 },
					{field:'nmrek6',
					 title:'Nama Rekening',
					 width:280
					 },
                    {field:'nilai1',
					 title:'Nilai',
					 width:140,
                     align:'right'
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

            $('#dgsppls').edatagrid('appendRow',{kdsubkegiatan:vrek_kegi,kdrek6:vrek_reke,nmrek6:vnm_rek_reke,nilai1:cnilai,idx:pidx});
            $("#dialog-modal-rek").dialog('close'); 
            
            jumtotal = jumtotal + angka(cnil) ;
            $("#rektotal_ls").attr('value',number_format(jumtotal,2,'.',','));
            $("#rektotal1_ls").attr('value',number_format(jumtotal,2,'.',','));
            $("#dgsppls").datagrid("unselectAll");
            
       }
       
       
       function hapus_detail(){
        
        var a          = document.getElementById('no_spp').value;
        var rows       = $('#dgsppls').edatagrid('getSelected');
        var ctotalspp  = document.getElementById('rektotal_ls').value ;
        
        bkdrek      = rows.kdrek6;
        bkdsubkegiatan = rows.kdsubkegiatan;
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
              
             var urll = '<?php  echo base_url(); ?>index.php/xtukd_ppkd/dsimpan_spp';
             $(document).ready(function(){
             $.post(urll,({cnospp:a,ckdgiat:bkdsubkegiatan,ckdrek:bkdrek,cnobukti:bbukti}),function(data){
             status = data;
                if (status=='0'){
                    alert('Gagal Hapus..!!');
                    exit();
                } else {
                    alert('Data Telah Terhapus..!!');
                    exit();
                }
             });
             });    
        }     
        }
    
   function tox_tanggal() {
  now = new Date();
  year = "" + now.getFullYear();
  month = "" + (now.getMonth() + 1); if (month.length == 1) { month = "0" + month; }
  day = "" + now.getDate(); if (day.length == 1) { day = "0" + day; }
  hour = "" + now.getHours(); if (hour.length == 1) { hour = "0" + hour; }
  minute = "" + now.getMinutes(); if (minute.length == 1) { minute = "0" + minute; }
  second = "" + now.getSeconds(); if (second.length == 1) { second = "0" + second; }
  return year + "-" + month + "-" + day + " " + hour + ":" + minute + ":" + second;
}

     function get_username() {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/spp/config_nm_user',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        			usernm = data;
        			}                                     
        	});
             
        }
	
	function setuju(){
		var no_spp = document.getElementById('no_spp').value;
		$(function(){      
			 $.ajax({
				type: 'POST',
				data: ({no_spp:no_spp,kd_skpd:kode}),
				dataType:"json",
				url:"<?php echo base_url(); ?>index.php/spp/setuju_spp",
				success:function(data){
					if (data = 2){
						alert('SPP TU telah disetujui');
						document.getElementById("p1").innerHTML="SPP sudah disetujui!!";
						$('#sah').linkbutton('disable');
						$('#btl').linkbutton('enable');
					}
				}
			 });
			});
	}
	
	function batal(){
		var no_spp = document.getElementById('no_spp').value;
		$(function(){      
			 $.ajax({
				type: 'POST',
				data: ({no_spp:no_spp,kd_skpd:kode}),
				dataType:"json",
				url:"<?php echo base_url(); ?>index.php/spp/batal_spp",
				success:function(data){
					if (data = 1){
						alert('SPP TU telah dibatalkan');
						document.getElementById("p1").innerHTML="SPP sudah dibatalkan!!";
						$('#sah').linkbutton('enable');
						$('#btl').linkbutton('disable');
					}
				}
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
<h3><a href="#" id="section1" onclick="javascript:$('#spp').edatagrid('reload')">List SPP TU</a></h3>
    
    <div style="height:350px;">
    <p align="right">         
    <!---     <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section2();kosong();">Tambah</a>
       <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a>    --->          
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="spp" title="List SPP" style="width:870px;height:650px;" >  
        </table>
    </p> 
    </div>

<h3><a href="#" id="section2">RINCIAN SPP</a></h3>
   
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
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><input type="text" id="no_simpan" style="border:0;width: 200px;" readonly="true";/></td>
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
   <td width="8%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >No SPP</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;<input type="text" name="no_spp" id="no_spp"  style="width:350px" readonly="true" onkeyup="this.value = this.value.toUpperCase()"/><input type="hidden" name="no_spp_hide" id="no_spp_hide" style="width:140px" readonly="true"/></td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"></td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<!-- <input id="dd" name="dd" type="text"  /> --></td>   
 </tr>
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">SKPD</td>
   <td width="53%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >     
      &nbsp;<input id="dn" name="dn"  readonly="true" style="width:130px; border: 0;"/> </td> 
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
   <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><textarea name="nmskpd" id="nmskpd" cols="40" rows="2" style="border: 0;"  readonly="true"></textarea></td>
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Keperluan</td>
   <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><textarea name="ketentuan" id="ketentuan" cols="40" rows="2" readonly="true" style="border: 0;"  ></textarea></td>
 </tr> 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Beban</td>
   <td width='92%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <select value="" name="jns_beban" id="jns_beban" style="height: 27px; width:190px;">
     <option value="3">SPP TU</option>
</select >   <input id="cc" name="dept" style="width: 190px;" value="3" type=hidden>
   
   </td>
 <td width="8%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" ></td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<!--<input id="rekanan" name="rekanan" style="width:250px" />-->
     <br>&nbsp;<input id="dir" name="dir" style="width:250px" hidden = "true"/></td>
	 
 </tr>
 
 <tr>
   <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">No SPD</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;
   <input id="sp" name="sp" style="width:220px" disabled />&nbsp;&nbsp; <!-- <input id="tglspd" name="tglspad" type="text" style="width:100px" disabled /> --></td></td>
   <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">BANK</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input type="text" name="bank1" id="bank1" style="width:50px"/>
       <br>&nbsp;<input type ="input" readonly="true" style="border:hidden" id="nama_bank" name="nama_bank" style="width:250px" /></td>
 </tr>
 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Kegiatan</td>
   <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >&nbsp;<input id="kg" name="kg" style="width:180px" disabled />
   &nbsp;<input type ="hidden" id="kp" name="kp" style="width:160px" disabled />
    &nbsp;&nbsp;<input id="nm_kg" name="nm_kg" style="width:150px;border: 0;"/>
      <input type ="hidden" id="nm_kp" name="nm_kp" /></td>
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Rekening</td>
   <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input type="text" name="rekening" id="rekening"  value="" style="width:190px" readonly="true" /></td>
 </tr>
  <!--

 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">NPWP</td>
   <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input type="text" name="npwp" id="npwp" value="" style="width:250px"/></td>
 </tr>
 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
	<td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
    <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
	<td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Alamat Perusahaan</td>
   <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<textarea name="alamat" id="alamat" cols="30" rows="1" ></textarea></td>   
 </tr> 
 -->
     <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
       <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
       <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
       <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
       <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;</td>
     </tr>  

       <tr style="border-spacing: 3px;padding:3px 3px 3px 3px;">
                <td colspan="4" align='right' style="border-bottom-color:black;border-spacing: 3px;padding:3px 3px 3px 3px;" >
                <a id="sah" class="easyui-linkbutton" iconCls="icon-ok" plain="true"  onclick="javascript:setuju();">Setujui</a>
                <a id="btl" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="javascript:batal();">Batal Setujui</a>
				<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>
                <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a></td>              
       </tr>
</table>

    
        <!------------------------------------------------------------------------------------------------------------------>
        
        <table id="dgsppls" title="Input Detail SPP" style="width:850%;height:300%;" >  
        </table>
        
        <div id="toolbar" align="left">
    		<!--<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Tambah Rekening</a>-->
        </div>
  
        <table border='0' style="width:100%;height:5%;"> 
             <td width='39%'></td>
             <td width='15%'><input class="right" type="hidden" name="rektotal1_ls" id="rektotal1_ls"  style="width:140px" align="right" readonly="true" ></td>
             <td width='9%'><B>Total</B></td>
             <td width='32%'><input class="right" type="text" name="rektotal_ls" id="rektotal_ls"  style="width:140px" align="right" readonly="true" ></td>
        </table>
        </fieldset>
        <!------------------------------------------------------------------------------------------------------------------>
   </div>

</div>
</div> 

<div id="dialog-modal-rek" title="Input Rekening">
    <p class="validateTips"></p>  
    <fieldset>
    <table align="center" style="width:100%;" border="0">
       
            <tr>
                <td width='15%'>SKPD</td>
                <td width='3%'>:</td>
                <td colspan="4" width='82%'><input id="rek_skpd" name="rek_skpd" style="width: 200px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="rek_nmskpd" style="border:0;width: 300px;" readonly="true"/></td>                            
            </tr>

            <tr>
                <td>KEGIATAN</td>
                <td>:</td>
                <td colspan="4"><input id="rek_kegi" name="rek_kegi" style="width: 200px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="nm_rek_kegi" style="border:0;width: 300px;" readonly="true"/></td>                            
            </tr>

            <tr>
                <td>REKENING</td>
                <td>:</td>
                <td colspan="4"><input id="rek_reke" name="rek_reke" style="width: 200px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="nm_rek_reke" style="border:0;width: 300px;" readonly="true"/></td>                            
            </tr>

            <tr>
                <td>TOTAL SPD</td>
                <td>:</td>
                <td><input type="text" id="total_spd" style="width: 196px; text-align: right; " readonly="true" /></td> 
				<td>ANGGARAN</td>
                <td>:</td>
                <td><input type="text" id="rek_nilai_ang" style="width: 196px; text-align: right; " readonly="true" /></td> 

            </tr>
            
            <tr>
                <td>SPD TERPAKAI</td>
                <td>:</td>
                <td><input type="text" id="nilai_spd_lalu" style="width: 196px; text-align: right; " readonly="true" /></td> 
                <td>JUMLAH SPP LALU</td>
                <td>:</td>
                <td><input type="text" id="rek_nilai_spp" style="width: 196px; text-align: right; " readonly="true" /></td> 
            </tr>

            <tr>
                <td>SISA SPD</td>
                <td>:</td>
                <td><input type="text" id="nilai_sisa_spd" style="width: 196px; text-align: right; " readonly="true" /></td>
				<td>SISA ANGGARAN</td>
                <td>:</td>
                <td><input type="text" id="rek_nilai_sisa" style="width: 196px; text-align: right; " readonly="true" /></td>
            </tr>

            <tr>
                <td>NILAI</td>
                <td>:</td>
                <td><input type="text" id="rek_nilai" style="width: 196px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td> 
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

<div id="dialog-modal" title="CETAK SPP">
    <p class="validateTips">SILAHKAN PILIH SPP</p>  
    <fieldset>
    <table>
        <tr>            
            <td width="110px">NO SPP:</td>
            <td><input id="cspp" name="cspp" style="width: 170px;" disabled />  &nbsp; &nbsp; &nbsp; <input type="checkbox" id="tanpa_tanggal"> Tanpa Tanggal</td>
        </tr>
       
    </table>  
    </fieldset>
    <div>
    </div>    
	<a href="<?php echo site_url(); ?>tukd/cetak_pengesahan_spp/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:openWindow(this.href);return false;">Rincian</a>
	<br/>
	<a href="<?php echo site_url(); ?>tukd/cetak_pengesahan_spp/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:openWindow(this.href);return false;">Rincian</a>
	&nbsp;&nbsp;&nbsp;<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>  
</div>

</body>
</html>