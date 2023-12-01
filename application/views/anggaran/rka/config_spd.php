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
    
    var kode = '';
    var giat = '';
    var nomor= '';
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
                    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 600,
            width: 900,
            modal: true,
            autoOpen:false,
        });
        });    
     
  
        $(function(){
   	     $('#tgl_con').datebox({  
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
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/rka/load_konfig_spd',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        nowrap:"true",                       
        columns:[[
    	    {field:'no_konfig_spd',
    		title:'Nomor',
    		width:10,
            align:"center"},
            {field:'tgl_konfig_spd',
    		title:'Tanggal',
    		width:10,
            align:"center"}
        ]],
        onSelect:function(rowIndex,rowData){
          vnomor = rowData.no_konfig_spd;
          vtgl_konfig_spd   = rowData.tgl_konfig_spd;
          vingat1 = rowData.ingat1;
          vingat2 = rowData.ingat2;
          vingat3 = rowData.ingat3;
          vingat4 = rowData.ingat4;
          vingat5 = rowData.ingat5;
          vingat6 = rowData.ingat6;
          vingat7 = rowData.ingat7;
          vingat8 = rowData.ingat8;
          vingat9 = rowData.ingat9;
          vingat10 = rowData.ingat10;
          vingat11 = rowData.ingat11;
          vingat_akhir = rowData.ingat_akhir;
          vmemutuskan = rowData.memutuskan;
          lcidx = rowIndex;
          get(vnomor,vtgl_konfig_spd,vingat1,vingat2,vingat3,vingat4,vingat5,vingat6,vingat7,vingat8,vingat9,vingat10,vingat11,vingat_akhir,vmemutuskan);   
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data LPSAL Lalu'; 
           edit_data();   
        }
        
        });
        
         
              
       

      
    });        
 
    function get(vnomor,vtgl_konfig_spd,vingat1,vingat2,vingat3,vingat4,vingat5,vingat6,vingat7,vingat8,vingat9,vingat10,vingat11,vingat_akhir,vmemutuskan){
        $("#nomor").attr("value",vnomor);
		$("#tgl_con").datebox("setValue",vtgl_konfig_spd);
        $("#ingat_1").attr("value",vingat1);
        $("#ingat_2").attr("value",vingat2);
        $("#ingat_3").attr("value",vingat3);
        $("#ingat_4").attr("value",vingat4);
        $("#ingat_5").attr("value",vingat5);
        $("#ingat_6").attr("value",vingat6);
        $("#ingat_7").attr("value",vingat7);  
        $("#ingat_8").attr("value",vingat8);  
        $("#ingat_9").attr("value",vingat9);  
        $("#ingat_10").attr("value",vingat10);  
        $("#ingat_11").attr("value",vingat11);  
        $("#ingat_akhir").attr("value",vingat_akhir);                          
        $("#memutuskan").attr("value",vmemutuskan);                        
    }
    
    function kosong(){
		cdate = '<?php echo date("Y-m-d"); ?>';
        $("#nomor").attr("value",'');                           
        $("#tgl_con").datebox("setValue",cdate);
    }
    
  
       function hapus(){
        //alert(lcstatus);
        var cno = document.getElementById('nomor').value;
		 $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/hapus_master',
                    data: ({tabel:'trkonfig_spd',cid:'no_konfig_spd',cnid:cno}),
                    dataType:"json",
                    success:function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Hapus..!!');
                            exit();
                        }else if(status=='1'){
                            alert('Data Berhasil Dihapus..!!');
							$('#dg').edatagrid('reload');
							$("#dialog-modal").dialog('close');
                            exit();
                        }else{
                            alert('Gagal Hapus..!!');
                            exit();
                        }
                    }
                });
            });  
	   }
    
    
    
       function simpan(){
        //alert(lcstatus);
        var cno = document.getElementById('nomor').value;
		var ctgl_con = $('#tgl_con').datebox('getValue');     
        var cingat_1 = document.getElementById('ingat_1').value;
        var cingat_2 = document.getElementById('ingat_2').value;
        var cingat_3 = document.getElementById('ingat_3').value;
        var cingat_4 = document.getElementById('ingat_4').value;
        var cingat_5 = document.getElementById('ingat_5').value;
        var cingat_6 = document.getElementById('ingat_6').value;
        var cingat_7 = document.getElementById('ingat_7').value;
        var cingat_8 = document.getElementById('ingat_8').value;
        var cingat_9 = document.getElementById('ingat_9').value;
        var cingat_10 = document.getElementById('ingat_10').value;
        var cingat_11 = document.getElementById('ingat_11').value;
        var cingat_akhir = document.getElementById('ingat_akhir').value;
        var cmemutuskan = document.getElementById('memutuskan').value;
        if (cno==''){
            alert('Nomor Tidak Boleh Kosong');
            exit();
        } 
        if (cingat_1==''){
            alert('Mengingat 1 Tidak Boleh Kosong');
            exit();
        }
        if (cmemutuskan==''){
            alert(' Memutuskan Tidak Boleh Kosong');
            exit();
        }
        
        if(lcstatus=='tambah'){ 
            
            lcinsert = "(no_konfig_spd, tgl_konfig_spd, ingat1, ingat2, ingat3, ingat4, ingat5, ingat6, ingat7, ingat8, ingat9, ingat10, ingat11, ingat_akhir, memutuskan)";
            lcvalues = "('"+cno+"','"+ctgl_con+"','"+cingat_1+"','"+cingat_2+"','"+cingat_3+"','"+cingat_4+"','"+cingat_5+"','"+cingat_6+"','"+cingat_7
                        +"','"+cingat_8+"','"+cingat_9+"','"+cingat_10+"','"+cingat_11+"','"+cingat_akhir+"','"+cmemutuskan+"')";

            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_master',
                    data: ({tabel:'trkonfig_spd',kolom:lcinsert,nilai:lcvalues,cid:'no_konfig_spd',lcid:cno}),
                    dataType:"json",
                    success:function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Simpan..!!');
                            exit();
                        }else if(status=='1'){
                            alert('Data Sudah Ada..!!');
                            exit();
                        }else{
                            alert('Data Tersimpan..!!');
                            exit();
                        }
                    }
                });
            });    
         
          } else{
            
            lcquery = "UPDATE trkonfig_spd SET no_konfig_spd='"+cno+"',tgl_konfig_spd='"+ctgl_con+"',ingat1='"+cingat_1+"',ingat2='"+cingat_2+"',ingat3='"+cingat_3
			+"',ingat4='"+cingat_4+"',ingat5='"+cingat_5+"',ingat6='"+cingat_6+"',ingat7='"+cingat_7+"',ingat8='"+cingat_8+"',ingat9='"+cingat_9+"',ingat10='"+cingat_10
            +"',ingat11='"+cingat_11+"',ingat_akhir='"+cingat_akhir+"',memutuskan='"+cmemutuskan+"' where no_konfig_spd='"+cno+"'";
            //alert(lcquery);
            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/master/update_master',
                data: ({st_query:lcquery}),
                dataType:"json",
                success:function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Simpan..!!');
                            exit();
                        }else{
                            alert('Data Tersimpan..!!');
                            exit();
                        }
                    }
            });
            });
                        
        }        
       // alert("Data Berhasil disimpan");
        $("#dialog-modal").dialog('close');
        $('#dg').edatagrid('reload');
        //section1();
		} 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("nomor").disabled=true;
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("nomor").disabled=false;
        document.getElementById("nomor").focus();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
     }    

    
       

  
    
  
   </script>

