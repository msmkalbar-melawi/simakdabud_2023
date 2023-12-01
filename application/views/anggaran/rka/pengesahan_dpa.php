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
            height: 500,
            width: 800,
            modal: true,
            autoOpen:false
        });
        });    
     
     $(function(){ 
        $('#kode').combogrid({  
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
               kd = rowData.kd_skpd;               
               $("#nmskpd").attr("value",rowData.nm_skpd.toUpperCase());                
           }  
       });
       
        $('#tgldpa').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();
                return y+'-'+m+'-'+d;
            },
            onSelect: function(date){
              jaka = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
           }
        });
        
        $('#tgldpasempurna').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();
                return y+'-'+m+'-'+d;
            },
            onSelect: function(date){
              jaka = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
           }
        });

        $('#tgldpasempurna2').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();
                return y+'-'+m+'-'+d;
            },
            onSelect: function(date){
              jaka = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
           }
        });

        $('#tgldpasempurna3').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();
                return y+'-'+m+'-'+d;
            },
            onSelect: function(date){
              jaka = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
           }
        });

        $('#tgldpasempurna4').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();
                return y+'-'+m+'-'+d;
            },
            onSelect: function(date){
              jaka = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
           }
        });
        
        $('#tgldpasempurna5').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();
                return y+'-'+m+'-'+d;
            },
            onSelect: function(date){
              jaka = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
           }
        });

        $('#tgldpasempurna5').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();
                return y+'-'+m+'-'+d;
            },
            onSelect: function(date){
              jaka = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
           }
        });

        $('#tgldpasempurna6').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();
                return y+'-'+m+'-'+d;
            },
            onSelect: function(date){
              jaka = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
           }
        });     
        
         $('#tgldppa').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();
                return y+'-'+m+'-'+d;
            },
            onSelect: function(date){
              jaka = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
           }
        });
        
        
     $('#dg').edatagrid({
        rowStyler:function(index,row){
                    if (row.header==1){
                    //return {class:'r1', style:{'color:#fff'}};
                        //return 'background-color:#6293BB;color:#fff;';
                       return 'color:red;font-weight:bold;';
                        //font-weight:bold;
                    }
                 }, 
        url: '<?php echo base_url(); ?>/index.php/rka/load_pengesahan_dpa',
        idField:'id',   
        toolbar: "#toolbar1",       
        rownumbers:"true", 
        fit:"true",
        //fitColumns:"true",
        fitColumns   : false,
        singleSelect:"true",
        autoRowHeight:"false",
        
        loadMsg:"Tunggu Sebentar....!!",
        nowrap:"true",                       
        columns:[[
            {field:'kd_skpd',
            title:'Kode SKPD',
            width:70,
            align:"center"},
            {field:'status_rancang',
            title:'Status RKA',
            width:70,
            align:"center"},
            {field:'tgl_dpa_rancang',
            title:'Tgl RKA',
            width:70,
            align:"center"},            
            {field:'statu',
            title:'Status DPA',
            width:70,
            align:"center"},
            {field:'status_sempurna',
            title:'Status Penyempurnaan',
            width:130,
            align:"center"},
            {field:'status_sempurna2',
            title:'Status Penyempurnaan 2',
            width:140,
            align:"center"},
            {field:'status_sempurna3',
            title:'Status Penyempurnaan 3',
            width:140,
            align:"center"},
            {field:'status_sempurna4',
            title:'Status Penyempurnaan 4',
            width:140,
            align:"center"},
            {field:'status_sempurna5',
            title:'Status Penyempurnaan 5',
            width:140,
            align:"center"},        
            {field:'status_sempurna6',
            title:'Status Penyempurnaan 6',
            width:140,
            align:"center"},            
            {field:'status_ubah',
            title:'Status DPPA',
            width:140,
            align:"center"},
            {field:'no_dpa',
            title:'No DPA',
            width:120,
            align:"center"},
            {field:'tgl_dpa',
            title:'TGL DPA',
            width:120,
            align:"center"},
            {field:'no_dpa_sempurna',
            title:'No DPA Sempurna',
            width:120,
            align:"center"},
            {field:'tgl_dpa_sempurna',
            title:'TGL DPA Sempurna',
            width:120,
            align:"center"},
            {field:'tgldpa_sempurna2',
            title:'TGL DPA Sempurna 2',
            width:140,
            align:"center"},            
            {field:'tgldpa_sempurna3',
            title:'TGL DPA Sempurna 3',
            width:140,
            align:"center"},
            {field:'tgldpa_sempurna4',
            title:'TGL DPA Sempurna 4',
            width:140,
            align:"center"},
            {field:'tgldpa_sempurna5',
            title:'TGL DPA Sempurna 5',
            width:140,
            align:"center"},
            {field:'tgldpa_sempurna6',
            title:'TGL DPA Sempurna 6',
            width:140,
            align:"center"},            
            {field:'no_dpa_ubah',
            title:'No DPPA',
            width:140,
            align:"center"},
            {field:'tgl_dpa_ubah',
            title:'TGL DPPA',
            width:140,
            align:"center"}
        ]],
        
        onSelect:function(rowIndex,rowData){
          ckd_skpd = rowData.kd_skpd;
          csts_rka = rowData.status_rancang;
          csts_dpa = rowData.statu;
          csts_dpa_sempurna = rowData.status_sempurna;
          csts_dpa_sempurna2 = rowData.status_sempurna2;
          csts_dpa_sempurna3 = rowData.status_sempurna3;
          csts_dpa_sempurna4 = rowData.status_sempurna4;
          csts_dpa_sempurna5 = rowData.status_sempurna5;
          csts_dpa_sempurna6 = rowData.status_sempurna6;
          csts_dppa = rowData.status_ubah;
          cno_dpa = rowData.no_dpa;
          cno_dpa_sempurna = rowData.no_dpa_sempurna;
          ctgl_dpa_sempurna = rowData.tgl_dpa_sempurna;
          ctgl_dpa_sempurna2 = rowData.tgldpa_sempurna2;
          ctgl_dpa_sempurna3 = rowData.tgldpa_sempurna3;
          ctgl_dpa_sempurna4 = rowData.tgldpa_sempurna4;
          ctgl_dpa_sempurna5 = rowData.tgldpa_sempurna5;
          ctgl_dpa_sempurna6 = rowData.tgldpa_sempurna6;          
          ctgl_rka = rowData.tgl_rancang;
          ctgl_dpa = rowData.tgl_dpa;
          cno_dppa = rowData.no_dpa_ubah;
          ctgl_dppa = rowData.tgl_dpa_ubah;
          get(ckd_skpd,csts_rka,csts_dpa,csts_dppa,cno_dpa,ctgl_rka,ctgl_dpa,cno_dppa,ctgl_dppa,csts_dpa_sempurna,cno_dpa_sempurna,ctgl_dpa_sempurna); 
          lcidx = rowIndex;                           
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Pengesahan DPA & DPPA'; 
           edit_data();   
        }
        });     
        
    });        

    function get(ckd_skpd,csts_rka,csts_dpa,csts_dppa,cno_dpa,ctgl_rka,ctgl_dpa,cno_dppa,ctgl_dppa,csts_dpa_sempurna,cno_dpa_sempurna,ctgl_dpa_sempurna){
    
        $("#kode").combogrid("setValue",ckd_skpd);

        
        if (csts_dpa==1){            
            $("#stsdpa").attr("checked",true);
        } else {
            $("#stsdpa").attr("checked",false);
        }
        
        
        if (csts_dpa_sempurna==1){            
            $("#stsdpasempurna").attr("checked",true);
        } else {
            $("#stsdpasempurna").attr("checked",false);
        }
        
        if (csts_dppa==1){            
            $("#stsdppa").attr("checked",true);
        } else {
            $("#stsdppa").attr("checked",false);
        }           
        
        $("#dpa").attr("value",cno_dpa);
        $("#tgldpa").datebox("setValue",ctgl_dpa);
        $("#dpasempurna").attr("value",cno_dpa_sempurna);
        $("#tgldpasempurna").datebox("setValue",ctgl_dpa_sempurna);
        
        $("#dppa").attr("value",cno_dppa);
        $("#tgldppa").datebox("setValue",ctgl_dppa);            
    }
  
    function kosong(){
        $("#kode").combogrid("setValue",'');
        $("#nmskpd").attr("value",'')
        $("#csts_rka").attr("checked",false);
        $("#stsdpa").attr("checked",false);
        $("#stsdpasempurna").attr("checked",false);
        $("#stsdppa").attr("checked",false);        
        $("#dpa").attr("value",'');
        $("#tgldpa").datebox("setValue",'');
        $("#dpasempurna").attr("value",'');
        $("#tgldpasempurna").datebox("setValue",'');
        $("#dppa").attr("value",'');
        $("#tgldppa").datebox("setValue",'');
    }
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/rka/load_pengesahan_dpa',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
       function simpan_pengesahan(){
        var ckd = $('#kode').combogrid('getValue');

        
        var cst1 = document.getElementById('stsdpa').checked;
        if (cst1==false){
           cst1=0;
        }else{
            cst1=1;
        }

        var cst3 = document.getElementById('stsdpasempurna').checked;
        if (cst3==false){
           cst3=0;
        }else{
            cst3=1;
        }
        alert("add");

        var cst2 = document.getElementById('stsdppa').checked;
        if (cst2==false){
           cst2=0;
        }else{
            cst2=1;
        }
        var cno1 = document.getElementById('dpa').value;
        var ctgl1 = $('#tgldpa').datebox('getValue');
        var cno3 = document.getElementById('dpasempurna').value;
        var ctgl3 = $('#tgldpasempurna').datebox('getValue');
        var cno2 = document.getElementById('dppa').value;
        var ctgl2 = $('#tgldppa').datebox('getValue');
        if (ckd==''){
            alert('SKPD Tidak Boleh Kosong');
            exit();
        }
        
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/rka/simpan_pengesahan',
                    data: ({tabel:'trhrka',tgl:ctgl1,tgl2:ctgl2,tgl3:ctgl3,kdskpd:ckd,stdpa:cst1,stdppa:cst2,no:cno1,no2:cno2,stsempurna:cst3,no3:cno3}),
                    dataType:"json"
                });
            });

        alert("Data Berhasil disimpan");
        $("#dialog-modal").dialog('close');
        $('#dg').edatagrid('reload');
        
    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Pengesahan DPA & DPPA';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("kode").disabled=true;
        }    
        
     function keluar(){
        $("#dialog-modal").dialog('close');
        lcstatus = 'edit';
     }    
    
   
      
    function addCommas(nStr)
    {
        nStr += '';
        x = nStr.split(',');
        x1 = x[0];
        x2 = x.length > 1 ? ',' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + '.' + '$2');
        }
        return x1 + x2;
    }
    
     function delCommas(nStr)
    {
        nStr += ' ';
        x2 = nStr.length;
        var x=nStr;
        var i=0;
        while (i<x2) {
            x = x.replace(',','');
            i++;
        }
        return x;
    }
  
    
  
   </script>

