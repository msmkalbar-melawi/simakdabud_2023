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
    
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
   
    <script type="text/javascript">
 
	var nl =0;
	var tnl =0;
	var idx=0;
	var tidx=0;
	var oldRek=0;
    var rek=0;
    var detIndex=0;
    
    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 400,
                width: 800,
                modal: true,
                autoOpen:false                
            });             
        });    
    $(document).ready(function(){
        $('#skpd').hide();
        $('#giat').hide();
    });
    
	$(function(){
 	   	   var mgiat = document.getElementById('giat').value;
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/rka/select_rka',
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",
 				onAfterEdit:function(rowIndex, rowData, changes){								
								rk=rowData.kd_rek5;
								nilai=rowData.nilai;
								sdana=rowData.sdana;
								simpan(rk,oldRek,nilai,sdana);
			 				},
			 	onSelect:function(){							
    				oldRek=getSelections(getRowIndex(this));	

    				},
				columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
					{field:'kd_rek5',
					 title:'Kode Rekening',
					 width:20,
					 align:'left',	
					 editor:{type:"combobox",
							 options:{valueField:'kd_rek5',
									  textField:'kd_rek5',
									  panelwidth:1000,	
									  url :'<?php echo base_url();?>/index.php/rka/ld_rek/'+mgiat,
									  required:true,
									  onChange:function(newValue,oldValue){ 
													//if ( (oldRek =='') && (newValue.length==7) ){	
													//	oldRek=newValue;
													//	simpan(newValue,oldValue,nl);  														
													//}
											   }
									  }
							}
					},
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:80,
					 editor:{type:"text"}
					},
                    {field:'nilai',
					 title:'Nilai Rekening',
					 width:20,
                     align:'right',
					 editor:{type:"numberbox",
						     options:{precision:0,groupSeparator:',',decimalSeparator:'.'}
							} 
                     },
					 {field:'sdana',
					  title:'Sumber Dana',
					  width:20,
					  align:'center',
					 editor:{type:"combobox",
							 options:{valueField:'sdana',
									  textField:'sdana',
									  panelwidth:1000,	
									  url :'<?php echo base_url();?>/index.php/rka/ld_sdana/',
									  onChange:function(newValue,oldValue){ 
													//if ( (oldRek =='') && (newValue.length==7) ){	
													//	oldRek=newValue;
													//	simpan(newValue,oldValue,nl);  														
													//}
											   }
									  }
							}
			 		 },
					 {field:'rinci',
					  title:'Detail',
					  width:10,
					  align:'center', 
					  formatter:function(value,rec){
							rek=rec.kd_rek5
							return ' <p onclick="javascript:section('+rec.kd_rek5+');">Rincian</p>';
						}
			 		}
				]]	
			
			});
  	
            $(function(){
            $('#sskpd').combogrid({  
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
                kdskpd = rowData.kd_skpd;
                $("#nmskpd").attr("value",rowData.nm_skpd);
                $("#skpd").attr("value",rowData.kd_skpd);
                validate_giat();
            }  
            }); 
            });
            
            
		});

        function validate_giat(){
	  	$(function(){
            $('#kdgiat').combogrid({  
            panelWidth:700,  
            idField:'kd_kegiatan',  
            textField:'kd_kegiatan',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka/pgiat/'+kdskpd,  
            columns:[[  
                {field:'kd_kegiatan',title:'Kode SKPD',width:150},  
                {field:'nm_kegiatan',title:'Nama Kegiatan',width:650}    
            ]],
            onSelect:function(rowIndex,rowData){
                kegiatan = rowData.kd_kegiatan;
                $("#nmgiat").attr("value",rowData.nm_kegiatan);
                $("#giat").attr("value",rowData.kd_kegiatan);
                validate_combo();
                //validate_rek();
            }  
            }); 
            });
		}

		function getSelections(idx){
			//alert(idx);
			var ids = [];
			var rows = $('#dg').edatagrid('getSelections');
			for(var i=0;i<rows.length;i++){
				ids.push(rows[i].kd_rek5);
			}
			return ids.join(':');
		}


		function getSelections2(idx){
			//alert(idx);
			var ids = [];
			var rows = $('#dg1').edatagrid('getSelections');
			for(var i=0;i<rows.length;i++){
				ids.push(rows[i].no_po);
			}
			return ids.join(':');
		}


		function getRowIndex(target){  
			var tr = $(target).closest('tr.datagrid-row');  
			return parseInt(tr.attr('datagrid-row-index'));  
		}  


        function simpan(baru,lama,nilai,sdana){		
		var cgiat = document.getElementById('giat').value;
		var cskpd = document.getElementById('skpd').value;
		if (lama==''){
			lama=baru;
		}
			$(function(){
				$('#dg').edatagrid({
				     url: '<?php echo base_url(); ?>/index.php/rka/tsimpan/'+cskpd+'/'+cgiat+'/'+baru+'/'+lama+'/'+nilai+'/'+sdana,
					 idField:'id',
					 toolbar:"#toolbar",              
					 rownumbers:"true", 
					 fitColumns:"true",
					 singleSelect:"true",
				});
			});

		}
		
														

        function validate_combo(){
			var cgiat = document.getElementById('giat').value;       
            $(function(){
			$('#dg').edatagrid({
				 url: '<?php echo base_url(); ?>/index.php/rka/select_rka/'+cgiat,
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",
				 showFooter:true,
				 nowrap:false,
				 onAfterEdit:function(rowIndex, rowData, changes){								
								rk=rowData.kd_rek5;
								nilai=rowData.nilai;
								sdana=rowData.sdana;
								simpan(rk,oldRek,nilai,sdana);
							 },
				 onSelect:function(){							
							  oldRek=getSelections(getRowIndex(this));
                             
						  },
				onLoadSuccess:function(data){
						   	load_sum_rek();		 
						  },
				columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
					{field:'kd_rek5',
					 title:'Kode Rekening',
					 width:20,
					 align:'left',	
					 editor:{type:"combobox",
							 options:{valueField:'kd_rek5',
									  textField:'kd_rek5',
									  panelwidth:1000,	
									  url :'<?php echo base_url();?>/index.php/rka/ld_rek/'+cgiat,
									  required:true,
									  onChange:function(newValue,oldValue){ 
													//if ( (oldRek =='') && (newValue.length==7) ){	
													//	oldRek=newValue;
													//	simpan(newValue,oldValue,nl);  														
													//}
											   }
									  }
							}
					},
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:80,
					 editor:{type:"text"}
					},
                    {field:'nilai',
					 title:'Nilai Rekening',
					 width:20,
                     align:'right',
					 editor:{type:"numberbox",
						     options:{precision:0,groupSeparator:',',decimalSeparator:'.'}
							} 
                     },
					 {field:'sdana',
					  title:'Sumber Dana',
					  width:20,
					  align:'center',
					 editor:{type:"combobox",
							 options:{valueField:'sdana',
									  textField:'sdana',
									  panelwidth:1000,	
									  url :'<?php echo base_url();?>/index.php/rka/ld_sdana/',
									  onChange:function(newValue,oldValue){ 
													//if ( (oldRek =='') && (newValue.length==7) ){	
													//	oldRek=newValue;
													//	simpan(newValue,oldValue,nl);  														
													//}
											   }
									  }
							}
			 		 },
					 {field:'rinci',
					  title:'Detail',
					  width:10,
					  align:'center', 
					  formatter:function(value,rec){
							rek=rec.kd_rek5
							return ' <p onclick="javascript:section('+rec.kd_rek5+');">Rincian</p>';
						}
			 		}
				]]

			});
		});
		
        }
        
        function validate_rek(){
        //var cgiat = document.getElementById('giat').value;  
        //alert(kegiatan);     
            $(function(){
			$('#dg_rek').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/rka/ld_rek/'+kegiatan,
                 idField:'id',                  
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",
				 showFooter:true,
				 nowrap:false,				 
				columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
					{field:'kd_rek5',
					 title:'Kode Rekening',
					 width:20,
					 align:'left'
					},
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:80
					}
				]],
                //onSelect:function(rowIndex,rowData){
