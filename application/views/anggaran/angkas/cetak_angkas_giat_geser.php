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
   
    <script> 
    var kode='';
    var kegiatan='';
	var xrekening ='';	
    var xnmkegiatan ='';
    var xkegiatan ='';
    var total_pic =0;
    
     $(document).ready(function() {
      $('#tgl_ttd').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();
                return y+'-'+m+'-'+d;
            }
        });

            $("#accordion").accordion();
            $('#ck').combogrid();
            $('#ttd1').combogrid();
            $('#ttd2').combogrid();
    });
  
      $(function(){
        $('#cc').combogrid({  
            panelWidth:700,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka_ro/skpduser',  
            columns:[[  
                {field:'kd_skpd',title:'Kode SKPD',width:100},  
                {field:'nm_skpd',title:'Nama SKPD',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
                kode = rowData.kd_skpd;
                nm = rowData.nm_skpd;
                $("#ck").combogrid("clear"); 
                $("#total_pic2").attr("value",'');
                $("#skpdd").attr("Value",nm);
                ttd();
                jang(); 

            }  
        }); 
      });
      function jang(){
            $('#j_ang').combogrid({  
                    panelWidth:400,  
                    idField:'kode',  
                    textField:'nama',  
                    mode:'remote',
                    url:'<?php echo base_url(); ?>index.php/rka_ro/load_jang/',  
                    columns:[[  
                        {field:'nama',title:'Nama',width:400}    
                    ]],
                    onSelect:function(rowIndex,rowData){
                         kd_jang = rowData.kode;
                         jangkas(kd_jang);
                    }
                });
      }

      function jangkas(kd_jang){
            $('#j_angkas').combogrid({  
                    panelWidth:400,  
                    idField:'kode',  
                    textField:'nama',  
                    mode:'remote',
                    url:'<?php echo base_url(); ?>index.php/rka_ro/load_jangkas/'+kd_jang,  
                    columns:[[  
                        {field:'nama',title:'Nama',width:400}    
                    ]],
                    onSelect:function(rowIndex,rowData){
                        tampil();
                    }
                });
      }
      
    
     function ttd(){   

                $('#ttd1').combogrid({  
                    panelWidth:400,  
                    idField:'urut',  
                    textField:'nip',  
                    mode:'remote',
                    url:'<?php echo base_url(); ?>index.php/rka_ro/load_ttd_unit/'+kode,  
                    columns:[[  
                        {field:'nip',title:'NIP',width:200},  
                        {field:'nama',title:'Nama',width:400}    
                    ]],
                    onSelect:function(rowIndex,rowData){
                        $("#nm_ttd1").attr("value",rowData.nama); 
                    }
                });      

                $('#ttd2').combogrid({  
                    panelWidth:400,  
                    idField:'urut',  
                    textField:'nip',  
                    mode:'remote',
                    url:'<?php echo base_url(); ?>index.php/rka_ro/load_ttd_bud',  
                    columns:[[  
                        {field:'nip',title:'NIP',width:200},  
                        {field:'nama',title:'Nama',width:400}    
                    ]], 
                    onSelect:function(rowIndex,rowData){
                        $("#nm_ttd2").attr("value",rowData.nama);
                    } 

                });  

     }

     function tampil(){
        var tj_jang     = $("#j_ang").combogrid("getValue");
        var tj_jangkas  = $("#j_angkas").combogrid("getValue");
        var skpd        = $("#cc").combogrid("getValue");
        var ttd1        = $("#ttd1").combogrid("getValue");
        var ttd2        = $("#ttd2").combogrid("getValue");
        var tgl         = $("#tgl_ttd").datebox("getValue");
        if(skpd !=''){
            var url="<?php echo site_url(); ?>rka_ro/cetak_angkas_giat_preview/a/2020-01-01/1/1/"+tj_jang+"/"+tj_jangkas+"/"+skpd+"/1/hidden";
            document.getElementById('tampil').innerHTML=
            "<embed src='"+url+"' width='875 px' height='500px'>"; 
        }
    }

    function cetak(cetak){
        var tj_jang     = $("#j_ang").combogrid("getValue");
        var tj_jangkas  = $("#j_angkas").combogrid("getValue");
        var skpd  = $("#cc").combogrid("getValue");
        var ttd1  = $("#ttd1").combogrid("getValue");
        var ttd2  = $("#ttd2").combogrid("getValue");
        var tgl   = $("#tgl_ttd").datebox("getValue");
        if((skpd=='' || ttd1=='') || (ttd2==''|| tgl=='') ){
            alert("Harap Lengkapi Inputan."); exit();
        }
        var url="<?php echo site_url(); ?>rka_ro/cetak_angkas_giat_preview/0cc175b9c0f1b6a831c399e26977266159202463fd4c312b063293b88f6063b28f60c8102d29fcd525162d02eed4566b/"+tgl+"/"+ttd1+"/"+ttd2+"/"+tj_jang+"/"+tj_jangkas+"/"+skpd+"/"+cetak+"/";
        window.open(url);
    }
    </script>

</head>
<?php 
    if($jns_ang=='1'){
        $select1="selected";
        $select2="";
        $select3="";
    }else if($jns_ang=='2'){
        $select1="";
        $select2="selected";
        $select3="";
    }else{
        $select1="";
        $select2="";
        $select3="selected";
    }

 ?>
<body>


<div id="content" > 
   
<div id="accordion" >
<h3><a href="#" id="section1">CETAK ANGKAS KEGIATAN PERGESERAN</a></h3>
   <div  style="height: 800px;">
   <p><h3>
        <table style="border-style: none; border-bottom: none" width="100%">
            <tr >
                <td width="15%" style="border-style: none; border-bottom: none">
                    S K P D
                </td>
                <td style="border-style: none; border-bottom: none">
                    &nbsp;<input id="cc" name="skpd" style="width: 300px;"/> <input  id="skpdd" name="skpdd" style="width: 500px; border-style: none"/>
                </td>
            </tr>
            <tr>
                <td width="15%" style="border-style: none; border-bottom: none">
                    Jenis Anggaran
                </td>
                <td style="border-style: none; border-bottom: none">
                    <input id="j_ang" name="j_ang" style="width: 300px;"/>
                </td>
            </tr>
            <tr>
                <td width="15%" style="border-style: none; border-bottom: none">
                    Jenis Anggaran Kas
                </td>
                <td style="border-style: none; border-bottom: none">
                    <input id="j_angkas" name="j_angkas" style="width: 300px;"/> 
                </td>
            </tr>
            <tr>
                <td width="15%" style="border-style: none; border-bottom: none">
                    Penandatangan
                </td>
                <td style="border-style: none; border-bottom: none">
                    &nbsp;<input id="ttd1" name="ttd1" style="width: 300px;"/> <input  id="nm_ttd1" name="nm_ttd1" style="width: 500px; border-style: none"/>
                </td>
            </tr>
            <tr>
                <td width="15%" style="border-style: none; border-bottom: none">
                    Penandatangan 2
                </td>
                <td style="border-style: none; border-bottom: none">
                    &nbsp;<input id="ttd2" name="ttd2" style="width: 300px;" />  <input  id="nm_ttd2" name="nm_ttd2" style="width: 500px; border-style: none"/>
                </td>
            </tr>
            <tr>
                <td width="15%" style="border-style: none; border-bottom: none">
                    Tanggal ttd
                </td>
                <td style="border-style: none; border-bottom: none">
                    &nbsp;<input id="tgl_ttd" name="kegiatan" style="width: 300px;" /> 
                </td>
            </tr>
            <tr>
                <td width="15%" style="border-style: none; border-bottom: none"></td>
                <td style="border-style: none; border-bottom: none">&nbsp;
                   <button onclick="javascript:cetak(1);" class="easyui-linkbutton" iconCls="icon-print" plain="true" style="cursor: pointer; padding:10px">Cetak</button>
                   <button onclick="javascript:cetak(2);" class="easyui-linkbutton" iconCls="icon-pdf"   plain="true" style="cursor: pointer; padding:10px"> PDF</button>
                   <button onclick="javascript:cetak(3);" class="easyui-linkbutton" iconCls="icon-excel" plain="true" style="cursor: pointer; padding:10px"> Excel</button>
                </td>
            </tr>

        </table>
    </h3>
   </p>
</div>

<br><br>
</div>
<p id="tampil"></p>
</div> 	
</body>

</html>