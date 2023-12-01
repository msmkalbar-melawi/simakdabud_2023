<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css" />
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>
    
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/shortcut.js"></script>
   
    <script type="text/javascript">
 
	var nl       = 0;
	var tnl      = 0;
	var idx      = 0;
	var tidx     = 0;
	var oldRek   = 0;
    var rek      = 0;
    var detIndex = 0;
    var kdrek    = '';
    var id       = 0;
    var status   = '0';
    var zfrek    = '';
    var zkdrek   = '';
    var status_apbd = '';
    var total_kas   = 0;
    
    
    shortcut.add("ctrl+m", function() {
        detsimpan();
    });
    
    $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 450,
                width: 970,
                modal: true,
                autoOpen:false                
            });
            
            $("#dialog-modal-edit" ).dialog({
                height: 230,
                width: 970,
                modal: true,
                autoOpen:false                
            });
            get_skpd();
			//get_nilai_kua();
        });    
    
    $(document).ready(function(){
        $('#skpd').hide();
        $('#giat').hide();
    });
    
	
    $(function(){
             
           var mgiat = document.getElementById('giat').value; 
	       $('#dg').edatagrid({
				url           : '<?php echo base_url(); ?>/index.php/rka/select_rka_alokasidana',
                 idField      : 'id',
                 toolbar      : "#toolbar",              
                 rownumbers   : "true", 
                 fitColumns   : "true",
                 singleSelect : "true",
			 	onSelect:function(rowIndex,rowData){							
    				},
				columns:[[
	                {field:'id',
					 title:'id',
					 width:10,
                     hidden:true
					},
					{field:'kd_rek5',
					 title:'Rekening',
					 width:12,
					 align:'left'	
					},
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:78
					},
                    {field:'nilai',
					 title:'Nilai Rekening',
					 width:30,
                     align:'right'
                     },
                    {field:'nilai_sempurna',
					 title:'Nilai Rek Sempurna',
					 width:30,
                     align:'right'
                     },                     
					 {field:'rinci',
					  title:'Detail',
					  width:10,
					  align:'center', 
					  formatter:function(value,rec){
							rek         = rec.kd_rek5
							return ' <p onclick="javascript:section('+rec.kd_rek5+');">Rincian</p>';
						}
			 		}
                    /*
                    ,
                    {field:'hapus',title:'Hapus',width:10,align:"center",
                     formatter:function(value,rec){ 
                     return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus();" />';
                    }}
                    */
				]]
			});
             
             
            $(function(){
            $('#rek5').combogrid({  
            panelWidth : 700,  
            idField    : 'kd_rek5',  
            textField  : 'kd_rek5',  
            mode       : 'remote',
            url        : '<?php echo base_url(); ?>index.php/rka/ambil_rekening5',  
            columns    : [[  
                {field:'kd_rek5',title:'Kode ',width:100},  
                {field:'nm_rek5',title:'Nama ',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
                kdrek = rowData.kd_rek5;
                validate_rek();
            }  
            }); 
            });
            
            
            $(function(){
            $("#sdana1").combogrid({
                panelWidth:300,
                idField   :'nm_sdana',
                textField :'nm_sdana',
                mode      :'remote',
                url       : '<?php echo base_url(); ?>index.php/rka/ambil_sdana',
                columns   : [[
                {field:'kd_sdana',title:'Kode',width:100},
                {field:'nm_sdana',title:'Sumber Dana',width:190}
                ]]
            });
            });
            
            
            $(function(){
            $("#sdana2").combogrid({
               panelWidth:300,
                idField   :'nm_sdana',
                textField :'nm_sdana',
                mode      :'remote',
                url       : '<?php echo base_url(); ?>index.php/rka/ambil_sdana',
                columns   : [[
                {field:'kd_sdana',title:'Kode',width:100},
                {field:'nm_sdana',title:'Sumber Dana',width:190}
                ]]
            });
            });
            
            
            $(function(){
            $("#sdana3").combogrid({
                panelWidth:300,
                idField   :'nm_sdana',
                textField :'nm_sdana',
                mode      :'remote',
                url       : '<?php echo base_url(); ?>index.php/rka/ambil_sdana',
                columns   : [[
                {field:'kd_sdana',title:'Kode',width:100},
                {field:'nm_sdana',title:'Sumber Dana',width:190}
                ]]
            });
            });
            
            
            $(function(){
            $("#sdana4").combogrid({
              panelWidth:300,
                idField   :'nm_sdana',
                textField :'nm_sdana',
                mode      :'remote',
                url       : '<?php echo base_url(); ?>index.php/rka/ambil_sdana',
                columns   : [[
                {field:'kd_sdana',title:'Kode',width:100},
                {field:'nm_sdana',title:'Sumber Dana',width:190}
                ]]
            });
            });


            
		});
        
		function load_sum_rek_rka(c){                
		var a = document.getElementById('skpd').value;
        var b = document.getElementById('giat').value;
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({skpd:a,keg:b,rek:c}),
            url:"<?php echo base_url(); ?>index.php/rka/load_sum_rek_rinci_rka",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rektotal_rka").attr("value",n['rektotal_rka_sempurna']);
                });
            }
         });
        });
    }
        
        
        function get_skpd()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd_sempurna',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#sskpd").attr("value",data.kd_skpd);
        								$("#nmskpd").attr("value",data.nm_skpd.toUpperCase());
                                        $("#skpd").attr("value",data.kd_skpd);
        								kdskpd = data.kd_skpd;
                                        sta    = '0';//data.status_sempurna;
                                        validate_giat();
                                        tombol(sta);
                                        validate_rekening();
                                        $("#kdrek5").combogrid("disable");
        							  }                                     
        	});
        }
        