//                    rk = rowData.kd_rek5;
//                    nmrk = rowData.nm_rek5;
//                    nilai= 0;
//                    sdana='PAD';                    
//                    simpan(rk,oldRek,nilai,sdana);  
//                    },
                    onClickRow:function(rowIndex, rowData){                                
                    rk = rowData.kd_rek5;
                    nmrk = rowData.nm_rek5;
                    nilai= 0;
                    sdana='PAD';                    
                    simpan(rk,oldRek,nilai,sdana);   
					}				 		

			});
		});    
        }
        
    	//function append_jak(){                     
//            $('#dg').datagrid('appendRow',{kd_rek5:rk,nm_rek5:nmrk});
//        }	
        
		function hapus(){
				var cgiat = document.getElementById('giat').value;
				var cskpd = document.getElementById('skpd').value;
				var rek=getSelections();
				if (rek !=''){
				var del=confirm('Anda yakin akan menghapus rekening '+rek+' ?');
				if  (del==true){
					$(function(){
						$('#dg').edatagrid({
							 url: '<?php echo base_url(); ?>/index.php/rka/thapus/'+cskpd+'/'+cgiat+'/'+rek,
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


		function hapus_rinci(){
				var cgiat = document.getElementById('giat').value;
				var cskpd = document.getElementById('skpd').value;
				var rek=getSelections();
				var idx=getSelections2();
				if ((rek !='') && (idx !='')) {
						$(function(){
							$('#dg1').edatagrid({
								 url: '<?php echo base_url(); ?>/index.php/rka/thapus_rinci/'+cskpd+'/'+cgiat+'/'+rek+'/'+idx,
								 idField:'id',
								 toolbar:"#toolbar1",              
								 rownumbers:"true", 
								 fitColumns:"true",
								 singleSelect:"true"
							});
						});					
				}
		}
		
		
		
		function simpan_rincian(idx,uraian,volum1,satuan1,harga1,volum2,satuan2,volum3,satuan3,rk){		
		var cgiat = document.getElementById('giat').value;
		var cskpd = document.getElementById('skpd').value;

		if (volum1==""){
			volum1=0;
		}

		if (volum2==""){
			volum2=0;
		}
		if (volum3==""){
			volum3=0;
		}

		if (satuan1==""){
			satuan1="12345678987654321";
		}
		if (satuan2==""){
			satuan2="12345678987654321";
		}
		if (satuan3==""){
			satuan3="12345678987654321";
		}

			$(function(){
				$('#dg1').edatagrid({
				     url: '<?php echo base_url(); ?>/index.php/rka/tsimpan_rinci/'+cskpd+'/'+cgiat+'/'+rk+'/'+idx+'/'+uraian+'/'+volum1+'/'+satuan1+'/'+harga1+'/'+volum2+'/'+satuan2+'/'+volum3+'/'+satuan3,
					 idField:'id',
					 toolbar:"#toolbar1",              
					 rownumbers:"true", 
					 fitColumns:"true",
					 singleSelect:"true"
				});
			});
		
		}
		
        
        $(function(){
       	    var mskpd = document.getElementById('skpd').value;
            var mgiat = document.getElementById('giat').value;
			$('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/rka/rka_rinci',
                 idField:'id',
                 toolbar:"#toolbar1",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true",
				 onAfterEdit:function(rowIndex, rowData, changes){								
							 
							 },
				 onSelect:function(rowIndex, rowData, changes){							
							  detIndex=rowIndex;	
						  },
				columns:[[
					{field:'no_po',
					 title:'no',
					 width:5,
					 hidden:true,
					 editor:{type:"numberbox"}
					},
					{field:'uraian',
					 title:'Uraian',
					 width:200,
					 editor:{type:"text"}
					},
                    {field:'volume1',
					 title:'Vol 1',
					 width:50,
                     align:'right',
					 editor:{type:"numberbox",
						     options:{precision:0,groupSeparator:',',decimalSeparator:'.'}
							} 
                     },
                    {field:'satuan1',
					 title:'Sat 1',
					 width:50,
                     align:'center',
					 editor:{type:"text"},
                     },
                    {field:'volume2',
					 title:'Vol 2',
					 width:50,
                     align:'right',
					 editor:{type:"numberbox",
						     options:{precision:0,groupSeparator:',',decimalSeparator:'.'}
							} 
                     },
                    {field:'satuan2',
					 title:'Sat 2',
					 width:50,
                     align:'center',
					 editor:{type:"text"},
                     },
                    {field:'volume3',
					 title:'Vol 3',
					 width:50,
                     align:'right',
					 editor:{type:"numberbox",
						     options:{precision:0,groupSeparator:',',decimalSeparator:'.'}
							} 
                     },
                    {field:'satuan3',
					 title:'Sat 3',
					 width:50,
                     align:'center',
					 editor:{type:"text"},
                     },
                    {field:'harga1',
					 title:'Harga',
					 width:70,
                     align:'right',
					 editor:{type:"numberbox",
						     options:{precision:0,groupSeparator:',',decimalSeparator:'.'}
							} 
                     },
                    {field:'total',
					 title:'Total',
					 width:90,
                     align:'right'
                     }
				]]	
			
			});
		});
        
        $(document).ready(function() {
            $("#accordion").accordion();
        });
  
        function section(kdrek){
			
       	    var mskpd = document.getElementById('skpd').value;
            var mgiat = document.getElementById('giat').value;
			var a=kdrek //+ " : <?php echo $this->rka_model->get_nama('5220101','nm_rek5','ms_rek5','kd_rek5'); ?>";
            $(document).ready(function(){
 			                    
				$('#section2').click();
                $(function(){
        			$('#dg1').edatagrid({
       				     url: '<?php echo base_url(); ?>/index.php/rka/rka_rinci/'+mskpd+'/'+mgiat+'/'+kdrek,
                         idField:'id',
                         toolbar:"#toolbar1",              
                         rownumbers:"true", 
                         fitColumns:false,
						 title:a,
                         singleSelect:"true",
						 onAfterEdit:function(rowIndex, rowData, changes){								
										urai=rowData.uraian;
										idx=rowIndex;
										
										vol1=rowData.volume1;
										sat1=rowData.satuan1;
										har1=rowData.harga1;

										vol2=rowData.volume2;
										sat2=rowData.satuan2;

										vol3=rowData.volume3;
										sat3=rowData.satuan3;

										simpan_rincian(idx,urai,vol1,sat1,har1,vol2,sat2,vol3,sat3,kdrek);
								     },
						onLoadSuccess:function(data){
										load_sum_rek_rinci(kdrek);		 
									 }
        			});
         		});
                    
            });
        }

	   function section1(){
		 validate_combo();
         $(document).ready(function(){    
             $('#section1').click();                                               
         });
       }


    function load_sum_rek(){                
		var a = document.getElementById('skpd').value;
        var b = document.getElementById('giat').value;
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({skpd:a,keg:b}),
            url:"<?php echo base_url(); ?>index.php/rka/load_sum_rek",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rektotal").attr("value",n['rektotal']);
                });
            }
         });
        });
    }

    function load_sum_rek_rinci(c){                
		var a = document.getElementById('skpd').value;
        var b = document.getElementById('giat').value;
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({skpd:a,keg:b,rek:c}),
            url:"<?php echo base_url(); ?>index.php/rka/load_sum_rek_rinci",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rektotal_rinci").attr("value",n['rektotal_rinci']);
                });
            }
         });
        });
    }
    
