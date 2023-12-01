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
    var urusan='';
        
    
    $(document).ready(function() {

            $("#dialog-modal").dialog({
                height: 300,
                width: 300,
                modal: true,
                autoOpen:false                
            });
            $("#dialog-modal").dialog("close");
    	$('#dg').edatagrid();
    	$("#giat").combogrid();
		$('#Xskpd').combogrid({  
            panelWidth:900,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka_rancang/skpd',  
            columns:[[  
                {field:'kd_skpd',title:'Kode SKPD',width:200},  
                {field:'nm_skpd',title:'Nama SKPD',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){

				var skpd = rowData.kd_skpd;
                $("#nm_skpd").attr("value",rowData.nm_skpd);
			    var urus = skpd.substring(0, 4);
			    var urusan= urus.replace("-", ".");
			    $("#urusan").combogrid("setValue",urusan);
			    kegiatan(skpd,urusan);
			    validate_combo(skpd);				
            }  
            });   

        $('#urusan').combogrid({  
            panelWidth:700,  
            idField:'kd_urusan',  
            textField:'kd_urusan',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka_rancang/urusan',  
            columns:[[  
                {field:'kd_urusan',title:'Kode Urusan',width:100},  
                {field:'nm_urusan',title:'Nama Urusan',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
                urusan = rowData.kd_urusan;
                $("#nm_urusan").attr("value",rowData.nm_urusan);
                var skpd =$("#Xskpd").combogrid("getValue");
             	kegiatan(skpd,urusan);
                
            } 
        });  
    });    
	
		function kegiatan(skpd,urusan=''){
			$('#giat').combogrid({  
	            panelWidth:700,  
	            idField:'kd_kegiatan',  
	            textField:'kd_kegiatan',  
	            mode:'remote',
	            url:'<?php echo base_url(); ?>/index.php/rka_rancang/ld_giat_rancang/'+skpd+'/'+urusan,  
	            columns:[[  
	                {field:'kd_kegiatan',title:'Kode SubKegiatan',width:120},  
	                {field:'nm_kegiatan',title:'Nama SubKegiatan',width:600},
	                {field:'jns_kegiatan',title:'Jenis SubKegiatan',width:40},
	                {field:'lanjut',title:'Lanjut',width:40}
	            ]],
	            onSelect:function(rowIndex,rowData){
	                    kode = rowData.kd_kegiatan;
	                    nama = rowData.nm_kegiatan;
	                    jenis = rowData.jns_kegiatan;
	                    lanjut = rowData.lanjut;
	                    $("#nm_giat").attr("value",rowData.nm_kegiatan);
						$(function(){   
					        $.ajax({
					           type     : "POST",
					           dataType : "json",
					           data     : ({skpd:skpd,giat:kode,jenis:jenis,lanjut:lanjut,nama:nama}),
					           url      : '<?php echo base_url(); ?>/index.php/rka_rancang/psimpan_rancang/', 
					           success  : function(data){
					           					validate_combo(skpd);
					           					$("#giat").combogrid("setValue",'');
					           					$('#dg').edatagrid('reload');
					           }
					        });
				   		});     
	            }

            }); 

		}        												
      
        function validate_combo(skpd){
            $(function(){
           	$("#dg").datagrid("unselectAll");
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/rka_rancang/select_giat_rancang/'+skpd,
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",

                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
					{field:'kd_kegiatan',
					 title:'Kode SubKegiatan',
					 width:30,
					 align:'left'
					},                    
					{field:'nm_kegiatan',
					 title:'Nama SubKegiatan',
					 width:140
					},
                    {field:'jns_kegiatan',
					 title:'Jenis',
					 width:10,
                     align:'center'
                    },
                    {field:'status',
					 title:'status',
					 width:10,
                     align:'center'                   
                    }
				]],
				onSelect:function(isi,index){
					$("#egiat").attr("value",index.kd_kegiatan);
					if(index.stat==1){
						document.getElementById("stat").checked = true;
					}else{
						document.getElementById("stat").checked = false;
					}
					$("#stat").attr("value",index.stat);
					$("#dialog-modal").dialog('open');
				}
			});
		});
        }  

        function aktif(){
        	var giat = document.getElementById('egiat').value;
			var oke  =document.getElementById("stat").checked;
			if(oke==false){
				var apdet=0;
			}else{
				var apdet=1;
			} 
							$(function(){   
						        $.ajax({
						           type     : "POST",
						           dataType : "json",
						           url: '<?php echo base_url(); ?>/index.php/rka_rancang/status_giat/'+apdet+'/'+giat, 
						           success  : function(data){
						           			$("#dg").datagrid("unselectAll");
						           			$('#dg').edatagrid('reload');
						           }
						        });	
						        });	    	
        }

        function hapus(){
        	    var skpd =$("#Xskpd").combogrid("getValue");
                var giat        = document.getElementById('egiat').value;

					var del=confirm('Anda yakin akan menghapus kegiatan '+giat+' ?');
					if  (del==true){

							$(function(){   
						        $.ajax({
						           type     : "POST",
						           dataType : "json",
						           url: '<?php echo base_url(); ?>/index.php/rka_rancang/ghapus_rancang/'+skpd+'/'+giat, 
						           success  : function(data){
						           			$("#dg").datagrid("unselectAll");
						           			$('#dg').edatagrid('reload');
						           			alert("berhasil dihapus");
						           }
						        });	
						        });	
				
					
					}
				
		}
		function runEffect(){

			if($("#st_lintas").is(':checked')) {	
				$("#urusan").combogrid("enable");
				$("#Xskpd").combogrid("disable");
			} else {
					$("#urusan").combogrid("disable");
					$("#Xskpd").combogrid("enable");
				}   
    }
	</script>
    
</head>
<body>
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>

<div id="content">   
<input type="text" hidden name="egiat" id="egiat">

  <h3>S K P D&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="Xskpd" name="Xskpd" style="width: 200px;border: 0;" />&nbsp;<input id="nm_skpd" name="nm_skpd" readonly="true" style="width:500px;border: 0;"/> </h3>
  
  <h3>SUB KEGIATAN &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="giat" name="giat" style="width: 200px;" />&nbsp;&nbsp;&nbsp;<input id="nm_giat" name="nm_giat" readonly="true" style="width:650px;border: 0;"/> </h3>
  <input type="checkbox" name="" checked> : Kegiatan Aktif <input type="checkbox" name=""> : Kegiatan Non Aktif &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Klik 2x pada tabel untuk hapus.
   <table id="dg" title="Pilih Kegiatan Anggaran Penyusunan" style="width:925px;height:300%"></table>    	    
 		
<div id="dialog-modal" title="Edit Rincian Rekening">
<p align="center">
	<button type="delete" onclick="javascript:hapus()"><i class="fa fa-trash"></i> Hapus Sub Kegiatan</button> <br><br>

<label class="switch">
  <input type="checkbox" id='stat' onclick="javascript:aktif();">
  <span class="slider round"></span>
</label><br><br>
</p>
</div>	     
 
</div>  	




