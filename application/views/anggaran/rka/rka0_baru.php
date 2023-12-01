

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
    var ctk = '1';
        
      $(document).ready(function() {
            
        //get_skpd();
		 
        }); 
    
     $(function(){ 
        
           // $("#div_bend").hide();
			
           // $("#div_ttd").hide();
			 $("#nm_skpd").attr("value",'');
      

        $('#tgl_ttd').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        }); 
        
        
		$('#skpd').combogrid({  
            panelWidth:700,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka/skpd_susun',  
            columns:[[  
                {field:'kd_skpd',title:'Kode SKPD',width:100},  
                {field:'nm_skpd',title:'Nama SKPD',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
				skpd = rowData.kd_skpd;
                $("#nmskpd").attr("value",rowData.nm_skpd);
           $(function(){
            $('#ttd1').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/rka/load_tanda_tangan/'+skpd,  
                    idField:'nip',  
                    textField:'nama',
                    mode:'remote',  
                    fitColumns:true
                });
				
				 $('#ttd2').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/rka/load_tanda_tangan/'+skpd,  
                    idField:'nip',  
                    textField:'nama',
                    mode:'remote',  
                    fitColumns:true
                });
				
				
           });
		   
		   
				}  
            });
		
        $(function(){  
	       $('#dg_cunit').edatagrid({
				url           : '<?php echo base_url(); ?>/index.php/rka/skpd_trdrka',
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
					{field:'kd_skpd',
					 title:'Rekening',
					 width:12,
					 align:'left'	
					}
				]]
			});
		
		});	
              
        	$('#ttd1').combogrid({  
    		panelWidth:500,  
    		url: '<?php echo base_url(); ?>/index.php/rka/load_tanda_tangan'+kode,  
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
			
			
            $('#ttd2').combogrid({  
                panelWidth:400,  
                idField:'nip',  
                textField:'nama',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/rka/load_tanda_tangan/'+kode,  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},  
                    {field:'nama',title:'Nama',width:400}    
                ]]  
            });          
			
    });        

	   
	 
    
     function cetak(){
        $("#dialog-modal").dialog('close');
     } 
     

     
     function openWindow( url ){
        
		 
		   var  ctglttd = $('#tgl_ttd').datebox('getValue');
		   var  ttd = $('#ttd1').combogrid('getValue');
		   var  ttd_2 = $('#ttd2').combogrid('getValue');
           var ttd1 = ttd.split(" ").join("a");
		   var ttd2 = ttd_2.split(" ").join("a");
		  
            lc = '?tgl_ttd='+ctglttd+'&ttd1='+ttd1+'&ttd2='+ttd2+'';
			window.open(url+lc,'_blank');
			window.focus();
	
		 
     }  
     
     function opt(val){        
        ctk = val; 
        if (ctk=='1'){
            $("#div_rekap").hide();
            $("#div_skpd").hide();
        } else if (ctk=='2'){
            $("#div_rekap").show();
            $("#div_skpd").hide();
				} else if (ctk=='3'){
				$("#div_rekap").hide();
				$("#div_skpd").show();
				} else {
				exit();
				}                 
    }     

	
	 function alltrim(kata){
	 //alert(kata);
		b = (kata.split(' ').join(''));
		c = (b.replace( /\s/g, ""));
		return c
	 
	 }
	
	function cek($cetak){
        var thn = '<?php echo $this->session->userdata("pcThang"); ?>';
        if(ctk==''){
            alert("Pilih Jenis Laporan");
            return;
        }
        
        if ($('#skpd').combogrid('getValue')==''){
            alert("Pilih Nama SKPD Terlebih Dahulu");
            return;
        }
        
        var ckdskpd = $('#skpd').combogrid('getValue');
        if ($('input[name="jns_cetak"]:checked').val()=='1'){	
            if($cetak=='5'){
				$('#dg_cunit').datagrid('selectAll');
				var rows = $('#dg_cunit').datagrid('getSelections');                   
				for(var p=0;p<rows.length;p++){
					ckdskpd  = rows[p].kd_skpd; 
                    url="<?php echo site_url(); ?>/rka/preview_rka0_baru/"+ckdskpd+'/'+$cetak+'/RKA-0 '+ckdskpd+' Tahun '+thn+'.pdf';
                    openWindow( url );
                }
            }else{
                url="<?php echo site_url(); ?>/rka/preview_rka0_baru/"+ckdskpd+'/'+$cetak+'/RKA-0 '+ckdskpd+' Tahun '+thn;
                openWindow( url );
            }
        }else{
            if($cetak=='5'){
				$('#dg_cunit').datagrid('selectAll');
				var rows = $('#dg_cunit').datagrid('getSelections');                   
				for(var p=0;p<rows.length;p++){
					ckdskpd  = rows[p].kd_skpd; 
                    url="<?php echo site_url(); ?>/rka/preview_rka0_rinci/"+ckdskpd+'/'+$cetak+'/';
                    openWindow( url );
                }
            }else{
                url="<?php echo site_url(); ?>/rka/preview_rka0_rinci/"+ckdskpd+'/'+$cetak+'/';
                openWindow( url );                
            }    
		}

	if(ctk==''){
	alert("Pilih Jenis Laporan");
	exit();
		} else if ($('#skpd').combogrid('getValue')==''){
		alert("Pilih Nama SKPD Terlebih Dahulu")
			} else {

					openWindow( url );
					}
	}
	
  function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
  
  
   </script>


