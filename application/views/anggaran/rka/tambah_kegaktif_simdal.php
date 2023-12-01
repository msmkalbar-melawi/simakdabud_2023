 	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>
    <script type="text/javascript">
     
    $(document).ready(function() 
	{ 
	//get_skpd()
	}); 
    
	var idx=0;
	var tidx=0;
	var oldRek=0;
    var skpd='';
    var urusan='';
        
        
    $(document).ready(function() {
        $('#urusan').combogrid({  
            panelWidth:700,  
            idField:'kd_urusan',  
            textField:'kd_urusan',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka/urusan',  
            columns:[[  
                {field:'kd_urusan',title:'Kode Urusan',width:100},  
                {field:'nm_urusan',title:'Nama Urusan',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
                urusan = rowData.kd_urusan;
                $("#nm_urusan").attr("value",rowData.nm_urusan);
               // validate_skpd();
				
				//runEffect();
                //validate_combo(skpd,urusan)
                validate_giat(skpd,urusan);
                
            } 
        }); 
				   		
   		$("#urusan").combogrid("disable");			



  
    //$(function(){
		$('#Xskpd').combogrid({  
            panelWidth:700,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka/skpd_simdal',  
            columns:[[  
                {field:'kd_skpd',title:'Kode SKPD',width:100},  
                {field:'nm_skpd',title:'Nama SKPD',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
                $("#totalkua").attr('value',number_format(0,2,'.',',')); 
                $('#dg').datagrid('loadData',[]);
				skpd = rowData.kd_skpd;
                $("#nm_skpd").attr("value",rowData.nm_skpd);

			    $("#giat").combogrid("clear");
                get_simdal(skpd);				
//			    $("#giat").combogrid("setValue",'');				
//				alert(skpd);
				//runEffect();
				//validate_giat(skpd,urusan);
                //validate_combo(skpd,urusan);
				//$('#dg').edatagrid('reload');
            }  
            }); 
//});

	  //$(function(){
		$('#giat').combogrid({  
            panelWidth:750,  
            idField:'kd_kegiatan',  
            textField:'kd_kegiatan',  
            mode:'remote',
            url:'<?php echo base_url(); ?>/index.php/rka/ld_giat/'+''+'/'+'',  
            columns:[[  
                {field:'kd_kegiatan',title:'Kode Kegiatan',width:150},  
                {field:'nm_kegiatan',title:'Nama Kegiatan',width:600},
                {field:'jns_kegiatan',title:'Jenis Kegiatan',width:40},
                {field:'lanjut',title:'Lanjut',width:40}
            ]],
            onSelect:function(rowIndex,rowData){
				//alert('on select ni bro...............')
                    kode = rowData.kd_kegiatan;
                    nama = rowData.nm_kegiatan;
                    jenis = rowData.jns_kegiatan;
                    lanjut = rowData.lanjut;
                    $("#nm_giat").attr("value",rowData.nm_kegiatan);   					
					 append_jak();
                    simpan(kode,oldRek,jenis,lanjut);
                    //validate_combo(skpd,urusan);
                    }
					/* ,
                    onDblClickRow:function(rowIndex,rowData){
                        section2();   
                    } */ 
            }); 

     //   });    
    });    
	 /*  function get_skpd()
        {
        
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#skpd").attr("value",data.kd_skpd);
        								$("#nm_skpd").attr("value",data.nm_skpd);
        								skpd = data.kd_skpd;
        							  }
        	});  
        } */
      
      function validate_skpd(){
/* 		  $(function(){
            $('#Xskpd').combogrid({  
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
				skpd = rowData.kd_skpd;
				//alert(skpd);
                $("#nm_skpd").attr("value",rowData.nm_skpd);				
				validate_giat(skpd,urusan);
                validate_combo(skpd,urusan);
				$('#dg').edatagrid('reload');
            }  
            }); 
            }); */
		}
        
        function validate_giat(skpd,urusan){
		 // $(function(){
			  if($("#st_lintas").is(':checked'))
				{	skpd='0';				}
			
			$('#giat').combogrid({  
            url:'<?php echo base_url(); ?>/index.php/rka/ld_giat/'+skpd+'/'+urusan
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
         
        function get_simdal(skpd){
           
            var skpd = skpd;    
            $(function(){
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/rka/get_aktif_simdal/'+skpd,
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true",
                 nowrap:false, 
                 fit:"true",
                 fitColumns   : false,
                 singleSelect:"true",
                 columns:[[
	                {field:'id',title:'id',checkbox:true,hidden:true},
					{field:'ID_SKPD',title:'Kode SKPD',width:70,align:'left',formatter:function(value,row){return '<div style="height:25px;line-height:12px">'+value+'</div>';}},
                    {field:'NM_SKPD',title:'Nama SKPD',width:200,align:'left',formatter:function(value,row){return '<div style="height:25px;line-height:12px">'+value+'</div>';}},
                    {field:'ID_URUSAN',title:'Kode Urusan',width:70,align:'left',formatter:function(value,row){return '<div style="height:25px;line-height:12px">'+value+'</div>';}},
                    {field:'NM_URUSAN',title:'Nama Urusan',width:100,align:'left',formatter:function(value,row){return '<div style="height:25px;line-height:12px">'+value+'</div>';}},
                    {field:'ID_PROGRAM',title:'Kode Program',width:120,align:'left',formatter:function(value,row){return '<div style="height:25px;line-height:12px">'+value+'</div>';}},
                    {field:'NM_PROGRAM',title:'Nama Program',width:300,align:'left',formatter:function(value,row){return '<div style="height:25px;line-height:12px">'+value+'</div>';}},
                    {field:'ID_KEGIATAN',title:'Kode Kegiatan',width:100,align:'left',formatter:function(value,row){return '<div style="height:25px;line-height:12px">'+value+'</div>';}},
                    {field:'NM_KEGIATAN',title:'Nama Kegiatan',width:300,align:'left',formatter:function(value,row){return '<div style="height:25px;line-height:12px">'+value+'</div>';}},
                    {field:'LOKASI',title:'Lokasi',width:100,align:'left',formatter:function(value,row){return '<div style="height:25px;line-height:12px">'+value+'</div>';}},
                    {field:'WAKTU_PELAKSANAAN',title:'Waktu',width:50,align:'left',formatter:function(value,row){return '<div style="height:25px;line-height:12px">'+value+'</div>';}},
                    {field:'SASARAN',title:'Sasaran',width:50,align:'left',formatter:function(value,row){return '<div style="height:25px;line-height:12px">'+value+'</div>';}},
                    {field:'CAPAIAN_UKUR',title:'Capaian Ukur',width:50,align:'left',formatter:function(value,row){return '<div style="height:25px;line-height:12px">'+value+'</div>';}},
                    {field:'MASUKAN_UKUR',title:'Masukan Ukur',width:50,align:'left',formatter:function(value,row){return '<div style="height:25px;line-height:12px">'+value+'</div>';}},
                    {field:'KELUARAN_UKUR',title:'Keluaran Ukur',width:300,align:'left',formatter:function(value,row){return '<div style="height:25px;line-height:12px">'+value+'</div>';}},
                    {field:'HASIL_UKUR',title:'Hasil Ukur',width:300,align:'left',formatter:function(value,row){return '<div style="height:25px;line-height:12px">'+value+'</div>';}},
                    {field:'CAPAIAN_KINERJA',title:'Capaian Kinerja',width:300,align:'left',formatter:function(value,row){return '<div style="height:25px;line-height:12px">'+value+'</div>';}},
                    {field:'KELUARAN_KINERJA',title:'Keluaran Kinerja',width:300,align:'left',formatter:function(value,row){return '<div style="height:25px;line-height:12px">'+value+'</div>';}},
                    {field:'HASIL_KINERJA',title:'Hasil',width:80,align:'left',formatter:function(value,row){return '<div style="height:25px;line-height:12px">'+value+'</div>';}},
                    {field:'ANGG_LALU',title:'Angg Lalu',width:100,align:'right',formatter:function(value,row){return '<div style="height:25px;line-height:12px">'+value+'</div>';}},
                    {field:'PAGU',title:'Pagu',width:120,align:'right',formatter:function(value,row){return '<div style="height:25px;line-height:12px">'+value+'</div>';}}
				]]
			});
		});
        //setTimeout(function(){total_kua();}, 5000);
        }

    
	$(function(){
	   	    //var mskpd = document.getElementById('cc').value;           
            //var murusan = document.getElementById('ur').value;
            //var mskpd =skpd;           
            //var murusan =urusan;
            //alert(skpd+urusan);
			$('#dg').edatagrid({
				url: '',
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",
                columns:[[
					{field:'',
					 title:'',
					 width:140,
					 editor:{type:"text"}
					}
				]]	
			
			});
  	
		  

		});

        function total_kua(){
            var data = $('#dg').datagrid('getData');
            var rows = data.rows;
            var kua = 0;
            for (i=0; i < rows.length; i++) {
                kua+=angka(rows[i].PAGU);
            }    
            $("#totalkua").attr('value',number_format(kua,2,'.',','));     
        }  

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


        function simpan(){
            var tabel = 'tsimdal';
            var cskpd  = skpd;
            var kua  = angka(document.getElementById('totalkua').value);
            var field = '(kd_skpd,nm_skpd,kd_urusan,nm_urusan,kd_program,nm_program,kd_kegiatan,nm_kegiatan,lokasi,waktu_giat,sasaran_giat,tu_capai,tu_mas,tu_kel,'+
                        'tu_hasil,tk_capai,tk_kel,tk_has,angg_lalu,pagu)';           
            $('#dg').datagrid('selectAll');
            var rows = $('#dg').datagrid('getSelections');
			for(var i=0;i<rows.length;i++){            
				cidx        = rows[i].id;
                ckdskpd     = rows[i].ID_SKPD.trim();
				cnmskpd     = rows[i].NM_SKPD.trim();
                ckdu        = rows[i].ID_URUSAN.trim();
                cnmu        = rows[i].NM_URUSAN.trim();
                ckdprog     = rows[i].ID_PROGRAM.trim();
                cnmprog     = rows[i].NM_PROGRAM.trim();
                ckdkegi     = rows[i].ID_KEGIATAN.trim();
                cnmkegi     = rows[i].NM_KEGIATAN.trim();
                clokasi     = rows[i].LOKASI.trim().split("'").join("`");             
                cwaktu      = rows[i].WAKTU_PELAKSANAAN.trim().split("'").join("`");
                csasaran    = rows[i].SASARAN.trim().split("'").join("`");
                ccu         = rows[i].CAPAIAN_UKUR.trim().split("'").join("`");
                cmu         = rows[i].MASUKAN_UKUR.trim().split("'").join("`");
                cku         = rows[i].KELUARAN_UKUR.trim().split("'").join("`");
                chu         = rows[i].HASIL_UKUR.trim().split("'").join("`");
                cck         = rows[i].CAPAIAN_KINERJA.trim().split("'").join("`");
                ckk         = rows[i].KELUARAN_KINERJA.trim().split("'").join("`");
                chk         = rows[i].HASIL_KINERJA.trim().split("'").join("`");
                canggl      = angka(rows[i].ANGG_LALU);
                cpagu       = angka(rows[i].PAGU);

				if(i>0){
					csql = csql+","+"('"+ckdskpd+"','"+cnmskpd+"','"+ckdu+"','"+cnmu+"','"+ckdprog+"','"+cnmprog+"','"+ckdkegi+"','"+cnmkegi+"','"+clokasi+"',"+
                           "'"+cwaktu+"','"+csasaran+"','"+ccu+"','"+cmu+"','"+cku+"','"+chu+"','"+cck+"','"+ckk+"','"+chk+"','"+canggl+"','"+cpagu+"')";
				}else{
					csql = "values('"+ckdskpd+"','"+cnmskpd+"','"+ckdu+"','"+cnmu+"','"+ckdprog+"','"+cnmprog+"','"+ckdkegi+"','"+cnmkegi+"','"+clokasi+"',"+
                           "'"+cwaktu+"','"+csasaran+"','"+ccu+"','"+cmu+"','"+cku+"','"+chu+"','"+cck+"','"+ckk+"','"+chk+"','"+canggl+"','"+cpagu+"')";                
				}                                             
			}   	                  

			$(document).ready(function(){
				//alert(csql);
				//exit();
				$.ajax({
					type: "POST",   
					dataType : 'json',                 
					data: ({sql:csql,tabel:tabel,field:field,cskpd:cskpd,kua:kua}),
					url: '<?php echo base_url(); ?>/index.php/rka/dsimpan_simdal_keg',
					success:function(data){                        
						status = data.pesan;   
						 if (status=='1'){
							alert('Data Berhasil Tersimpan...!!!');
						} else{ 
							alert('Detail Gagal Tersimpan...!!!');
						}                                             
					}
				});
				});  
              $('#dg').datagrid('unselectAll');   
        }
            
        
        function hapus(){
				//var cgiat = document.getElementById('giat').value;
				//var cskpd = document.getElementById('skpd').value;
				var rek=getSelections();
                //alert(skpd+rek) 
				if (rek !=''){
				var del=confirm('Anda yakin akan menghapus kegiatan '+rek+' ?');
				if  (del==true){
					$(function(){
						$('#dg').edatagrid({
							 url: '<?php echo base_url(); ?>/index.php/rka/ghapus/'+skpd+'/'+rek,
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


        function hapus(){
				//var cgiat = document.getElementById('giat').value;
				//var cskpd = document.getElementById('skpd').value;
				var rek=getSelections();
                //alert(skpd+rek) 
				if (rek !=''){
				var del=confirm('Anda yakin akan menghapus kegiatan '+rek+' ?');
				if  (del==true){
					$(function(){
						$('#dg').edatagrid({
							 url: '<?php echo base_url(); ?>/index.php/rka/ghapus/'+skpd+'/'+rek,
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
  
        
		function runEffect(){
			zskpd= $("#Xskpd").combogrid("getValue");
			$("#giat").combogrid("clear");
			if($("#st_lintas").is(':checked'))
				{	
				$("#urusan").combogrid("enable");
				$("#Xskpd").combogrid("disable");
				//$("#urusan").combogrid("setValue",no_spd);
					validate_giat(zskpd,$("#urusan").combogrid("getValue"));
				} else
				{
					$("#urusan").combogrid("setValue",Left(zskpd,4));
					$("#urusan").combogrid("disable");
					$("#Xskpd").combogrid("enable");
//					validate_giat(zskpd,Left(zskpd,4))
					validate_giat(zskpd,'')
				}
				
				
					
			
			
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
  
  <!--<h3>S K P D&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="Xskpd" name="Xskpd" style="width: 100px;border: 0;" />&nbsp;&nbsp;&nbsp;<input id="nm_skpd" name="nm_skpd" readonly="true" style="width:600px;border: 0;"/> </h3> 
  <h3>*)Setelah pilih SKPD harap centang Pilih urusan</h3>
  <h3>	<input type="checkbox" id="st_lintas"  onclick="javascript:runEffect();"/> PILIH URUSAN</h3>
  <h3>U R U S A N&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="urusan" name="urusan" style="width: 100px;" />&nbsp;&nbsp;&nbsp;&nbsp;<input id="nm_urusan" name="nm_urusan" readonly="true" style="width:400px;border: 0;"/> </h3>
  <h3>K E G I A T A N &nbsp;&nbsp;&nbsp;&nbsp;<input id="giat" name="giat" style="width: 150px;" />&nbsp;&nbsp;&nbsp;<input id="nm_giat" name="nm_giat" readonly="true" style="width:650px;border: 0;"/> </h3>
   <h3>&nbsp;</h3>
   BULAN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select  name="kebutuhan_bulan" id="kebutuhan_bulan" >
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
   </select>   -->
   <h3>S K P D&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="Xskpd" name="Xskpd" style="width: 100px;border: 0;" />&nbsp;&nbsp;&nbsp;<input id="nm_skpd" name="nm_skpd" readonly="true" style="width:600px;border: 0;"/> </h3>
   <h3>Total Kua&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="totalkua" name="totalkua" style="width:250px;text-align:right;" disabled="true"/>&nbsp;&nbsp;&nbsp;
   <!--<button type="button" onclick="javascript:get_simdal()">Ambil SKPD Simdal</button>&nbsp;&nbsp; --><button type="button" onclick="javascript:simpan()">Simpan Data</button></h3>
   <table id="dg" title="Data SimdalRenbang" style="width:910%;height:300%" data-options="onLoadSuccess:function(){total_kua();}">  
        

	</table>    	    
        <!-- <button type="button" onclick="javascript:$('#dg').edatagrid('addRow')">BARU</button>
		<button class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('addRow');">SIMPAN</button> 
		
		<button class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">BATAL</button>
		-->
              	
	
	     
 
</div>  	