</head>
<body>

<div id="content"> 
<div id="accordion">
<h3 align="center"><u><b><a href="#" id="section1">KONFIGURASI SPD</a></b></u></h3>
    <div>
    <p align="right">         
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a>               
        <!--<a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>-->
        <table id="dg" title="Listing KONFIGURASI SPD" style="width:870px;height:450px;" >  
        </table>
 
    </p> 
    </div>   

</div>

</div>

<div id="dialog-modal" title="">
    <fieldset>
     <table align="center" style="width:100%;" border="0">
	        <tr>
                <td >No_konfig_SPD.</td>
                <td></td>
                <td><input type="text" id="nomor" style="width: 200px;"/></td>  
            </tr>            
			<tr>
                <td>Tanggal konfig SPD</td>
                <td></td>
                <td><input type="text" id="tgl_con" /></td>  
            </tr>            
            <tr>
                <td>1.</td>
                <td></td>
                <td><textarea name="ingat_1" id="ingat_1" cols="100" rows="1" ></textarea></td>  
            </tr>             
            <tr>
                <td>2.</td>
                <td></td>
                <td><textarea name="ingat_1" id="ingat_2" cols="100" rows="1"></textarea></td>  
            </tr>
            <tr>
                <td>3.</td>
                <td></td>
                <td><textarea name="ingat_1" id="ingat_3" cols="100" rows="1" ></textarea></td>  
            </tr>
            <tr>
                <td>4.</td>
                <td></td>
                <td><textarea name="ingat_1" id="ingat_4" cols="100" rows="1" ></textarea></td>  
            </tr>
            <tr>
                <td>5.</td>
                <td></td>
                <td><textarea name="ingat_1" id="ingat_5" cols="100" rows="1" ></textarea></td>  
            </tr>
            <tr>
                <td>6.</td>
                <td></td>
                <td><textarea name="ingat_1" id="ingat_6" cols="100" rows="1" ></textarea></td>  
            </tr>
            <tr>
                <td>7.</td>
                <td></td>
                <td><textarea name="ingat_7" id="ingat_7" cols="100" rows="1" ></textarea></td>  
            </tr>
            <tr>
                <td>8.</td>
                <td></td>
                <td><textarea name="ingat_8" id="ingat_8" cols="100" rows="1" ></textarea></td>  
            </tr>
            <tr>
                <td>9.</td>
                <td></td>
                <td><textarea name="ingat_9" id="ingat_9" cols="100" rows="1" ></textarea></td>  
            </tr>
            <tr>
                <td>10.</td>
                <td></td>
                <td><textarea name="ingat_10" id="ingat_10" cols="100" rows="1" ></textarea></td>  
            </tr>
            <tr>
                <td>11.</td>
                <td></td>
                <td><textarea name="ingat_11" id="ingat_11" cols="100" rows="1" ></textarea></td>  
            </tr>
            <tr>
                <td>Akhir.</td>
                <td></td>
                <td><textarea name="ingat_akhir" id="ingat_akhir" cols="100" rows="1" ></textarea></td>  
            </tr>
            <tr>
                <td>Memutuskan</td>
                <td></td>
                <td><textarea name="memutuskan" id="memutuskan" cols="100" rows="1"></textarea></td>  
            </tr>			          
            <tr>
                <td colspan="3" align="center">
				<a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
				<a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>


  	
</body>

</html>