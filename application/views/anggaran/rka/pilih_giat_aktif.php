 	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script type="text/javascript">
     
    $(document).ready(function() 
	{ 
	//get_skpd()
	}); 
    
	var idx=0;
	var tidx=0;
	var oldRek=0;
    var skpd='';

        
        
    $(document).ready(function() {
	  
    //$(function(){
		$('#Xskpd').combogrid({  
            panelWidth:700,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka/skpduser',  
            columns:[[  
                {field:'kd_skpd',title:'Kode SKPD',width:100},  
                {field:'nm_skpd',title:'Nama SKPD',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
				skpd = rowData.kd_skpd;
                $("#nm_skpd").attr("value",rowData.nm_skpd);

			    $("#giat").combogrid("clear");				
//			    $("#giat").combogrid("setValue",'');				
//				alert(skpd);
				runEffect();
				
                validate_combo(skpd);
                validate_giat(skpd);
				$('#dg').edatagrid('reload');
            }  
            }); 

		$('#giat').combogrid({  
            panelWidth:700,  
            idField:'kd_kegiatan',  
            textField:'kd_kegiatan',  
            mode:'remote',
            url:'<?php echo base_url(); ?>/index.php/rka/ld_giat_keg_renja_nonaktif/'+''+'/'+'',  
            columns:[[  
                {field:'kd_kegiatan',title:'Kode Kegiatan',width:120},  
                {field:'nm_kegiatan',title:'Nama Kegiatan',width:600},
                {field:'jns_kegiatan',title:'Jenis Kegiatan',width:40},
                {field:'lanjut',title:'Lanjut',width:40}
            ]],
            onSelect:function(rowIndex,rowData){
                    kode = rowData.kd_kegiatan;
                    nama = rowData.nm_kegiatan;
                    $("#nm_giat").attr("value",rowData.nm_kegiatan);   					
					 append_jak();
                    simpan(kode); 
					//$('#dg').edatagrid('reload');
                    }
            }); 

    });    

      function validate_skpd(){
		}
        
        function validate_giat(skpd){
			$('#giat').combogrid({  
            url:'<?php echo base_url(); ?>/index.php/rka/ld_giat_keg_renja_nonaktif/'+skpd
            });
			
		}




        function append_jak(){
	$("#dg").edatagrid("selectAll");
        var rows = $("#dg").edatagrid("getSelections");
        var jrow = rows.length;
        jidx     = jrow + 1 ;			
		    //$('#dg').edatagrid('appendRow',{kd_rek5:reke,nm_rek5:nmrek5,nilai:nrek,id:jidx});
            $('#dg').edatagrid('appendRow',{kd_kegiatan:kode,id:jidx});
        }
         
        function validate_combo(skpd){
        //var cskpd = document.getElementById('skpd').value;
        //alert(cskpd);
        //alert(skpd+urusan);
            $(function(){
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/rka/select_giat_aktif_renja/'+skpd,
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",
                 onAfterEdit:function(rowIndex, rowData, changes){								
								rk=rowData.kd_kegiatan;
								simpan(rk);
							 },
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
					{field:'kd_kegiatan',
					 title:'Kegiatan',
					 width:30,
					 align:'left',	
					 editor:{type:"combobox",
      		                options:{valueField:'kd_kegiatan',
									  textField:'kd_kegiatan',
									  panelwidth:910,	
									  url :'<?php echo base_url();?>/index.php/rka/ld_giat_keg_renja_nonaktif/'+skpd,                                   
									  required:true,
									  onSelect:function(){							
						                      oldRek=getSelections(getRowIndex(this));	
                                                //alert(oldRek);
						                  }
									  }
							}
					},                    
					{field:'nm_kegiatan',
					 title:'Nama Kegiatan',
					 width:140,
					 editor:{type:"text"}
					}
				]]
			});
		});
        }

    
	$(function(){
	   	    //var mskpd = document.getElementById('cc').value;           
            //var murusan = document.getElementById('ur').value;
            //var mskpd =skpd;           
            //var murusan =urusan;
            //alert(skpd+urusan);
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/rka/select_giat_aktif_renja',
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",
				 onAfterEdit:function(rowIndex, rowData, changes){								
								rk=rowData.kd_kegiatan;
								simpan(rk);
							 },
				 onSelect:function(){							
						  oldRek=getSelections(getRowIndex(this));	
                            //alert(oldRek);
						  },
				columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
					{field:'kd_kegiatan',
					 title:'Kegiatan',
					 width:30,
					 align:'left',	
					 editor:{type:"combobox",
      		                options:{valueField:'kd_kegiatan',
									  textField:'kd_kegiatan',
									  panelwidth:910,	
									  url :'<?php echo base_url();?>/index.php/rka/ld_giat_keg_renja_nonaktif',
									  required:true,
									  onSelect:function(){							
						                      oldRek=getSelections(getRowIndex(this));	
                                                //alert(oldRek);
						                  }
									  }
							}
					},                    
					{field:'nm_kegiatan',
					 title:'Nama Kegiatan',
					 width:140,
					 editor:{type:"text"}
					}
				]]	
			
			});
  	
		  

		});




		function getSelections(idx){
			//alert(idx);
			var ids = [];
			var rows = $('#dg').edatagrid('getSelections');
			for(var i=0;i<rows.length;i++){
				ids.push(rows[i].kd_kegiatan);
			}
			return ids.join(':');
		}


		function getRowIndex(target){  
			var tr = $(target).closest('tr.datagrid-row');  
			return parseInt(tr.attr('datagrid-row-index'));  
		}  


        function simpan(baru){		
		//var curus = document.getElementById('urusan').value;
		//var cskpd = document.getElementById('skpd').value;        
        //alert(urusan+skpd+baru+lama+jns);
            $(function(){
				$('#dg').edatagrid({
				     url: '<?php echo base_url(); ?>/index.php/rka/psimpan_keg_aktif/'+skpd+'/'+baru,
					 idField:'id',
					 toolbar:"#toolbar",              
					 rownumbers:"true", 
					 fitColumns:"true",
					 singleSelect:"true"
				});
			});
		
		}

       function aktifs(){		
		//var curus = document.getElementById('urusan').value;
		var cskpd = $("#Xskpd").combogrid("getValue");
        if(cskpd==''){
            alert('Pilih SKPD terlebih dahulu');
            return;
        }       
        //alert(urusan+skpd+baru+lama+jns);
            $(function(){
				$('#dg').edatagrid({
				     url: '<?php echo base_url(); ?>/index.php/rka/keg_renja_aktif_semua/'+skpd,
					 idField:'id',
					 toolbar:"#toolbar",              
					 rownumbers:"true", 
					 fitColumns:"true",
					 singleSelect:"true"
				});
			});
		validate_giat(cskpd);
        $('#dg').edatagrid('reload');
		}
		
		

        function hapus(){
				//var cgiat = document.getElementById('giat').value;
				//var cskpd = document.getElementById('skpd').value;
				var rek=getSelections();
                //alert(skpd+rek) 
				if (rek !=''){
				var del=confirm('Anda yakin akan menonaktifkan kegiatan '+rek+' ?');
				if  (del==true){
					$(function(){
						$('#dg').edatagrid({
							 url: '<?php echo base_url(); ?>/index.php/rka/f_keg_nonaktif/'+skpd+'/'+rek,
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
        
        function hapus_semua(){
				//var cgiat = document.getElementById('giat').value;
				//var cskpd = document.getElementById('skpd').value;
                var cskpd = $("#Xskpd").combogrid("getValue");
                if(cskpd==''){
                    alert('Pilih SKPD terlebih dahulu');
                    return;
                }       
                
                //alert(skpd+rek) 
				var del=confirm('Anda yakin akan menonaktifkan semua kegiatan '+cskpd+' ?');
				if  (del==true){
					$(function(){
						$('#dg').edatagrid({
							 url: '<?php echo base_url(); ?>/index.php/rka/f_keg_nonaktif_semua/'+skpd,
							 idField:'id',
							 toolbar:"#toolbar",              
							 rownumbers:"true", 
							 fitColumns:"true",
							 singleSelect:"true"
						});
					});
				
				}
                validate_giat(cskpd);
		}

        
        
		function runEffect(){
			zskpd= $("#Xskpd").combogrid("getValue");
			$("#giat").combogrid("clear");
		}
        
	function Left(str, n){
    	if (n <= 0)
    	    return "";
    	else if (n > String(str).length)
    	    return str;
    	else
    	    return String(str).substring(0,n);
    }
     
    function Right(str, n){
        if (n <= 0)
           return "";
        else if (n > String(str).length)
           return str;
        else {
           var iLen = String(str).length;
           return String(str).substring(iLen, iLen - n);
        }
    }
    
	</script>
    
</head>
<body>

<div id="content">   
 <!-- <?php echo $prev; ?>-->
  <h3>S K P D&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="Xskpd" name="Xskpd" style="width: 100px;border: 0;" />&nbsp;&nbsp;&nbsp;<input id="nm_skpd" name="nm_skpd" readonly="true" style="width:600px;border: 0;"/> </h3>
  <h3>K E G I A T A N &nbsp;&nbsp;&nbsp;&nbsp;<input id="giat" name="giat" style="width: 150px;" />&nbsp;&nbsp;&nbsp;<input id="nm_giat" name="nm_giat" readonly="true" style="width:650px;border: 0;"/> </h3>
  
   <table id="dg" title="Kegiatan Anggaran  yg Aktif di Renja (Kegiatan Bendahara yg Tidak Aktif)" style="width:910%;height:300%" >  
        

	</table>    	    
        <!-- <button type="button" onclick="javascript:$('#dg').edatagrid('addRow')">BARU</button>
		<button class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('addRow');">SIMPAN</button> 
		
		<button class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">BATAL</button>
		-->
        <button type="button" onclick="javascript:hapus()">Nonaktifkan</button>
        <button type="button" onclick="javascript:aktifs()">Aktifkan Semua</button> 
        <button type="button" onclick="javascript:hapus_semua()">Nonaktifkan Semua</button>         	
</div>  	