<div id="content1" align="center"> 
    <h3 align="center"><b>CETAK RKA 0</b></h3>
    
        <div style="display:none">
			<table id="dg_cunit"  style="width:875px;height:370px;"> 
			</table> 
		</div>
    <fieldset style="width: 90%;">
     <table align="center" style="width:100%;" border="0">
            <tr>
                <td><input type="radio" name="jns_cetak" value="1" />REKAP &ensp;
                <input type="radio" name="jns_cetak" value="2" id="status" />RINCI
                </td>
                <td>&ensp;</td>
                <td>&nbsp</td>
            </tr>
			
            <tr>	
				<td>			
                 <div id="div_skpd">
                        <table style="width:100%;" border="0">
                            <td width="20%">SKPD</td>
                            <td width="1%">:</td>
                            <td width="79%"><input id="skpd" name="skpd" style="width: 100px;" />&ensp;
                            <input type="text" id="nmskpd" readonly="true" style="width: 700px;border:0" />
                            </td>
                        </table>
                </div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
                            <td width="20%">TANGGAL TTD</td>
                            <td width="1%">:</td>
                            <td><input type="text" id="tgl_ttd" style="width: 100px;" /> 
                            </td> 
                        </table>
                </div>
                </td> 
            </tr>
			<tr>
		<td colspan="4">
                <div id="div_ttd">
                        <table style="width:100%;" border="0">
                            <td width="20%">TTD 1</td>
                            <td width="1%">:</td>
                            <td><input type="text" id="ttd1" style="width: 300px;" /> 
                            </td> 
						
                        </table>
                </div>
                <div id="div_ttd">
                        <table style="width:100%;" border="0">
                            <td width="20%">TTD 2</td>
                            <td width="1%">:</td>
                            <td><input type="text" id="ttd2" style="width: 300px;" /> 
                            </td> 
						
                        </table>
                </div>
        </td> 
		</tr>

            <tr>
             		

                <td colspan="3" align="center">
				<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cek(4)">Cetak</a>
				<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cek(1)">Cetak</a>
                <a class="easyui-linkbutton" iconCls="icon-filesave" plain="true" onclick="javascript:cek(5)">
                <img src="<?php echo base_url(); ?>assets/images/icon/down_pdf.png" width="14" height="14" title="Save Semua SKPD - *.pdf"/>&nbsp; Save Semua (*.pdf)</a>
                </td>                
            </tr>
        </table>  
            
    </fieldset>  
	<h1><h1>
</div>	