//tambahan jaka    
    function tambah(){
        //alert(kegiatan+kdskpd)
        //var skpd = document.getElementById('sskpd').value;
        //var giat = document.getElementById('kdgiat').value;
         //$("#dg_rek").combogrid("clear");          
        if (kegiatan != '' && kdskpd != '' ){            
            $("#dialog-modal").dialog('open');            
            validate_rek()
                   
        } else {
            alert('Harap Isi Kode SKPD dan Kegiatan ') ;         
        }
    }
    
    function keluar(){
        
        $("#dialog-modal").dialog('close');
        $('#dg_rek').datagrid('unselectAll');
        $('#dg').edatagrid('reload');
                              
    } 
      
////tambah jaka
//DETIL KEGIATAN=========================================
    function simpan_det_keg(){
        var a = document.getElementById('skpd').value;
        var b = document.getElementById('giat').value;
        var c = document.getElementById('lokasi').value; 
        var d = document.getElementById('sasaran').value;
        var e = document.getElementById('wkeg').value;
        var f = document.getElementById('cp_tu').value;
        var g = document.getElementById('cp_ck').value;
        var h = document.getElementById('m_tu').value;
        var i = document.getElementById('m_ck').value; 
        var j = document.getElementById('k_tu').value;
        var k = document.getElementById('k_ck').value;
        var l = document.getElementById('h_tu').value;  
        var m = document.getElementById('h_ck').value;
        var n = document.getElementById('ttd').value;

		$(function(){      
         $.ajax({
            type: 'POST',
            data: ({skpd:a,giat:b,lokasi:c,sasaran:d,wkeg:e,cp_tu:f,cp_ck:g,m_tu:h,m_ck:i,k_tu:j,k_ck:k,h_tu:l,h_ck:m,ttd:n}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/rka/simpan_det_keg",
			success:function(data){ 
					alert('Data Tersimpan');
					}
         });
        });
    }




    function load_detail_keg(){                
		var a = document.getElementById('skpd').value;
        var b = document.getElementById('giat').value;
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({skpd:a,keg:b}),
            url:"<?php echo base_url(); ?>index.php/rka/load_det_keg",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#lokasi").attr("value",n['lokasi']);
                    $("#sasaran").attr("value",n['sasaran']);
                    $("#wkeg").attr("value",n['wkeg']);
                    $("#ttd").attr("value",n['ttd']);
                    $("#cp_tu").attr("value",n['cp_tu']);
                    $("#m_tu").attr("value",n['m_tu']);
                    $("#k_tu").attr("value",n['k_tu']);
                    $("#h_tu").attr("value",n['h_tu']);
                    $("#cp_ck").attr("value",n['cp_ck']);
                    $("#m_ck").attr("value",n['m_ck']);
                    $("#k_ck").attr("value",n['k_ck']);
                    $("#h_ck").attr("value",n['h_ck']);
                });
            }
         });
        });
    }


		function insert(){
			$('#dg1').datagrid('insertRow',{
				index:detIndex,
				row:{uraian:''				
					}
			});
			$('#dg1').datagrid('beginEdit',detIndex+1);		
		}	