/*		function get_nilai_kua()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/load_nilai_kua',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
					  $.each(data, function(i,n){
						$("#nilai_kua").attr("Value",n['nilai']);
						$("#nilai_kua_ang").attr("Value",n['kua_terpakai']);
						//$("#sisa_nilai_kua").attr("Value",(n['nilai']-n['kua_terpakai']));
						var n_kua  = n['nilai'] ;
						var n_kua_terpakai = n['kua_terpakai'];
					    var n_sisa_kua = angka(n_kua) - angka(n_kua_terpakai) ;
					    $("#sisa_nilai_kua").attr("Value",number_format(n_sisa_kua,2,'.',','));
					});
				}
            });
        }*/
        
        function cek_kas(){
            var xxgiat = document.getElementById('giat').value;
			var xxskpd = document.getElementById('skpd').value;
            
            total_kas = 0;
            $("#cek_kas").attr("value",0);
            
            $.ajax({
               
               url       : '<?php echo base_url(); ?>/index.php/rka/cek_kas',
               type      : 'POST',
               dataType  : 'json',
               data      : ({skpd:xxskpd,kegiatan:xxgiat}),
               success   : function(data) {
                
                    $.each(data, function(i,n){
                        var nilai_kas = n['nilai'];
                        total_kas = total_kas + angka(nilai_kas) ;
                        $("#cek_kas").attr("value",total_kas);
                    });
               }
            });
        }
        
        
        
        function validate_giat(){
	  	    $(function(){
            $('#kdgiat').combogrid({  
            panelWidth : 700,  
            idField    : 'kd_kegiatan',  
            textField  : 'kd_kegiatan',  
            mode       : 'remote',
            url        : '<?php echo base_url(); ?>index.php/rka/pgiat/'+kdskpd,  
            columns    : [[  
                {field:'kd_kegiatan',title:'Kode SKPD',width:150},  
                {field:'nm_kegiatan',title:'Nama Kegiatan',width:650}    
            ]],
            onSelect:function(rowIndex,rowData){
                kegiatan = rowData.kd_kegiatan;
                $("#nmgiat").attr("value",rowData.nm_kegiatan.toUpperCase());
                $("#giat").attr("value",rowData.kd_kegiatan);
                $("#jnskegi").attr("value",rowData.jns_kegiatan);
                validate_combo();
                $("#kdrek5").combogrid("disable");
                $("#kdrek5").combogrid("setValue",'');
                $("#sdana1").combogrid("setValue",'');
                $("#sdana2").combogrid("setValue",'');
                $("#sdana3").combogrid("setValue",'');
                $("#sdana4").combogrid("setValue",'');
                $("#nilaisumber").attr("value",'0.00');
                $("#nilaisumber2").attr("value",'0.00');
                $("#nilaisumber3").attr("value",'0.00');
                $("#nilaisumber4").attr("value",'0.00');
                
				
				document.getElementById('nilairek').value   = 0;
                document.getElementById('total_rek5').value = 0;
                document.getElementById('nmrek5').value     = '';
            },
            }); 
            });
		}
        
            
        
        function validate_rekening(){
            
            $("#dg").datagrid("unselectAll");
            $("#dg").datagrid("selectAll");
            var rows   = $("#dg").datagrid("getSelections");
            var jrows  = rows.length ;

            zfrek  = '';
            zkdrek = '';
            
            for (z=0;z<jrows;z++){
               zkdrek=rows[z].kd_rek5;                 
               if ( z == 0 ){
                   zfrek  = zkdrek ;
               } else {
                   zfrek  = zfrek+','+zkdrek ;
               }
            }          
            
            var jkegi = document.getElementById('jnskegi').value ;
            
            $('#kdrek5').combogrid({  
               panelWidth : 500,  
               idField    : 'kd_rek5',  
               textField  : 'kd_rek5',  
               mode       : 'remote',
               url        : '<?php echo base_url(); ?>index.php/master/ambil_rekening5_all_ar',  
               queryParams: ({reknotin:zfrek,jns_kegi:jkegi}),
               columns    : [[  
                   {field:'kd_rek5',title:'Kode Rekening',width:100},  
                   {field:'nm_rek5',title:'Nama Rekening',width:400}    
               ]],  
               onSelect:function(rowIndex,rowData){
                    kd_rek5 = rowData.kd_rek5;
                    $("#nmrek5").attr("value",rowData.nm_rek5.toUpperCase());
               },
               onLoadSuccess:function(data){
                    $("#nilairek").attr("value",0);
                    $("#nilaisumber").attr("value",'0.00');
                    $("#nilaisumber2").attr("value",'0.00');
                    $("#nilaisumber3").attr("value",'0.00');
                    $("#nilaisumber4").attr("value",'0.00');
					get_nilai_real_rek();
               }  
             });     
        }
                

  		function get_nilai_real_rek(){
  		    var kdskpd = document.getElementById('skpd').value;
            var kgiat = document.getElementById('giat').value;    
            //var kua_rek = document.getElementById('nilairek').value;
            var reke   = $("#kdrek5").combogrid("getValue");
        	
            $.ajax({   	       
        		url:'<?php echo base_url(); ?>index.php/rka/get_realisasi_rek_alokasi/'+kdskpd,
        		type: "POST",
        		dataType:"json",  
                data      : ({skpd:kdskpd,keg:kgiat,rek5:reke}),                        
        		success:function(data){
					  $.each(data, function(i,n){
					       $("#total_rek5").attr("Value",n['nrealisasi']);
                           $("#total2_rek5").attr("Value",n['nrealisasi']);
					});
				}
            });
		} 


		
        function getSelections(idx){
			var ids = [];
			var rows = $('#dg').edatagrid('getSelections');
			for(var i=0;i<rows.length;i++){
				ids.push(rows[i].kd_rek5);
			}
			return ids.join(':');
		}

		
        function getSelections2(idx){
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


		function refresh(){  
			$('#dg').datagrid('reload');
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
				 url: '<?php echo base_url(); ?>/index.php/rka/select_rka_alokasidana/'+cgiat,
                 idField     : 'id',
                 toolbar     : "#toolbar",              
                 rownumbers  : "true", 
                 fitColumns  : "true",
                 singleSelect: "true",
				 showFooter  : true,
				 nowrap      : false,
				 onSelect:function(rowIndex,rowData){							
							  
                              oldRek   = getSelections(getRowIndex(this));
                              vvkdrek  = rowData.kd_rek5;
                              vvnmrek  = rowData.nm_rek5;
                              vvnilai  = rowData.nilai;
                              vvnilai2  = rowData.nilai_sempurna;
                              vvsdana1 = rowData.sumber1_su;
                              vvsdana2 = rowData.sumber2_su;
                              vvsdana3 = rowData.sumber3_su;
                              vvsdana4 = rowData.sumber4_su;
                              vvnsdana1 = rowData.nsumber1_su;
                              vvnsdana2 = rowData.nsumber2_su;
                              vvnsdana3 = rowData.nsumber3_su;
                              vvnsdana4 = rowData.nsumber4_su;
                                
                              $("#nilairek").attr("value",vvnilai2);
                              $("#nmrek5").attr("value",vvnmrek);
                              $("#kdrek5").combogrid("setValue",vvkdrek);
                              
							  if(vvnilai2!=0){
								$("#sdana1").combogrid("setValue",vvsdana1);
								$("#sdana2").combogrid("setValue",vvsdana2);
								$("#sdana3").combogrid("setValue",vvsdana3);
								$("#sdana4").combogrid("setValue",vvsdana4);
								$("#nilaisumber").attr("value",vvnsdana1);
								$("#nilaisumber2").attr("value",vvnsdana2);
								$("#nilaisumber3").attr("value",vvnsdana3);
								$("#nilaisumber4").attr("value",vvnsdana4);
                              } 
                              
                              cek_kas();
                              get_nilai_real_rek();
                              
						  },
				onLoadSuccess:function(data){
						   	load_sum_rek();
                            get_nilai_real_rek();   		 
						  },
				columns:[[
	                {field:'id',
					 title:'id',
					 width:10,
                     hidden:true
                    },
					{field:'kd_rek5',
					 title:'Rekening',
					 width:12,
					 align:'left'	
					},
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:78
					},
                    {field:'nilai',
					 title:'Nilai Rekening',
					 width:30,
                     align:'right'
                     },
                    {field:'nilai_sempurna',
					 title:'Nilai Rek Sempurna',
					 width:30,
                     align:'right'
                     }, 
					 {field:'rinci',
					  title:'Detail',
					  width:10,
					  align:'center', 
					  formatter:function(value,rec){
							rek         = rec.kd_rek5
							return ' <p onclick="javascript:section('+rec.kd_rek5+');">Rincian</p>';
						}
			 		}
                     /*
                     ,
                     {field:'hapus',title:'Hapus',width:10,align:"center",
                     formatter:function(value,rec){ 
                     return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus();" />';
                     }}
                     */
				]]

			});
		});
        }

        
        function cek_karakter(ckey,_cid){
   		//var key;
			var keychar;
			var ca = document.getElementById(_cid).value; 
        
			var key = ca;
			if(/'/.test(ca))
			{
	           //alert('Ada Tanda Petik di '+_cid+'. '+_cid+' dihapus');
			    document.getElementById(_cid).value = ca.split("'").join("`");
			}
		}
        
        function validate_rek(){
            $(function(){
			$('#dg_rek').edatagrid({
				url          :  '<?php echo base_url(); ?>/index.php/rka/ld_rek/'+kegiatan+'/'+kdrek,
                idField      : 'id',                  
                rownumbers   : "true", 
                fitColumns   : "true",
                singleSelect : "true",
				showFooter   : true,
				nowrap       : false,				 
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
                    onClickRow:function(rowIndex, rowData){                                
                    rk    = rowData.kd_rek5;
                    nmrk  = rowData.nm_rek5;
                    nilai = 0;
                    sdana = 'PAD';                    
                    simpan(rk,oldRek,nilai,sdana);   
					}				 		

			});
		});    
        }
        
        function hapus(){
             
		var a = document.getElementById('skpd').value;
        var b = document.getElementById('giat').value;
        var c =  $("#kdrek5").combogrid("getValue");
        var nrek5 = angka(document.getElementById('total_rek5').value);
        if(nrek5!=0){
            alert('Sudah ada realisasi. Anggaran Tidak bisa dihapus');
            exit;            
        }
        
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({skpd:a,keg:b,rek:c}),
            url:"<?php echo base_url(); ?>index.php/rka/load_sum_rek_rinci_rka",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    total_sem = angka(n['rektotal_rka_sempurna']);
                    total = angka(n['rektotal_rka']);
                    if(total_sem>0){
                        alert('Nilai Penyempurnaan belum 0. Tidak bisa dihapus');
                        exit;
                    }else{
                        if(total>0){
                            alert('Rekening Penyusunan tidak boleh dihapus');
                            exit;
                        }else{
                            hapus2();
                        }
                    }
                    
                });
            }
         });
        });

        }
        
		function hapus2(){
		  
                var nil_cek_kas = document.getElementById('cek_kas').value ;
                
                if ( nil_cek_kas > 0 ) {
                    alert('Rka Sudah di Buat Anggaran Kas...!!!, Hapus Dulu Anggaran Kas nya...!!!');
                    exit();
                }
                
                
                if ( status_apbd=='1' ){
                    alert("APBD TELAH DI SAHKAN...!!!  DATA TIDAK DAPAT DI HAPUS...!!!");
                    exit();
                }
                          
				var cgiat = document.getElementById('giat').value;
				var cskpd = document.getElementById('skpd').value;
				var rek   = getSelections();
				if (rek !=''){
				var del=confirm('Anda yakin akan menghapus rekening '+rek+' ?');
				if  (del==true){
					$(function(){
						$('#dg').edatagrid({
							 url: '<?php echo base_url(); ?>/index.php/rka/thapus_alokasidana/'+cskpd+'/'+cgiat+'/'+rek,
							 idField:'id',
							 toolbar:"#toolbar",              
							 rownumbers:"true", 
							 fitColumns:"true",
							 singleSelect:"true"
						});
					});
                    //get_nilai_kua();
                    $("#kdrek5").combogrid("disable");
                    $("#kdrek5").combogrid("setValue",'');
                    $("#nilairek").attr("value",0);
                    document.getElementById('nmrek5').value = '';
                    $("#sdana1").combogrid("setValue",'');
                    $("#sdana2").combogrid("setValue",'');
                    $("#sdana3").combogrid("setValue",'');
                    $("#sdana4").combogrid("setValue",'');
                    $("#nilaisumber").attr("value",0);
                    $("#nilaisumber2").attr("value",0);
                    $("#nilaisumber3").attr("value",0);
                    $("#nilaisumber4").attr("value",0);
					
                    $("#dg").datagrid("unselectAll");
                    zfrek  = '';
                    zkdrek = '';
  				
				}
				}
		}


		function hapus_rinci(){
		  
                var cgiat = document.getElementById('giat').value;
        		var cskpd = document.getElementById('skpd').value;
                var crek  = document.getElementById('reke').value;
                var norka = cskpd+'.'+cgiat+'.'+crek;
                var nourut  = document.getElementById('nourut').value;
                var penerima  = document.getElementById('penerima').value;
                var total_susun  = angka(document.getElementById('total_edit_susun').value);
                
                var total_awal = angka(document.getElementById('rektotal_rinci').value) ;
                
                var rows        = $('#dg1').edatagrid('getSelected');
                    penerima        = rows.penerima ;
                var total_rinci = angka(rows.total_sem) ;

                var cfm   = confirm("Hapus Penerima "+penerima+" ?") ;

                if(total_susun > 0){
                    alert('Rincian Penyusunan tidak bisa dihapus.');
                    exit;
                }else{
                
                if ( cfm == true ){
                
                    var idx   = $('#dg1').edatagrid('getRowIndex',rows);
                    $('#dg1').datagrid('deleteRow',idx);     
                    $('#dg1').datagrid('unselectAll');
                    
                    
                    var total_rincian = total_awal - total_rinci ;
                    $("#rektotal_rinci").attr("value",number_format(total_rincian,"2",'.',','));
                  
                    /*
                    $(document).ready(function(){
                        $.ajax({
                               type     : 'POST',
                               dataType : 'json',
                               data     : ({vnorka:norka,vnopo:nopo}),
                               url      : '<?php echo base_url(); ?>index.php/rka/thapus_rinci_ar',
                               success  : function(data){
                                        st12=data;
                                        if ( st12=='0'){
                                            alert('Gagal Hapus...!!!');
                                        } else {
                                            section(crek);
                                            alert("Data Telah Terhapus...!!!");
                                        }
                               }    
                        });
                    });
                    */
                }
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
        
        if (harga1==""){
			harga1=0;
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
            
            $(document).ready(function(){
            $.ajax({
                type     : "POST",       
                dataType : 'json',         
                data     : ({skpd:cskpd,giat:cgiat,rek:rk,id:idx,uraian:uraian,volum1:volum1,satuan1:satuan1,harga1:harga1,volum2:volum2,satuan2:satuan2,volum3:volum3,satuan3:satuan3}),
                url      : '<?php echo base_url(); ?>/index.php/rka/tsimpan_rinci',
                success  : function(data){
                    status = data.pesan;
                    load_sum_rek_rinci(rk);
					load_sum_rek_rka(rk);
                    get_nilai_real_rek();
                    $('#dg1').edatagrid('reload');                                                               
                }
            });
           });
		
		}
        
        function detsimpan(){
            
		//var rektotal_rka  = angka(document.getElementById('rektotal_rka').value) ;
		var total_rinci  = angka(document.getElementById('rektotal_rinci').value) ;
		var nrek5 = angka(document.getElementById('total2_rek5').value) ;
        //alert(total_rinci)
		/*if (total_rinci!==0 && total_rinci!== rektotal_rka ){
			alert("Total Rincian Rekening tidak sama dengan Anggaran");
			exit();
			kosong();
			}
		*/
		
        var cgiat = document.getElementById('giat').value;
		var cskpd = document.getElementById('skpd').value;
        var crek =  document.getElementById('reke').value;
        var norka  = cskpd+'.'+cgiat+'.'+crek;
        var ndana1 =  angka(document.getElementById('nilaisumber').value);
        var ndana2 =  angka(document.getElementById('nilaisumber2').value);
        var ndana3 =  angka(document.getElementById('nilaisumber3').value);
        var ndana4 =  angka(document.getElementById('nilaisumber4').value);
        var totaldana = ndana1+ndana2+ndana3+ndana4;
        var sdana1 = $("#sdana1").combogrid("getValue");
        var sdana2 = $("#sdana2").combogrid("getValue");
        var sdana3 = $("#sdana3").combogrid("getValue");
        var sdana4 = $("#sdana4").combogrid("getValue");

        if(sdana1!=''){        
            switch (sdana1) {
                case sdana2:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana3:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana4:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
            } 

        }

        if(sdana2!=''){                 
            switch (sdana2) {
                case sdana1:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana3:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana4:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
            } 
        }
        
        if(sdana3!=''){
            switch (sdana3) {
                case sdana2:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana1:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana4:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
            } 
        }
        
        if(sdana4!=''){
            switch (sdana4) {
                case sdana2:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana3:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana1:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
            }       
        }      
        if((sdana1.replace(/\s/g,"") =="") || ndana1 == undefined) {
            ndana1 = 0;
        }

        if((sdana2.replace(/\s/g,"") =="") || ndana2 == undefined){
            ndana2 = 0;
            
        }
        
        
        if(sdana3.replace(/\s/g,"") =="" || ndana3 == undefined){
            ndana3 = 0;
        }        
        
        if(sdana4.replace(/\s/g,"") =="" || ndana4 == undefined){
            ndana4 = 0;
        } 
        
        if ( cgiat=='' ){
            alert("Pilih Kegiatan Terlebih Dahulu...!!!");
            exit();
        }
        
        if ( crek=='' ){
            alert("Pilih Rekening Terlebih Dahulu...!!!");
            exit();
        }

        if ( total_rinci<nrek5 ){
            alert("Nilai Anggaran tidak boleh lebih kecil dari realisasi !!!");
            exit();
        }


        //var n_sisakua   = angka(document.getElementById('sisa_nilai_kua').value) ;
		var lcrek=crek.substr(0,1);  		  
		/*if (( n_sisakua < total_rinci )&&(lcrek='5')){
			alert('Nilai Melebihi nilai KUA');
			exit();
		}*/

        $('#dg1').datagrid('unselectAll');            
        $('#dg1').datagrid('selectAll');
        var rows = $('#dg1').datagrid('getSelections');
        
		
		//alert(''+rows.length); 
		
        if ( rows.length == 0 ) {
            $(document).ready(function(){
            $.ajax({
                    type     : 'POST',
                    dataType : 'json',
                    data     : ({vnorka:norka}),
                    url      : '<?php echo base_url(); ?>index.php/rka/thapus_alokasi_all',
                    success  : function(data){
                               }    
                        });
                    });
            alert("Data Tersimpan...");
            exit();
        }       
         
        //for(var i=0;i<rows.length;i++){
		for(var i=0;i<rows.length;i++){	
            cpenerima   = rows[i].penerima;
            calamat  	= rows[i].alamat;
			ccad1		= rows[i].cad1;
			cuser = '';
			cpenerima	= cpenerima.split("'").join("`");
			calamat		= calamat.split("'").join("`");

			/*		
			cvolume2  = rows[i].volume2;
            csatuan2  = rows[i].satuan2;
            cvolume3  = rows[i].volume3;
            csatuan3  = rows[i].satuan3; */
            ctotal    = angka(rows[i].total); 
            ctotal_sem = angka(rows[i].total_sem); 
            no        = i + 1 ;           
            
			
            if ( i > 0 ) {
                csql = csql+","+"('"+no+"','"+norka+"','"+cpenerima+"','"+calamat+"','"+ctotal+"','"+ctotal_sem+"','"+ctotal_sem+"','"+no+"','"+cuser+"',getdate())";
            } else {
                csql = "values('"+no+"','"+norka+"','"+cpenerima+"','"+calamat+"','"+ctotal+"','"+ctotal_sem+"','"+ctotal_sem+"','"+no+"','"+cuser+"',getdate())";                                            
            
			} 		//alert(csql);
        }
		
		
        $(document).ready(function(){
                $.ajax({
                    type     : "POST",   
                    dataType : 'json',                 
                    data     : ({no:norka,sql:csql,skpd:cskpd,giat:cgiat,dana1:sdana1,dana2:sdana2,dana3:sdana3,dana4:sdana4,
								vdana1:ndana1,vdana2:ndana2,vdana3:ndana3,vdana4:ndana4}),
                    url      : '<?php echo base_url(); ?>/index.php/rka/tsimpan_alokasidanarinci_sem_jk',
                    success  : function(data){                        
                        status = data.pesan; 
                         if (status=='1'){               
                            alert('Data Berhasil Tersimpan...!!',status);
                        } else{ 
                            alert('Data Gagal Tersimpan...!!!');
                        }                                             
                    }
                });
                });  
                if (status=='1'){               
                            alert('Data Berhasil Tersimpan...!!!!');
                        } else{ 
                            alert('Data Gagal Tersimpan...!!!');
                        }    
        $('#dg1').edatagrid('unselectAll');
        //$('#dg1').edatagrid('reload');
    }
		
    /*    
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
                              po=rowData.no_po;
                              $("#noid").attr("value",detIndex);
                              $("#nopo").attr("value",po);	
						  },
				columns:[[
					{field:'id',
					 title:'id',
					 width:20,
					 hidden:true,
					 editor:{type:"numberbox"}
					},
                    {field:'no_po',
					 title:'no',
					 width:20,
					 hidden:true,
					 editor:{type:"numberbox"}
					},
					{field:'uraian',
					 title:'Uraian',
					 width:260,
					 editor:{type:"text"}
					},
                    {field:'volume1',
					 title:'Vol 1',
					 width:35,
                     align:'right',
					 editor:{type:"numberbox",
						     options:{precision:0,groupSeparator:',',decimalSeparator:'.'}
							} 
                     },
                    {field:'satuan1',
					 title:'Sat 1',
					 width:35,
                     align:'center',
					 editor:{type:"text"},
                     },
                    {field:'volume2',
					 title:'Vol 2',
					 width:35,
                     align:'right',
					 editor:{type:"numberbox",
						     options:{precision:0,groupSeparator:',',decimalSeparator:'.'}
							} 
                     },
                    {field:'satuan2',
					 title:'Sat 2',
					 width:35,
                     align:'center',
					 editor:{type:"text"},
                     },
                    {field:'volume3',
					 title:'Vol 3',
					 width:35,
                     align:'right',
					 editor:{type:"numberbox",
						     options:{precision:0,groupSeparator:',',decimalSeparator:'.'}
							} 
                     },
                    {field:'satuan3',
					 title:'Sat 3',
					 width:35,
                     align:'center',
					 editor:{type:"text"},
                     },
                    {field:'volume',
					 title:'T-Vol',
					 width:35,
                     align:'center'
                     },
                    {field:'harga1',
					 title:'Harga',
					 width:90,
                     align:'right',
					 editor:{type:"numberbox",
						     options:{precision:0,groupSeparator:',',decimalSeparator:'.'}
							} 
                     },
                    {field:'total',
					 title:'Total',
					 width:130,
                     align:'right'
                     }
				]]	
			
			});
		});
        */
        
        
            
        $(function(){
       	    var mskpd = document.getElementById('skpd').value;
            var mgiat = document.getElementById('giat').value;
			$('#dg1').edatagrid({
				 rowStyler:function(index,row){
					//if (row.cad1==1){
					//return {class:'r1', style:{'color:#fff'}};
						//return 'background-color:#6293BB;color:#fff;';
					 //  return 'color:red;font-weight:bold;';
						//font-weight:bold;
					//}
				 },
				 url           : '<?php echo base_url(); ?>/index.php/rka/alokasidana_rinci',
                 idField      : 'id',
                 toolbar      : "#toolbar1",              
                 rownumbers   : "true", 
                 fitColumns   : false,
                 singleSelect : "true",
				 onAfterEdit  : function(rowIndex, rowData, changes){								
							    },
				 onSelect:function(rowIndex, rowData, changes){							
							  detIndex=rowIndex;
                              nourut=rowData.no_urut;
                              $("#noid").attr("value",detIndex);
                              $("#nourut").attr("value",nourut);	
                              
						  },
				columns:[[
				
					{field:'id',
					 title:'id',
					 width:20,
                     hidden:true
					},
					{field:'cad1',
					 title:'No',
					 width:20,
                     align:'left'
                     },
					{field:'penerima',
					 title:'Penerima',
					 width:240,
					 align:'left'
					},
                    {field:'alamat',
					 title:'Alamat',
					 width:380,
                     align:'left'
                     },
                    {field:'total_sem',
					 title:'Total Sempurna',
					 width:150,
                     align:'right',
                     styler: function(value,row,index){
						return 'background-color:#d9e3fc;color:red;font-weight:bold;';
					 },
					 },
                    {field:'total',
					 title:'Total',
					 width:150,
                     align:'right',
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
			var a     = kdrek ;
            
            if ( mgiat=='' ){
                alert("Pilih Kegiatan Terlebih Dahulu...!!!");
            }
            
            if ( a=='' ){
                alert("Pilih Rekening Terlebih Dahulu...!!!");
            }
            

            $(document).ready(function(){
 			    $("#reke").attr("value",a);            
				$('#section2').click();
                $(function(){
        			$('#dg1').edatagrid({
       				     url          : '<?php echo base_url(); ?>/index.php/rka/rka_rinci_alokasi/'+mskpd+'/'+mgiat+'/'+kdrek,
                         idField      : 'id',
                         toolbar      : "#toolbar1",              
                         rownumbers   : "true", 
                         fitColumns   : false,
						 title        : a,
                         singleSelect : "true",
						 onSelect     : function(rowIndex,rowData){
										$("#id_edit").attr("value",rowData.id) ;
										$("#cad1_edit").attr("value",rowData.cad1) ;
										$("#penerima_edit").attr("value",rowData.penerima) ;
										$("#alamat_edit").attr("value",rowData.alamat) ;
							            $("#nourut_edit").attr("value",rowData.no_urut) ;
										
										/*stsheader    = rowData.header;
										if (stsheader==1){            
											$("#header_po_edit").attr("checked",true);
										} else {
											$("#header_po_edit").attr("checked",false);
										}
									   $("#kode_edit").attr("value",rowData.kode) ;
                                       $("#uraian_edit").attr("value",rowData.uraian) ;
                                       $("#vol1_edit").attr("value",rowData.alamat) ;
                                       $("#vol2_edit").attr("value",rowData.volume2) ;
                                       $("#vol3_edit").attr("value",rowData.volume3) ;
                                       $("#sat1_edit").attr("value",rowData.satuan1) ;
                                       $("#sat2_edit").attr("value",rowData.satuan2) ;
                                       $("#sat3_edit").attr("value",rowData.satuan3) ;
                                       $("#harga_edit").attr("value",rowData.harga1) ;
                                       $("#nopo_edit").attr("value",rowData.no_po) ;
                                       */
                                       var rows_e     = $('#dg1').edatagrid('getSelected');
                                       var idx_ins_e  = $('#dg1').edatagrid('getRowIndex',rows_e);
                                       
									   //var ntotal_edit = angka(rowData.total) ;                                      
                                       //var ntotal_edit = angka(total_edit) ;
                                       
                                       $("#noid_edit").attr("value",idx_ins_e);
                                       $("#total_edit").attr("value",rowData.total_sem);
									   $("#totallama_edit").attr("value",rowData.total_sem);
                                       $("#total_edit_susun").attr("value",rowData.total);
                                       
                                                                              
                       },
                       onDblClickRow  : function(rowIndex,rowData){
                                        $("#dialog-modal-edit").dialog('open');
                                        document.getElementById('total_edit').focus() ;
                       },
                       /*onAfterEdit  : function(rowIndex, rowData, changes){								
										urai = rowData.uraian;
										idx  = rowData.no_po;
										
										vol1=rowData.volume1;
										sat1=rowData.satuan1;
										har1=rowData.harga1;

										vol2=rowData.volume2;
										sat2=rowData.satuan2;

										vol3=rowData.volume3;
										sat3=rowData.satuan3;

										simpan_rincian(idx,urai,vol1,sat1,har1,vol2,sat2,vol3,sat3,kdrek);
								     },*/
						onLoadSuccess:function(data){
										load_sum_rek_rinci(kdrek);	
										load_sum_rek_rka(kdrek);
                                        get_nilai_real_rek();
										
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
	   
	   function section3(){
		// validate_combo();
         $(document).ready(function(){    
             $('#section3').click();                                               
         });
       }


    function load_sum_rek(){                
		var a = document.getElementById('skpd').value;
        var b = document.getElementById('giat').value;
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({skpd:a,keg:b}),
            url:"<?php echo base_url(); ?>index.php/rka/load_sum_rek_alokasidana",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rektotal").attr("value",n['rektotal_sempurna']);
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
            url:"<?php echo base_url(); ?>index.php/rka/load_sum_rek_alokasidana_rinci",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rektotal_rinci").attr("value",n['rektotal_rinci_sem']);
                });
            }
         });
        });
    }
    
    
    function input(){
        $("#kdrek5").combogrid("setValue","");
        var cek_giat = $("#kdgiat").combogrid('getValue');
        
        if ( cek_giat=='' ){
            alert('Pilih Kegiatan Terlebih Dahulu...!!!');
            exit();
        }
        
		//$("#kdrek5").combogrid("setValue",'');
        $("#kdrek5").combogrid("enable");
        $("#kdrek5").combogrid("setValue",'');
        $("#sdana1").combogrid("setValue",'');
        $("#sdana2").combogrid("setValue",'');
        $("#sdana3").combogrid("setValue",'');
        $("#sdana4").combogrid("setValue",'');
        
        document.getElementById('nilairek').value    = 0;
        document.getElementById('nilairek').disabled = false
        document.getElementById('nmrek5').value      = '';
        document.getElementById('nourut').value        = '';
        validate_rekening();
    }
    

    function tambah(){

        var skpd   = document.getElementById('skpd').value;
        var kegi   = $("#kdgiat").combogrid("getValue");
        var reke   = $("#kdrek5").combogrid("getValue");
        var nmrek5 = document.getElementById('nmrek5').value;
        var nrek   = angka(document.getElementById('nilairek').value) ;
        var sdana1 = $("#sdana1").combogrid("getValue");
        var sdana2 = $("#sdana2").combogrid("getValue");
        var sdana3 = $("#sdana3").combogrid("getValue");
        var sdana4 = $("#sdana4").combogrid("getValue");
        var ndana1   = angka(document.getElementById('nilaisumber').value) ;
        var ndana2   = angka(document.getElementById('nilaisumber2').value) ;
        var ndana3   = angka(document.getElementById('nilaisumber3').value) ;
        var ndana4   = angka(document.getElementById('nilaisumber4').value) ;
		var nrek5_real = angka(document.getElementById('total_rek5').value) ;
        
		if(sdana1!=''){        
            switch (sdana1) {
                case sdana2:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana3:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana4:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
            } 

        }

        if(sdana2!=''){                 
            switch (sdana2) {
                case sdana1:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana3:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana4:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
            } 
        }
        
        if(sdana3!=''){
            switch (sdana3) {
                case sdana2:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana1:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana4:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
            } 
        }
        
        if(sdana4!=''){
            switch (sdana4) {
                case sdana2:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana3:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
                case sdana1:
                    alert('Ada Nama Sumber yg Sama!!!');
                    return;
                break;
            }       
        }      
        if((sdana1.replace(/\s/g,"") =="") || ndana1 == undefined) {
            ndana1 = 0;
        }

        if((sdana2.replace(/\s/g,"") =="") || ndana2 == undefined){
            ndana2 = 0;
            
        }
        
        
        if(sdana3.replace(/\s/g,"") =="" || ndana3 == undefined){
            ndana3 = 0;
        }        
        
        if(sdana4.replace(/\s/g,"") =="" || ndana4 == undefined){
            ndana4 = 0;
        }
		
		var totaldana=ndana1+ndana2+ndana3+ndana4;
        
		if(nrek != totaldana){
            alert('Nilai Sumber Dana Masih Selisih dengan Nilai Rekening');
            return;
        } 
		
		//var n_sisakua   = angka(document.getElementById('sisa_nilai_kua').value) ;

        if(nrek < nrek5_real){
            alert('Anggaran tidak boleh lebih kecil dari realisasi...!!!');
            exit();            
        }

        if ( kegi == '' ){
            alert('Pilih Kode Kegiatan Terlebih Dahulu...!!!');
            exit();
        }
        if ( reke == '' ){
            alert('Pilih Rekening Terlebih Dahulu...!!!');
            exit();
        }
		lcrek=reke.substr(0,1);
		/*	
        if (( n_sisakua < nrek )&&(lcrek='5')){
            alert('Nilai Melebihi nilai KUA');
            exit();
        }*/
        

        $("#dg").datagrid("selectAll");
        var rows = $("#dg").datagrid("getSelections");
        var jrow = rows.length - 1;
        jidx     = jrow + 1 ;

        $("#dg").edatagrid('appendRow',{kd_rek5:reke,nm_rek5:nmrek5,nilai:nrek});
        //$('#dg').datagrid('appendRow',{kd_kegiatan:xkdkegi,nm_kegiatan:xnmkegi,jns_kegiatan:xjns,lanjut:xljt});

        $(document).ready(function(){
        $.ajax({
           type     : "POST",
           dataType : "json",
           data     : ({kd_skpd:skpd,kd_kegiatan:kegi,kd_rek5:reke,nilai:nrek,dana1:sdana1,dana2:sdana2,dana3:sdana3,dana4:sdana4,vdana1:ndana1,vdana2:ndana2,vdana3:ndana3,vdana4:ndana4}),
           url      : '<?php echo base_url(); ?>index.php/rka/tsimpan_sempurna', 
           success  : function(data){
                      st12 = data;
                      if ( st12 == '1' ){
                        alert("Data Tersimpan...!!!");
						//get_nilai_kua();
                      } else {
                        alert("Gagal Simpan...!!!");
                      }
                      }
        });
        });
		
        $("#dg").datagrid("unselectAll");
        validate_combo();
        $('#dg').datagrid('reload');
        $("#kdrek5").combogrid("disable");
        $("#kdrek5").combogrid("setValue",'');
        $("#nilairek").attr("value",0);
        document.getElementById('nmrek5').value = '';
        $("#sdana1").combogrid("setValue",'');
        $("#sdana2").combogrid("setValue",'');
        $("#sdana3").combogrid("setValue",'');
        $("#sdana4").combogrid("setValue",'');
		$("#nilaisumber").attr("value",0);
        $("#nilaisumber2").attr("value",0);
        $("#nilaisumber3").attr("value",0);
        $("#nilaisumber4").attr("value",0);
    }
    
    
    function btl(){
        //$("#kdrek5").combogrid("setValue",'');
        //$("#kdrek5").combogrid("setValue",'');
        $("#sdana1").combogrid("setValue",'');
        $("#sdana2").combogrid("setValue",'');
        $("#sdana3").combogrid("setValue",'');
        $("#sdana4").combogrid("setValue",'');
		 $("#nilaisumber").attr("value",0);
        $("#nilaisumber2").attr("value",0);
        $("#nilaisumber3").attr("value",0);
        $("#nilaisumber4").attr("value",0);
		//$("#kdrek5").combogrid("disable");
        document.getElementById('nilairek').value = 0;
        document.getElementById('nmrek5').value   = '';
        $("#dg").datagrid("unselectAll");
        
    }
    

    function keluar(){
        $("#dialog-modal").dialog('close');
        $('#dg_rek').datagrid('unselectAll');
        $('#dg').edatagrid('reload');
    } 
      

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
        var lalu = angka(document.getElementById('ang_lalu').value);
        if ( b=='' ){
            alert('Pilih Kegiatan Terlebih Dahulu...!!!');
            exit();
        }


		$(function(){      
         $.ajax({
            type: 'POST',
            data: ({skpd:a,giat:b,lokasi:c,sasaran:d,wkeg:e,cp_tu:f,cp_ck:g,m_tu:h,m_ck:i,k_tu:j,k_ck:k,h_tu:l,h_ck:m,ttd:n,lalu:lalu}),
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
                    $("#ang_lalu").attr("value",n['ang_lalu']);
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
					 width:780,
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
            var mkdrek5 = $("#kdrek5").combogrid("getValue");
			//alert(mkdrek5);
			$('#dg2').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/rka/rka_hukum/'+mskpd+'/'+mgiat+'/'+mkdrek5,
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
					 width:780,
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
        var mkdrek5 = $("#kdrek5").combogrid("getValue");
		//alert();
		$(function(){      
         $.ajax({
            type: 'POST',
            data: ({skpd:a,giat:b,cisi:isi,rek5:mkdrek5}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/rka/simpan_dhukum",
			success:function(data){ 
					alert('Data Tersimpan');
					}
         });
        });
    }
    
    function tombol(st){  
    if (st=='1'){
            $('#add').linkbutton('disable');
            $('#input').linkbutton('disable');
            $('#btl').linkbutton('disable');
            $('#del').linkbutton('disable');
            $('#save').linkbutton('disable');
            $('#cancel').linkbutton('disable');
            $('#add1').linkbutton('disable');
            $('#del1').linkbutton('disable');
            $('#save1').linkbutton('disable');
            $('#cancel1').linkbutton('disable');
            $('#insert1').linkbutton('disable');
            $('#save2').linkbutton('disable');
            $('#save4').linkbutton('disable');
            document.getElementById("p1").innerHTML="APBD TELAH DI - SAH - KAN...!!!";
            status_apbd = '1';
            
     } else {
            $('#add').linkbutton('enable');
            $('#input').linkbutton('enable');
            $('#btl').linkbutton('enable');
            $('#del').linkbutton('enable');
            $('#save').linkbutton('enable');
            $('#cancel').linkbutton('enable');
            $('#add1').linkbutton('enable');
            $('#del1').linkbutton('enable');
            $('#save1').linkbutton('enable');
            $('#cancel1').linkbutton('enable');
            $('#insert1').linkbutton('enable');
            $('#save2').linkbutton('enable');
            $('#save4').linkbutton('enable');
            
            document.getElementById("p1").innerHTML="";
            status_apbd = '0';
            
     }
    }
    
    
       
       
       
       function insert_row() {
            var crek  = document.getElementById('reke').value;
            var rows     = $('#dg1').edatagrid('getSelected');
            var idx_ins  = $('#dg1').edatagrid('getRowIndex',rows);
                
            if ( idx_ins == -1){
                alert("Pilih Lokasi Insert Terlebih Dahulu...!!!") ;
                exit();
            }

            $('#dg1').datagrid('selectAll');
            var rows_grid = $('#dg1').datagrid('getSelections');
            for ( var i=idx_ins; i<rows_grid.length; i++ ) {            
                  $('#dg1').edatagrid('updateRow',{index:i,row:{id:i+1,no_po:i+1}});
            }
            $('#dg1').datagrid('unselectAll');
			
			var cad1 = document.getElementById('kode_cad1').value;
			if (cad1==''){
				alert('Isi No Terlebih dahulu!');
				exit();
			}
			var penerima = document.getElementById('penerima').value;
			if (penerima==''){
				alert('Isi Penerima Terlebih dahulu!');
				exit();
			}
			var alamat = document.getElementById('alamat').value;			
            var total  = document.getElementById('total').value;
            //var total  = document.getElementById('total').value;
            
            var vtotal = angka(total);
			var total_awal  = angka(document.getElementById('rektotal_rinci').value) ;
            var total_rinci = vtotal + total_awal; 
			/*var n_sisakua   = angka(document.getElementById('sisa_nilai_kua').value) ;
			var lcrek=crek.substr(0,1);  		  
			if (( n_sisakua < total_rinci )&&(lcrek='5')){
				alert('Nilai Melebihi nilai KUA');
				exit();
			}*/
			
            total = number_format(vtotal,2,'.',',');
	   
            $('#dg1').edatagrid('insertRow',{index:idx_ins,row:{cad1:idx_ins+1,penerima:penerima,alamat:alamat,total_sem:total,total:'0.00'}});
            $("#dg1").datagrid("unselectAll");
         
            $("#rektotal_rinci").attr("value",number_format(total_rinci,2,'.',','));
			
            kosong();
            
    }
       
    
    function kosong(){
            $("#kode_cad1").attr("value","");
            $("#penerima").attr("value","");
            $("#alamat").attr("value","");
            $("#total").attr("value","");
            document.getElementById('kode_cad1').focus();            
    }
        
    
    function enter(ckey,_cid){
        if (ckey==13)
        	{    	       	       	    	   
        	   document.getElementById(_cid).focus();
               if(_cid=='penerima'){
                    var as_kdgiat = $("#kdgiat").combogrid("getValue") ;
                    var as_reke   = document.getElementById('reke').value ;
                    
                    if ( as_kdgiat=='' ){
                        alert('Pilih Kegiatan Terlebih Dahulu...!!!');
                        exit();
                    }
                    if ( as_reke=='' ){
                        alert("Pilih Rekening Terlebih Dahulu...!!!");
                        exit();
                    }
                    append_save();
               }
        	}     
        }
    

   function append_save() {
         
       
            var cgiat = document.getElementById('giat').value;
    		var cskpd = document.getElementById('skpd').value;
            var crek  = document.getElementById('reke').value;
            var norka = cskpd+'.'+cgiat+'.'+crek;
            
            $("#dg1").datagrid("unselectAll");
            $('#dg1').datagrid('selectAll');
            var rows   = $('#dg1').datagrid('getSelections') ;
                jgrid  = rows.length ;
				
            var cad1 = document.getElementById('kode_cad1').value;
            var penerima = document.getElementById('penerima').value;
			var alamat = document.getElementById('alamat').value;
			if(penerima == ''){
				alert('Penerima Tidak Boleh Kosong!!');
				exit();
			}
			
			//alert(''+penerima);
            var nilai  = document.getElementById('total').value ;
			if ( nilai == '' ){
				var harga = 0;				
			}else {
				harga = angka(nilai);
			}

            var total  = harga;
            
            var ftotal = number_format(total,2,'.',',');           
            var total_awal  = angka(document.getElementById('rektotal_rinci').value) ;
            var total_rinci = total + total_awal; 			
			
            var id     = jgrid  ;
            
			//alert(''+id);
            $('#dg1').edatagrid('appendRow',{id:id,cad1:id+1,penerima:penerima,alamat:alamat,total_sem:ftotal,total:'0.00'});
            $("#dg1").datagrid("unselectAll");
			
			
			$('#dg1').datagrid('selectAll');
			var rows   = $('#dg1').datagrid('getSelections') ;
            jgrid  = rows.length ;
			//alert(''+jgrid+'');
			
			//alert(tvol);
			
            $("#rektotal_rinci").attr("value",number_format(total_rinci,2,'.',','));
            kosong();
       
       }
     
      
 /*    function standard_harga(){

        $("#dialog-modal").dialog("open");
        var crekening = document.getElementById('reke').value ;
		//alert(crekening);
        $('#dg_std').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_daftar_harga_detail_ck',
        queryParams   : ({rekening:crekening}),
        idField       : 'id',
        rownumbers    : true, 
        fitColumns    : false,
        singleSelect  : false,
        columns       : [[{field:'id',        title:'id',           width:70, align:"left",hidden:"true"},
                          //{field:'no_urut',   title:'No Urut',      width:70, align:"left",hidden:"true"},
                          {field:'kd_rek5',   title:'Rekening',     width:80, align:"left",hidden:"true"},
                          {field:'uraian',    title:'Uraian',       width:510,align:"left"},
                          //{field:'merk',      title:'Merk',         width:100,align:"left"},
                          {field:'satuan',    title:'Satuan',       width:100,align:"left"},
                          {field:'harga',     title:'Harga',        width:150,align:"right"},
                          {field:'ck',        title:'ck',           checkbox:true}
                         ]]
        });
        selectall_std();		 
     }   */
     
     function kembali_std(){
        $("#section2").click();
        $("#dialog-modal").dialog("close");
     }

     var sell_std = new Array();
	 var max_std  = 0;
	  
     function getcek_std(){
    	var ids   = [];  
		var a     = null;
		var rows  = $('#dg_std').edatagrid('getSelections');  
		for(var i=0; i<rows.length; i++){  
		    a       = rows[i].ck;
			max_std = i;
			if (a!=null){
				sell_std[i]=a-1;
			}else{
				sell_std[i]=1000;			
			}
		}  
	  }
	
	 
     function setcek_std(){
		for(var i=0; i<max+1; i++){ 
			if (sell_std[i]!=1000){
				selectRecord_std(sell_std[i]);
			}
		} 		
	 }


	 function selectall_std(){
		max_std = 0;
		$('#dg_std').edatagrid('selectAll');
		getcek_std();
		Unselectall_std();
		setcek_std();
	 }

	 
     function Unselectall_std(){
		$('#dg_std').edatagrid('unselectAll');
	 }
     
     function selectRecord_std(rec){
		$('#dg_std').edatagrid('selectRecord',rec);
	 }
     
 /*    function pilih_std(){

		var ids  = [];  
		var rows = $('#dg_std').edatagrid('getSelections');  
		for(var i=0; i<rows.length; i++){  

		    var urai_std  = rows[i].uraian;
		    var satu_std  = rows[i].satuan;
		    var harga_std = rows[i].harga;
            
            var vol1      = 1;
            var vol2      = '';
            var vol3      = '';
            var tvol      = vol1;

            var sat2      = '';
            var sat3      = '';
            
            var fharga    = number_format(harga_std,2,'.',',');
            var ftotal    = number_format(harga_std,2,'.',',');
            var total     = harga_std;
            
            $("#dg1").datagrid("unselectAll");
            $('#dg1').datagrid('selectAll');
            var rows_2 = $('#dg1').datagrid('getSelections') ;
                jgrid  = rows_2.length ;
           
            var id     = jgrid  ;
            
            $('#dg1').edatagrid('appendRow',{uraian:urai_std,volume1:vol1,satuan1:satu_std,volume2:vol2,satuan2:sat2,volume3:vol3,volume:tvol,satuan3:sat3,harga1:fharga,id:id,total:ftotal});
            $("#dg1").datagrid("unselectAll");
            
            var total_awal  = angka(document.getElementById('rektotal_rinci').value) ;
            var total_rinci = angka(total)
                total_rinci = total_rinci + total_awal; 
            
            $("#rektotal_rinci").attr("value",number_format(total_rinci,2,'.',','));
            kosong();
            $("#dialog-modal").dialog("close");
            
        }  
	}*/
    
    function kembali_edit(){
        $("#section2").click();
        $("#dialog-modal-edit").dialog("close");
    }
    
    
    function pilih_edit(){
            var cad1_edit = document.getElementById('cad1_edit').value;
            var penerima_edit = document.getElementById('penerima_edit').value;
            var alamat_edit = document.getElementById('alamat_edit').value;
			var btotallama = document.getElementById('totallama_edit').value;
			var vbtotallama = angka(btotallama);
			var btotalbaru = document.getElementById('total_edit').value;
			var vbtotalbaru = angka(btotalbaru);
            var ftotal_edit = number_format(vbtotalbaru,2,'.',',');    
            var ftotal_susun = document.getElementById('total_edit_susun').value;  
            //alert(''+ftotal_susun);
            var idx_ins_edit  = document.getElementById('noid_edit').value;
            
            $('#dg1').edatagrid('updateRow',{index:idx_ins_edit,row:{cad1:cad1_edit,penerima:penerima_edit,alamat:alamat_edit,total_sem:ftotal_edit,total:ftotal_susun}});
            $("#dg1").datagrid("unselectAll");
            
            var total_awal_edit  = angka(document.getElementById('rektotal_rinci').value) ;
            //alert(''+total_awal_edit); //200
			var total_rinci_edit = 0; //500
                total_rinci_edit = total_awal_edit + vbtotalbaru - vbtotallama    ; 
			//alert(''+total_awal_edit +'+'+ vbtotalbaru+ '-'+ vbtotallama);	
			
			//200 + 500 - 200
            $("#rektotal_rinci").attr("value",number_format(total_rinci_edit,2,'.',','));
            $("#dialog-modal-edit").dialog('close');
			//$("#dg1").datagrid("reload");

            kosong();
            
            //$("#dg1").datagrid("reload");
    
    }
	
	
	 function copy_edit(){
		    var kode_copy = document.getElementById('uraian_edit').value;
            var uraian_copy = document.getElementById('uraian_edit').value;
            var vol1_copy   = document.getElementById('vol1_edit').value;
            if ( vol1_copy == '' ){
                 volu1_copy = 1;
            }else{
                volu1_copy  = vol1_copy;
            }
            var sat1_copy = document.getElementById('sat1_edit').value;
            var vol2_copy = document.getElementById('vol2_edit').value;
            
            if ( vol2_copy == '' ){
                 volu2_copy = 1 ;
            }else{
                volu2_copy  = vol2_copy;
            }
            var sat2_copy = document.getElementById('sat2_edit').value;
            var vol3_copy = document.getElementById('vol3_edit').value;
            if ( vol3_copy == '' ){
                 volu3_copy = 1 ;
            }else{
                volu3_copy = vol3_copy ;
            }
            var sat3_copy   = document.getElementById('sat3_edit').value;
            //var nilai_copy  = document.getElementById('harga_edit').value ;
            var nilai_copy  = document.getElementById('total_edit').value ;
            $("#dialog-modal-edit").dialog('close');
            kosong();
    }

	function paste_copy(){
            var uraian_copy = document.getElementById('uraian_edit').value;
            var vol1_copy   = document.getElementById('vol1_edit').value;
            if ( vol1_copy == '' ){
                 volu1_copy = 1;
            }else{
                volu1_copy  = vol1_copy;
            }
            var sat1_copy = document.getElementById('sat1_edit').value;
            var vol2_copy = document.getElementById('vol2_edit').value;
            
            if ( vol2_copy == '' ){
                 volu2_copy = 1 ;
            }else{
                volu2_copy  = vol2_copy;
            }
            var sat2_copy = document.getElementById('sat2_edit').value;
            var vol3_copy = document.getElementById('vol3_edit').value;
            if ( vol3_copy == '' ){
                 volu3_copy = 1 ;
            }else{
                volu3_copy = vol3_copy ;
            }
            var sat3_copy   = document.getElementById('sat3_edit').value;
            var nilai_copy  = document.getElementById('harga_edit').value ;
            $("#dialog-modal-edit").dialog('close');
            kosong();
			
			$("#kode_header").attr("value",kode_copy);
			$("#uraian").attr("value",uraian_copy);
            $("#vol1").attr("value",volu1_copy);
            $("#sat1").attr("value",sat1_copy);
            $("#vol2").attr("value",volu2_copy);
            $("#sat2").attr("value",sat2_copy);
            $("#vol3").attr("value",volu3_copy);
            $("#sat3").attr("value",sat3_copy);
            $("#harga").attr("value",nilai_copy);
            document.getElementById('harga').focus();
			
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

   <?php echo $prev; ?><p id="p1" style="font-size: x-large;color: red;"></p><br />
   <table style="border-collapse:collapse;border-style:hidden;" width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
   <tr style="border-style:hidden;">
   <td>S K P D&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="sskpd" name="sskpd" readonly="true" style="width:170px;border: 0;" />
   &nbsp;&nbsp;<input id="nmskpd" name="nmskpd" readonly="true" style="width: 620px; border:0;  " /></td>
   </tr>
   <tr style="border-style:hidden;">
   <td>KEGIATAN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="kdgiat" name="kdgiat" style="width:170px;" />  
   &nbsp;&nbsp;&nbsp;<input id="nmgiat" name="nmgiat" readonly="true" style="width:620px;border:0;background-color:transparent;color: black;" disabled="true"/>
   <input type="hidden" id="jnskegi" name="jnskegi" style="width:20px;" /></td>
   </tr>
   </table>

<div id="accordion">



<h2><a href="#" id="section1" onclick="javascript:validate_combo()">Rekening Anggaran</a></h2>
   
   <div  style="height:700px;">      
   
       <table border='1'  style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;width:880px;border-style: ridge;" >
       
       <tr style="border-bottom-style:hidden;">
       <td colspan="5" style="border-bottom-style:hidden;"></td>
       </tr>
       
       <tr style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;">
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;">REKENING</td>
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:770px;border-bottom-style:hidden;" colspan="4"><input id="kdrek5" name="kdrek5" style="width:170px;" />  
           <input id="nmrek5" name="nmrek5" readonly="true" style="width:570px;border:0;background-color:transparent;color:black;" disabled="true" />
       </td>
       </tr>
       
       <tr style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;">
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;">NILAI</td>
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:170px;border-bottom-style:hidden;border-right-style:hidden;"><input id="nilairek" type="decimal" name="nilairek" style="width:170px;text-align:right;" onkeypress="javascript:enter(event.keyCode,'add');return(currencyFormat(this,',','.',event))"/><input type="hidden" id="cek_kas" name="cek_kas" style="width:170px;text-align:right;"/></td>
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:600px;border-bottom-style:hidden;" colspan="3"></td>
       </tr>
       <tr style="border-bottom-style:hidden;">
       <td colspan="5" align="center" style="border-bottom-style:hidden;">
       <button id="input" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:input()">Tambah</button>
       <button id="btl" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:btl()">Batal</button>
       <button id="delrek" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus()">Hapus</button>
       <button id="add" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:tambah()">Simpan</button>
       </td>
       </tr>
       <tr style="border-bottom-color:black;height:1px;" >
       <td colspan="5" style="border-bottom-color:black;height:1px;"></td>
       </tr>
       </table>
       
       <!--<table border='0' width="100%" >
       <tr>
       <td align='right'>
       <button id="add" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</button>
       <button id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</button>
       <button id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('addRow');">Simpan</button>
       <button id="cancel" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">Batal</button>
       </td>
       </tr>
       </table>-->
       
       <table id="dg" title="Input Rekening Rencana Kegiatan Anggaran" style="width:880px;height:400px;" >          
       </table>  
        <div id="toolbarx">
    		&nbsp;&nbsp; <button  class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="javascript:refresh()">Refresh Tabel</button>
            <button  class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section3()">Hukum</button>
    		<!--<button id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</button>-->
    		<!--<button id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('addRow');">Simpan</button>-->
    		<!--<button id="cancel" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">Batal</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
            <table style="width:880px;height:10px;border-style:hidden;">
            <tr><td align="right">
			<button id="reload" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="javascript:refresh();">Refresh Tabel</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <B>Total Sempurna</B>&nbsp;&nbsp;<input class="right" type="text" name="rektotal" id="rektotal"  style="width:200px;text-align:right;"  readonly="true"/>
            </td></tr>
			<tr>
			<tr><td align="right">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <B>Realisasi Rek5</B>&nbsp;&nbsp;<input class="right" value="0" type="text" name="total_rek5" id="total_rek5"  style="width:200px;text-align:right;"  readonly="true"/>
            </td></tr>

            
            <!--<td align="right">
			<button id="reload" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="javascript:get_nilai_kua();">Refresh Nilai KUA</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<B>Plafond (KUA-PPAS)</B>&nbsp;&nbsp;<input class="right" type="text" name="nilai_kua" id="nilai_kua"  style="width:200px;text-align:right;"  readonly="true"/>-->
            </td></tr>
			<tr><!--<td align="right">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <B>Total Keseluruhan</B>&nbsp;&nbsp;<input class="right" type="text" name="nilai_kua_ang" id="nilai_kua_ang"  style="width:200px;text-align:right;"  readonly="true"/>
            </td>--></tr>
			<tr><!--<td align="right">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <B>Sisa Plafond (KUA-PPAS)</B>&nbsp;&nbsp;<input class="right" type="text" name="sisa_nilai_kua" id="sisa_nilai_kua"  style="width:200px;text-align:right;"  readonly="true"/>-->
            </td></tr>
            </table>
        </div>
    </div>
    
<h3><a href="#" id="section2">Rincian Rekening</a></h3>
    
    <div>

       </table>
		<table border>
        <tr style="border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;">
            <td colspan='8' style="border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;"><input type="hidden" id="noid" style="width: 200px;" /><input type="hidden" id="nourut" style="width: 200px;" /><input type="hidden" id="reke" style="width: 200px;" /></td>

       <tr style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;">
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;">SUMBER DANA</td>
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:170px;border-bottom-style:hidden;border-right-style:hidden;"><input id="sdana1" name="sdana1" style="width:170px;"/></td>  
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:170px;border-bottom-style:hidden;border-right-style:hidden;"><input id="sdana2" name="sdana2" style="width:170px;"/></td>  
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:170px;border-bottom-style:hidden;border-right-style:hidden;"><input id="sdana3" name="sdana3" style="width:170px;"/></td> 
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:260px;border-bottom-style:hidden;"><input id="sdana4" name="sdana4" style="width:170px;"/></td> 
       </tr>
       
       
       <tr style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;">
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;">Nilai S.DANA</td>
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:170px;border-bottom-style:hidden;border-right-style:hidden;"><input id="nilaisumber" name="nilaisumber"  value='0.00' type="decimal" style="width:170px;text-align:right;" onkeypress="javascript:return(currencyFormat(this,',','.',event))"/></td>  
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:170px;border-bottom-style:hidden;border-right-style:hidden;"><input id="nilaisumber2" name="nilaisumber2" type="decimal" value='0.00'  style="width:170px;text-align:right;" onkeypress="javascript:return(currencyFormat(this,',','.',event))"/></td>  
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:170px;border-bottom-style:hidden;border-right-style:hidden;"><input id="nilaisumber3" name="nilaisumber3" type="decimal" value='0.00'  style="width:170px;text-align:right;" onkeypress="javascript:return(currencyFormat(this,',','.',event))"/></td> 
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:260px;border-bottom-style:hidden;"><input id="nilaisumber4" type="decimal" name="nilaisumber4" value='0.00' style="width:170px;text-align:right;" onkeypress="javascript:return(currencyFormat(this,',','.',event))"/></td> 
       </tr>
       <table>
       
        <tr style="border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;">
			<td style="border-right-style:hidden; border-bottom-style:hidden;border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;">No</td>
			<td style="border-right-style:hidden; border-bottom-style:hidden;border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;">Penerima</td>
            <td style="border-right-style:hidden; border-bottom-style:hidden;border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;">Alamat</td>
            <td style="border-bottom-style:hidden;border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;">Total</td>
        </tr>
        <tr style="border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;">
            <td style="text-align:center; border-right-style:hidden; border-bottom-color:black;border-style-bottom:hidden;border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;"> <input type="number" id="kode_cad1" style="width: 30px;"/></td>
            <td style="text-align:center; border-right-style:hidden; border-bottom-color:black;border-style-bottom:hidden;border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;"> <input type="text" id="penerima" style="width: 400px;"onblur="javascript:cek_karakter(event.keyCode,'penerima');"/></td>
            <td style="text-align:center; border-right-style:hidden; border-bottom-color:black;border-style-bottom:hidden;border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;"> <input type="text" id="alamat" style="width: 225px;"onblur="javascript:cek_karakter(event.keyCode,'alamat');"/></td>
	        <td style="border-spacing:3px ;padding:3px 3px 3px 3px;border-bottom-color:black;"><input type="text" id="total" style="width: 175px; text-align: right;"  onkeypress="javascript:enter(event.keyCode,'penerima');return(currencyFormat(this,',','.',event))"/>
            </td>
        </tr> 
		<tr style="border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;">
            <td colspan='8' align="right" style="border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;border-left-style:hidden;border-right-style:hidden;">
			<i><b>Tekan Enter untuk Input</b></i>
			</td>
       
        </table>
		
		
		
		
		
        <table id="dg1"  style="width:875px;height:370px;"> 
        </table>  
        <table border='1' style="width:870px;">
        <tr style="border-style: hidden;">
        
        <td style="border-style: hidden;">
            <button id="add1" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Tambah</button>
    		<button id="del1" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus_rinci();">Hapus</button>
            <button id="save1" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:detsimpan();">Simpan</button>
			<button id="paste" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="javascript:paste_copy();">Paste</button>
    		<button id="insert1" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="javascript:insert_row();">Insert</button>
		  <!--  <button id="stdhrg" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="javascript:standard_harga();">Standart Harga</button>-->
			
        </td>
        
        <td align='right' style="border-style: hidden;">
        <B>Total</B>&nbsp;&nbsp;&nbsp;&nbsp;<input class="right" type="text" name="rektotal_rinci" id="rektotal_rinci"  style="width:200px" align="right" readonly="true" />
        </td>
        
        </tr>
        
        <tr>
        <td style="border-style: hidden;">
        <button class="easyui-linkbutton" iconCls="icon-back" plain="true" onclick="javascript:section1()">Kembali</button>
        </td>
        <td align='right' style="border-style: hidden;">
        <B>Realisasi Rek5</B>&nbsp;&nbsp;&nbsp;&nbsp;<input class="right" type="text" value="0" name="total2_rek5" id="total2_rek5"  style="width:200px" align="right" readonly="true" />
        </td>
        </tr>
        </table>
    </div>   


<h3><a href="#" id="section3" onclick="javascript:load_dhukum();" >Dasar Hukum</a></h3>

    <div>
        
        <table id="dg2" title="Input Dasar Hukum Per Kegiatan" style="width:870px;height:400px;" >  
        
        </table>  
        <div id="toolbar1xa">
    		<button id="save2" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_dhukum();">Simpan</button>
    		<button class="easyui-linkbutton" iconCls="icon-back" plain="true" onclick="javascript:section1()">Kembali</button>
        </div>

	</div>   

<h3><a href="#" id="section4" onclick="javascript:load_detail_keg();" >Detail Kegiatan</a></h3>
    <div>
    <fieldset style="width:100%;height:570px;border-color:black;border-style:hidden; border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;"> 
    <br />
	<table align="center" border='0' width="100%" style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;" >
	<tr style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;">
		<td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><b>Anggaran Tahun Lalu</b></td>
		<td colspan="3" style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" > <input type="text" id="ang_lalu" style="width:190px;text-align: right;"  onkeypress="javascript:return(currencyFormat(this,',','.',event))"/></td>
	</tr>	<tr style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;">
		<td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><b>Lokasi</b></td>
		<td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><textarea id="lokasi" name="lokasi" rows='2' cols="40" ></textarea></td>
		<td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" >&nbsp;&nbsp;</td>
		<td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><b>Sasaran</b></td>
		<td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;"><textarea id="sasaran" name="sasaran" rows='2' cols="40"></textarea></td>
	</tr>
	<tr style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;" >
		<td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><b>Waktu</b></td>
		<td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><textarea id="wkeg" name="wkeg" rows='2' cols="40" > </textarea></td>
		<td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" >&nbsp;&nbsp;</td>
		<td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" ><b>PPTK</b></td>
		<td style="border-spacing:0px ;padding:0px 0px 0px 0px;border-collapse:collapse;" > <?php echo $this->rka_model->combo_ttd(); ?></td>
	</tr>
	</table>
	<br/>    

	<table align="center" border='0' width="100%" style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;">
	<tr style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;" >
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;"><b>Indikator</b></td>
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;">&nbsp;&nbsp;</td>
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;" align="center"><b>Tolak Ukur</b></td>
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;">&nbsp;&nbsp;</td>
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;" align="center"><b>Capaian Kinerja</b></td>
	</tr>
	<tr style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;">
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;"><b>Capaian</b></td>
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;">&nbsp;&nbsp;</td>
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;" align="center"><textarea id="cp_tu" name="cp_tu" rows='2' cols="45" > </textarea></td>
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;">&nbsp;&nbsp;</td>
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;" align="center"><textarea id="cp_ck" name="cp_ck" rows='2' cols="45" > </textarea></td>
	</tr>
	<tr style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;" >
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;"><b>Masukan</b></td>
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;">&nbsp;&nbsp;</td>
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;" align="center"><textarea id="m_tu" name="m_tu" rows='2' cols="45"  > </textarea></td>
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;">&nbsp;&nbsp;</td>
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;" align="center"><textarea id="m_ck" name="m_ck" rows='2' cols="45"  > </textarea></td>
	</tr>
	<tr style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;" >
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;"><b>Keluaran</b></td>
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;">&nbsp;&nbsp;</td>
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;" align="center"><textarea id="k_tu" name="k_tu" rows='2' cols="45" > </textarea></td>
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;" >&nbsp;&nbsp;</td>
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;" align="center"><textarea id="k_ck" name="k_ck" rows='2' cols="45" > </textarea></td>
	</tr>
	<tr style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;border-style:hidden;" >
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;"><b>Hasil</b></td>
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;">&nbsp;&nbsp;</td>
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;" align="center"><textarea id="h_tu" name="h_tu" rows='2' cols="45" > </textarea></td>
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;">&nbsp;&nbsp;</td>
		<td style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;" align="center"><textarea id="h_ck" name="h_ck" rows='2' cols="45" > </textarea></td>
	</tr>
	</table>
        <div id="toolbar1xa" >
    		<button id="save4" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_det_keg()">Simpan</button>
    		<button class="easyui-linkbutton" iconCls="icon-back" plain="true" onclick="javascript:section1()">Kembali</button>
        </div>
    </fieldset>    
	</div>   
</div>


<div id="dialog-modal" title="">

    <p class="validateTips"></p> 
    <fieldset>        
    <table id="dg_std" title="Pilih Standard" style="width:930px;height:300px;">  
    </table>  
    
    <table style="width:930px;height:20px;" border="0">
        <tr>
        <td align="center" colspan='2'>&nbsp;</td>
        </tr>

        <tr>
        <td align="center" >
        <button class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:pilih_std();">Pilih</button>
        <button class="easyui-linkbutton" iconCls="icon-back" plain="true" onclick="javascript:kembali_std();">Kembali</button></td>
        </tr>
    </table>
    
    </fieldset>  
</div>


<div id="dialog-modal-edit" title="Edit Rincian Rekening">

    <p class="validateTips"></p> 
    
    <fieldset>        
    <table border='0' style="width:800px;">
        <tr style="border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;">
            <td colspan='8' style="border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;">
				<input type="hidden" id="noid_edit" style="width: 200px;" />
				<input type="hidden" id="nourut_edit" style="width: 200px;" />
				<input type="hidden" id="reke" style="width: 200px;" />
				<input type="hidden" id="totallama_edit" style="width: 200px;" />
                <input type="hidden" id="total_edit_susun" style="width: 200px;" />
			</td>


		<tr style="border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;">
			<td style="border-right-style:hidden; border-bottom-style:hidden;border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;">No</td>
			<td style="border-right-style:hidden; border-bottom-style:hidden;border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;">Penerima</td>
            <td style="border-right-style:hidden; border-bottom-style:hidden;border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;">Alamat</td>
            <td style="border-bottom-style:hidden;border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;">Total</td>
        </tr>
        <tr style="border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;">
            <td style="text-align:center; border-right-style:hidden; border-bottom-color:black;border-style-bottom:hidden;border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;"> <input type="number" id="cad1_edit" style="width: 50px;" onkeypress="javascript:enter(event.keyCode,'penerima');"/></td>
            <td style="text-align:center; border-right-style:hidden; border-bottom-color:black;border-style-bottom:hidden;border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;"> <input type="text" id="penerima_edit" style="width: 400px;"/></td>
            <td style="text-align:center; border-right-style:hidden; border-bottom-color:black;border-style-bottom:hidden;border-spacing:3px ;padding:3px 3px 3px 3px;border-collapse:collapse;"> <input type="text" id="alamat_edit" style="width: 200px;"/></td>
            <td style="border-spacing:3px ;padding:3px 3px 3px 3px;border-bottom-color:black;">
            <input type="text" id="total_edit" style="width: 175px; text-align: right;"  onkeypress="return(currencyFormat(this,',','.',event))"/>
            </td>
        </tr> 
		
        
        <tr>
        <td colspan="8">&nbsp;
        </td>
        </tr>

        <tr>
        <td colspan="8" align="center">
        <button class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:pilih_edit();">Simpan</button> &nbsp; &nbsp; &nbsp;
		<button class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="javascript:copy_edit();">Copy</button>
        </td>
        </tr>
        
    </table>
    </fieldset>  
</div>

</div>  	
</body>
</html>