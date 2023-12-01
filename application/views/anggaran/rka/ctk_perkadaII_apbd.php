

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
        
    
     $(function(){ 

            $("#accordion").accordion();
             $("#nm_skpd").attr("value",'');
             $('#urusan').combogrid();
      
            $('#skpd').combogrid({  
                panelWidth:700,  
                idField:'kd_skpd',  
                textField:'kd_skpd',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/rka/skpduser_induk',  
                columns:[[  
                    {field:'kd_skpd',title:'Kode SKPD',width:150},  
                    {field:'nm_skpd',title:'Nama SKPD',width:600}    
                ]],
                onSelect:function(rowIndex,rowData){
                    skpd = rowData.kd_skpd;
                    $("#nm_skpd").attr("value",rowData.nm_skpd);
                    //cetakbawah(skpd);
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
    }); 

     /*function cetakbawah(skpd){
        var  tipe_doc   = document.getElementById('tipe_doc').value;
        var url="<?php echo site_url(); ?>rka/lampiran3_murni/2020-1-1/"+tipe_doc+'/0/'+skpd+'/Lampiran 3 '+tipe_doc;
            document.getElementById('cetakan').innerHTML="<br><embed src='"+url+"' width='100%' height='600px'></embed>";     
     }*/



    function cek2(cetak){
        var  ctglttd    = $('#tgl_ttd').datebox('getValue');
        var  tipe_doc   = document.getElementById('tipe_doc').value;
        var skpd = $("#skpd").combogrid("getValue");

        if(skpd==''){
            alert("Isian Belum Lengkap!");
            exit();
        }
        var url="<?php echo site_url(); ?>rka/lampiran3_murni/"+ctglttd+'/PERGUB/'+cetak+'/'+skpd+'/Lampiran 3 '+tipe_doc;
 
        if (ctglttd == ''){
            alert("Tanggal wajib diisi.");
        } else {
            window.open(url);
        }
    }
    
  
   </script>

<body>
<input type="text" name="tipe_doc" id="tipe_doc"  hidden> <!-- untuk cek rka atau dpa -->
<div id="content">
<fieldset style="border-radius: 20px; border: 3px solid green;">
    <legend><h3><b>CETAK </b></h3></legend>
    <table align="center" style="width:100%;" border="0">
        <tr> 
            <td width="20%">SKPD</td>
            <td width="1%">:</td>
            <td width="79%"><input type="text" id="skpd" style="width: 300px;" onclick="javascript:cetakbawah();"/><input type="text" id="nm_skpd" style="border-style: none; width: 300px;"/>
            </td>
        </tr>
        <tr> 
            <td width="20%">TANGGAL TTD</td>
            <td width="1%">:</td>
            <td width="79%"><input type="text" id="tgl_ttd" style="width: 300px;" />
            </td>
        </tr>         
        <tr> 
            <td width="20%">Cetak</td>
            <td width="1%">:</td>
            <td width="79%">
                <a class="easyui-linkbutton" plain="true" onclick="javascript:cek2(0,'skpd','0');return false" >
                <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="cetak"/></a>
                <a class="easyui-linkbutton" plain="true" onclick="javascript:cek2(1,'skpd','0');return false">                    
                <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>

            </td>
        </tr>   
        </table>  
</fieldset> 

</div>    
</body>