</head>
<body>

<div id="content"> 
<h3 align="center"><u><b><a>PENGESAHAN DPA & DPPA</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:400px;" border="0">
        <tr>
            
        <td width="5%"><a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a></td>
        <td><input type="text" value="" id="txtcari" style="width:300px;"/></td>

        </tr>
        <tr>
        <td colspan="4">
        <table id="dg" title="LISTING DATA PENGESAHAN" style="width:900px;height:500px;" >  
        </table>
        </td>
        </tr>
    </table>    
    
    </p> 
    </div>   
</div>

<div id="dialog-modal" title="">
    <p class="validateTips" ></p> 
    <fieldset>
     <table align="center" style="width:100%;" border="0">
            <tr>
                <td width="35%">SKPD</td>
                <td width="1%">:</td>
                <td><input id="kode" width="5%"/></td>
                <td colspan="3" width="79%"><input  type="text" style="width:99%;" id="nmskpd"  style="border:0;"/></td>
            </tr> 
            <tr>
            <td >Pengesahan DPA</td>
            <td >:</td>
            <td><input type="checkbox" id="stsdpa"  onclick="javascript:runEffect();"/></td>
            <td width="35%"></td>
            <td width="1%"></td>
            <td ></td>
            </tr>
            <tr>
                <td >NO. DPA</td>
                <td >:</td>
                <td colspan="4"><input type="text" id="dpa"  style="width:99%;"/></td>  
            </tr>           
            <tr>
                <td >Tgl DPA</td>
                <td >:</td>
                <td colspan="4"><input type="text" id="tgldpa" style="width:100px;"/></td>  
            </tr>
            <tr>
            <tr>
                <td>NO. DPA Sempurna</td>
                <td>:</td>
                <td colspan="4"><input type="text" id="dpasempurna" style="width:99%;"/></td>  
            </tr>
            <tr>
                <td width="30%">TGL DPA Sempurna</td>
                <td width="1%">:</td>
                <td><input type="text" id="tgldpasempurna" style="width:100px;"/></td>  
            </tr>
            <td >Pengesahan Penyempurnaan</td>
            <td >:</td>
            <td><input type="checkbox" id="stsdpasempurna"  onclick="javascript:runEffect();"/><!--|<input type="text" id="tgldpasempurna" style="width:100px;"/></td>-->
            <td >Pengesahan Penyempurnaan 4</td>
            <td>:</td>
            <td><input type="checkbox" id="stsdpasempurna4"  onclick="javascript:runEffect();"/><!--|<input type="text" id="tgldpasempurna4" style="width:100px;"/></td>-->
            </tr>
            <tr>
            <td >Pengesahan Penyempurnaan2</td>
            <td >:</td>
            <td><input type="checkbox" id="stsdpasempurna2"  onclick="javascript:runEffect();"/><!--|<input type="text" id="tgldpasempurna2" style="width:100px;"/></td>-->
            <td >Pengesahan Penyempurnaan 5</td>
            <td>:</td>
            <td><input type="checkbox" id="stsdpasempurna5"  onclick="javascript:runEffect();"/><!--|<input type="text" id="tgldpasempurna5" style="width:100px;"/></td>-->
            </tr>
            <tr>
            <td >Pengesahan Penyempurnaan3</td>
            <td >:</td>
            <td><input type="checkbox" id="stsdpasempurna3"  onclick="javascript:runEffect();"/><!--|<input type="text" id="tgldpasempurna3" style="width:100px;"/></td>-->
            <td >Pengesahan Penyempurnaan 6</td>
            <td>:</td>
            <td><input type="checkbox" id="stsdpasempurna6"  onclick="javascript:runEffect();"/><!--|<input type="text" id="tgldpasempurna6" style="width:100px;"/></td>-->
            </tr>
            <tr>
            <td >Pengesahan DPPA</td>
            <td >:</td>
            <td><input type="checkbox" id="stsdppa"  onclick="javascript:runEffect();"/></td>
            <td></td>
            <td></td>
            <td ></td>
            </tr>
            <tr>
                <td>NO. DPA Ubah</td>
                <td>:</td>
                <td colspan="4"><input type="text" id="dppa" style="width:99%;"/></td>  
            </tr>

            <tr>
                <td >TGL DPPA</td>
                <td >:</td>
                <td colspan="4"><input type="text" id="tgldppa" style="width:100px;"/></td>  
            </tr>

            
            <!--<tr>
                <td>NO. DPA Sempurna</td>
                <td>:</td>
                <td colspan="4"><input type="text" id="dpasempurna" style="width:100%;"/></td>  
            </tr>
            
            <tr>
                <td width="30%">TGL DPA</td>
                <td width="1%">:</td>
                <td><input type="text" id="tgldpa" style="width:100px;"/></td>  
            </tr>
            <tr>
                <td width="30%">NO. DPA Sempurna</td>
                <td width="1%">:</td>
                <td><input type="text" id="dpasempurna" style="width:100px;"/></td>  
            </tr>
            
            
            <tr>
                <td width="30%">No. DPPA</td>
                <td width="1%">:</td>
                <td><input type="text" id="dppa" style="width:100px;"/></td>                
            </tr>
            
            <tr>
                <td width="30%">TGL DPPA</td>
                <td width="1%">:</td>
                <td><input type="text" id="tgldppa" style="width:100px;"/></td>  
            </tr>-->
                         
             
            <tr>
            <td colspan="6">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="6" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_pengesahan();">Simpan</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>              
            </tr>
        </table>       
    </fieldset>
</div>

</body>

</html>