

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
   
     $(function(){ 
        
        get_skpd();
      
       $('#tgl1').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });  
        
        $('#tgl_ttd').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });   
        
        $('#bulan').combogrid({  
                   panelWidth:120,
                   panelHeight:300,  
                   idField:'bln',  
                   textField:'nm_bulan',  
                   mode:'remote',
                   url:'<?php echo base_url(); ?>index.php/rka/bulan',  
                   columns:[[ 
                       {field:'nm_bulan',title:'Nama Bulan',width:700}    
                   ]] 
               });  
    });     
    function get_skpd() {
            $('#skpd').combogrid({
                panelWidth: 700,
                idField: 'kd_skpd',
                textField: 'kd_skpd',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/rka/skpd',
                columns: [
                    [{
                            field: 'kd_skpd',
                            title: 'Kode SKPD',
                            width: 100
                        },
                        {
                            field: 'nm_skpd',
                            title: 'Nama SKPD',
                            width: 700
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    // $("#skpd").attr("value", data.kd_skpd);       
                    $("#nmskpd").attr("value", rowData.nm_skpd);
                    ttd1();
                    ttd2();
                }
            });
        } 

        function ttd1() {
        var skpd = $('#skpd').combogrid('getValue');
        $(function() {
            $('#ttd1').combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/tukd/load_ttd/pa/' + skpd,
                columns: [
                    [{
                            field: 'nip',
                            title: 'NIP',
                            width: 200
                        },
                        {
                            field: 'nama',
                            title: 'Nama',
                            width: 400
                        }
                    ]
                ]
            });
        });
    }

    function ttd2() {
        var skpd = $('#skpd').combogrid('getValue');
        $(function() {
            $('#ttd2').combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/tukd/load_ttd/bk/' + skpd,
                columns: [
                    [{
                            field: 'nip',
                            title: 'NIP',
                            width: 200
                        },
                        {
                            field: 'nama',
                            title: 'Nama',
                            width: 400
                        }
                    ]
                ]
            });
        });
    }

    function cetak(jenis){
		var skpd = $('#skpd').combogrid('getValue');
        var nm_skpd = document.getElementById('nmskpd').value;
        var cbulan = $('#bulan').combogrid('getValue');
        var ctglttd = $('#tgl_ttd').datebox('getValue');
        var cttd1 = $('#ttd1').combogrid('getValue');
        var cttd2 = $('#ttd2').combogrid('getValue');
        var spasi  = document.getElementById('spasi').value; 
        var url = "<?php echo site_url(); ?>cetak_buku_tunai/cetak_kas_tunai/" + jenis + "/" + skpd + "/"+ nm_skpd + "/" + cbulan + "/" + ctglttd + "/" + cttd1 + "/" + cttd2 + "/" + spasi;
        if(skpd == ''){
        alert("Skpd Tidak boleh kosong");
        return;
        }
        if(cbulan == ''){
        alert("Bulan Tidak boleh kosong");
        return;
        }else if(ctglttd == ''){
        alert("Tanggal Tanda tangan Tidak boleh kosong");
        return;
        }else if(cttd1 == ''){
        alert("Tanda tangan Tidak boleh kosong");
        return;
        }else if(cttd2 == ''){
        alert("Tanda tangan Tidak boleh kosong");
        return;
        }else{
        window.open(url);
        }  
    }  
      
   </script>


<div id="content" align="center"> 
    <h3 align="center"><b>BUKU KAS TUNAI</b></h3>
    
     <table align="center" style="width:100%;" border="0">

     <tr>
            <td colspan="3">
                <div id="div_skpd">
                    <table style="width:100%;" border="0">
                        <td width="20%">SKPD</td>
                        <td width="1%">:</td>
                        <td width="79%"><input id="skpd" name="skpd" style="width: 200px;" />&ensp;
                            <input type="text" id="nmskpd" readonly="true" style="width: 400px;border:0" />
                        </td>
                    </table>
                </div>
            </td>
    </tr>
            
           
            <tr>
                <td colspan="3">
                
                <div id="div_periode">
                        <table style="width:100%;" border="0">
                            <td width="20%">PERIODE</td>
                            <td width="1%">:</td>
                            <td width="79%"><input type="text" id="bulan" style="width: 200px;" /> 
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
                            <td><input type="text" id="tgl_ttd" style="width: 200px;" /> 
                            </td> 
                        </table>
                </div>
                </td> 
            </tr>
			<tr>
                <td colspan="3">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
                            <td width="20%">Bend. Pengeluaran</td>
                            <td width="1%">:</td>
                            <td><input type="text" id="ttd1" style="width: 200px;" /> 
							<input type="text" id="nmttd1" style="width: 200px;border:0" /> 
                            </td> 
                        </table>
                </div>
                </td> 
            </tr>
			<tr>
                <td colspan="3">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
                            <td width="20%">Pengguna Anggaran</td>
                            <td width="1%">:</td>
                            <td><input type="text" id="ttd2" style="width: 200px;" />  
							<input type="text" id="nmttd2" style="width: 200px;border:0" /> 
                            </td> 
                        </table>
                </div>
                </td> 
            </tr>
			<tr>
				 <td colspan="3">
				 <div id="div_bend">
					<table style="width:100%;" border="0">
						<td width="20%">Spasi</td>
						<td width="1%">:</td>
						<td><input type="number" id="spasi" style="width: 200px;" value="1"/></td>                       
					</table>
				</td>
			</tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center">
                <button  class="button-biru" plain="true" onclick="javascript:cetak(0)"><i class="fa fa-print"></i> Layar</button>
                <button  class="button-kuning" plain="true" onclick="javascript:cetak(1)"><i class="fa fa-file-pdf-o"></i> PDF</button>
                <?php
                ?> 
                
                </td>                
            </tr>
        </table>  
            
  
</div>	