//DASAR HUKUM ==================================================================================================
        $(function(){ //load pertama
       	    var mskpd = document.getElementById('skpd').value;
            var mgiat = document.getElementById('giat').value;
			$('#dg2').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/rka/rka_hukum/'+mskpd+'/'+mgiat,
                 idField:'id',
                 rownumbers:true, 
                 fitColumns:false,
                 singleSelect:false,
				 onLoadSuccess:function(data){
							       selectall();		 
							   },
				 columns:[[
 					{field:'kd_hukum',
					 title:'kode',
					 width:5,
					 hidden:true,
					 editor:{type:"text"}
					},
					{field:'nm_hukum',
					 title:'Dasar Hukum',
					 width:800,
					 editor:{type:"text"}
					},
                    {field:'ck',
					 title:'ck',
					 width:5,
					 checkbox:true
                     }
				]]	
			
			});
		});

//load dasar hukum pada combo giat==========================================================
	var sell = new Array();
	var max  = 0;
	function getcek(){
		var ids = [];  
		var a=null;
		var rows = $('#dg2').edatagrid('getSelections');  
		for(var i=0; i<rows.length; i++){  
		    a=rows[i].ck;
			max=i;
			if (a!=null){
				sell[i]=a-1;
			}else{
				sell[i]=1000;			
			}
		}  
	}
	
	function setcek(){
		for(var i=0; i<max+1; i++){ 
			//alert(i+' : '+sell[i]);
			if (sell[i]!=1000){
				selectRecord(sell[i]);
			}
		} 		
	}


	function selectall(){
		max  = 0;
		$('#dg2').edatagrid('selectAll');
		getcek();
		Unselectall();
		setcek();
	}

	function Unselectall(){
		$('#dg2').edatagrid('unselectAll');
	}


	function selectRecord(rec){
		$('#dg2').edatagrid('selectRecord',rec);
	}
	
	function load_dhukum(){
        $(function(){
       	    var mskpd = document.getElementById('skpd').value;
            var mgiat = document.getElementById('giat').value;
			$('#dg2').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/rka/rka_hukum/'+mskpd+'/'+mgiat,
                 idField:'id',
                 rownumbers:true, 
                 fitColumns:false,
                 singleSelect:false,
				 columns:[[
 					{field:'kd_hukum',
					 title:'kode',
					 width:5,
					 hidden:true,
					 editor:{type:"text"}
					},
					{field:'nm_hukum',
					 title:'Dasar Hukum',
					 width:800,
					 editor:{type:"text"}
					},
                    {field:'ck',
					 title:'ck',
					 width:5,
					 checkbox:true
                     }
				]]	
			
			});
		});
		selectall();
	}


	function simpan_dhukum(){
		var ids = [];  
		var rows = $('#dg2').edatagrid('getSelections');  
		for(var i=0; i<rows.length; i++){  
		    ids.push(rows[i].kd_hukum);
		}  
		hukum_cont(ids.join('||'));  
	}

    function hukum_cont(isi){
        var a = document.getElementById('skpd').value;
        var b = document.getElementById('giat').value;
		$(function(){      
         $.ajax({
            type: 'POST',
            data: ({skpd:a,giat:b,cisi:isi}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/rka/simpan_dhukum",
			success:function(data){ 
					alert('Data Tersimpan');
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

   <?php echo $prev; ?><br />
   <table style="border-collapse:collapse;" width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
   <tr>
   <td><h3>S K P D&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="sskpd" name="sskpd" style="width: 150px;" />
   <input id="nmskpd" name="nmskpd" style="width: 650px; border:0;  " /></h3></td>
   </tr>
   <tr>
   <td><h3>KEGIATAN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="kdgiat" name="kdgiat" style="width: 150px;" />  
  <input id="nmgiat" name="nmgiat" style="width: 650px; border:0;  " /> </h3></td>
   </tr>
   </table>
<div id="accordion">
<h2><a href="#" id="section1" onclick="javascript:validate_combo()" >Rekening Anggaran</a></h2>
   <div  style="height: 400px;">       
        <table id="dg" title="Input Rekening Rencana Kegiatan Anggaran" style="width:880px;height:400px;" >          
        </table>  
        <div id="toolbarx">
    		<button  class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg').edatagrid('addRow')">Baru</button>
            <button class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</button>
    		<button  class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</button>
    		<button  class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('addRow');">Simpan</button>
    		<button  class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">Batal</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>Total</B>&nbsp;&nbsp;<input class="right" type="text" name="rektotal" id="rektotal"  style="width:138px" align="rigth" readonly="true">
        </div>
    </div>
    
<h3><a href="#" id="section2">Rincian Rekening</a></h3>
    <div>
        <table id="dg1"  style="width:870px;height:400px;" >          
        </table>  
        <div id="toolbar1x">
    		<button class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg1').edatagrid('addRow')">Baru</button>            
    		<button class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="javascript:insert()">Insert</button>
    		<button class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus_rinci();">Hapus</button>
    		<button class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg1').edatagrid('saveRow');">Simpan</button>
    		<button class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg1').edatagrid('cancelRow')">Batal</button>
    		<button class="easyui-linkbutton" iconCls="icon-back" plain="true" onclick="javascript:section1()">Kembali</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>Total</B>&nbsp;&nbsp;<input class="right" type="text" name="rektotal_rinci" id="rektotal_rinci"  style="width:130px" align="rigth" readonly="true" >
        </div>
    </div>   


<h3><a href="#" id="section3" onclick="javascript:load_dhukum();" >Dasar Hukum</a></h3>
    <div>
        <table id="dg2" title="Input Dasar Hukum Per Kegiatan" style="width:870px;height:400px;" >  
        
        </table>  
        <div id="toolbar1xa">
    		<button class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_dhukum();">Simpan</button>
    		<button class="easyui-linkbutton" iconCls="icon-back" plain="true" onclick="javascript:section1()">Kembali</button>
        </div>

	</div>   


<h3><a href="#" id="section4" onclick="javascript:load_detail_keg();" >Detail Kegiatan</a></h3>
    <div>

	<table align="center">
	<tr >
		<td><b>Lokasi</b></td>
		<td><textarea id="lokasi" name="lokasi" rows='1' cols="25" > </textarea></td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td><b>Kelompok Sasaran</b></td>
		<td><textarea id="sasaran" name="sasaran" rows='1' cols="25"> </textarea></td>
	</tr>
	<tr >
		<td><b>Waktu Kegiatan</b></td>
		<td><textarea id="wkeg" name="wkeg" rows='1' cols="25" > </textarea></td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td><b>PPTK</b></td>
		<td> <? echo $this->rka_model->combo_ttd(); ?></td>
	</tr>
	</table>
	<br/>    
	<br/>    

	<table align="center">
	<tr >
		<td align="center"><b>Indikator</b></td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="center"><b>Tolak Ukur</b></td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="center"><b>Capaian Kinerja</b></td>
	</tr>
	<tr >
		<td><b>Capaian Program</b></td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="center"><textarea id="cp_tu" name="cp_tu" rows='1' cols="25" > </textarea></td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="center"><textarea id="cp_ck" name="cp_ck" rows='1' cols="25" > </textarea></td>
	</tr>
	<tr >
		<td><b>Masukan</b></td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="center"><textarea id="m_tu" name="m_tu" rows='1' cols="25" readonly > </textarea></td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="center"><textarea id="m_ck" name="m_ck" rows='1' cols="25" readonly > </textarea></td>
	</tr>
	<tr >
		<td><b>Keluaran</b></td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="center"><textarea id="k_tu" name="k_tu" rows='1' cols="25" > </textarea></td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="center"><textarea id="k_ck" name="k_ck" rows='1' cols="25" > </textarea></td>
	</tr>
	<tr >
		<td><b>Hasil</b></td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="center"><textarea id="h_tu" name="h_tu" rows='1' cols="25" > </textarea></td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="center"><textarea id="h_ck" name="h_ck" rows='1' cols="25" > </textarea></td>
	</tr>
	</table>
	<br/>
        <div id="toolbar1xa">
    		<button class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_det_keg()">Simpan</button>
    		<button class="easyui-linkbutton" iconCls="icon-back" plain="true" onclick="javascript:section1()">Kembali</button>
        </div>

	</div>   
</div>
<!--tambahan jaka-->
<div id="dialog-modal" title="Input Kegiatan">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    
    <fieldset>
    <table align="center">
        <tr>
            <td>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>                               
            </td>
        </tr>
    </table>   
    </fieldset>
    <fieldset>        
        <table id="dg_rek" title="Pilih Rekening" style="width:730px;height:250px;"  >  
        </table>  
     
    </fieldset>  
</div>

<!--tambahan jaka-->



</div>  	
</body>

</html>