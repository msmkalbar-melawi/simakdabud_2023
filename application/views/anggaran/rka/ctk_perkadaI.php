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
    var ctk = '';
        
   
        $(function(){
        $('#tgl_ttd').datebox({  
            required:true,
            formatter :function(date){
              var y = date.getFullYear();
              var m = date.getMonth()+1;
              var d = date.getDate();
              return y+'-'+m+'-'+d;
            }
        });
    
    
    
    $('#cunit').combogrid({  
            panelWidth:700,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka/skpd',  
            columns:[[  
                {field:'kd_skpd',title:'Kode Unit',width:100},  
                {field:'nm_skpd',title:'Nama Unit',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
        skpd = rowData.kd_skpd;
                $("#nmskpd").attr("value",rowData.nm_skpd);
           $(function(){
            $('#ttd1').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/rka/load_ttd_unit/'+skpd,  
                    idField:'nip',  
                    textField:'nama',
                    mode:'remote',  
                    fitColumns:true
                });
        
         $('#ttd2').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/rka/load_ttd_gub/'+skpd,  
                    idField:'nip',  
                    textField:'nama',
                    mode:'remote',  
                    fitColumns:true
                });
        
        
           });
       
       
        }  
            });

         
     
     $(function(){  
            $('#ttd2').combogrid({  
                panelWidth:400,  
                idField:'nip',  
                textField:'nama',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/rka/load_ttd_gub',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},  
                    {field:'nama',title:'Nama',width:400}    
                ]]  
            });          
         });
    
    
    

    }); 

  function cek($cetak,$jns){
         var ckdskpd = $('#cunit').combogrid('getValue');        
         if ($('input[name="chkrancang"]:checked').val()=='1'){
            var chkrancang = '1';
         }else{
            var chkrancang = '0';
         } 
         if ($jns=='skpd'){
             var ckdskpd = ckdskpd.substring(0,7);       
         }
                            
        if ($jns=='all'){
             var ckdskpd = 'all';       
         }

            url="<?php echo site_url(); ?>/perda_penetapan/preview_perkadaI/"+ckdskpd+'/'+$cetak+'/'+chkrancang;

        openWindow( url,$jns );
    }
    
 
 function openWindow( url,$jns ){
            var ckdskpd = $('#cunit').combogrid('getValue');
           var  ctglttd = $('#tgl_ttd').datebox('getValue');
       var ckdunit = $('#cunit').combogrid('getValue');     
            var ttd_2 = $('#ttd2').combogrid('getValue');
            var ttd2 = ttd_2.split(" ").join("a");
                    
           if ($jns != 'all')
           { 
                if (ckdunit=='' ){
                    alert("Kode Unit Tidak Boleh Kosong"); 
                return;
                }
           }
           /*
       if (ttd=='' || ctglttd=='' ){
       alert("Penanda tangan 1 atau tanggal Tanda tangan tidak boleh kosong");
       } else {
            lc = '?tgl_ttd='+ctglttd+'&ttd1='+ttd1+'&ttd2='+ttd2+'';
      window.open(url+lc,'_blank');
      window.focus();
      }*/
            
            lc = '?tgl_ttd='+ctglttd+'&ttd2='+ttd2+'';
      window.open(url+lc,'_blank');
      window.focus();
      
     } 
   
   function alltrim(kata){
   //alert(kata);
    b = (kata.split(' ').join(''));
    c = (b.replace( /\s/g, ""));
    return c
   
   }
   </script>

  <div id="content">        
      <h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp; 
       </h1>
        
        <?php echo form_close(); ?>   
    
    <?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
    
        <table style="width:100%;" border="0"> 
      <tr>  
            <td width="20%" style="border: none;">Unit</td>
            <td width="1%" style="border: none;">:</td>
            <td style="border: none;"><input type="text" id="cunit" style="width: 100px;" /> 
            <input type="text" id="nmskpd" readonly="true" style="width: 605px;border:0" /></td>
      <tr>
          <tr>
            <td style="border: none;">TANGGAL TTD</td>
            <td style="border: none;">:</td>
            <td style="border: none;"><input type="text" id="tgl_ttd" style="width: 100px;" /> </td> 
      </tr>
      <tr>
      <td style="border: none;">Penandatanganan</td>
            <td style="border: none;">:</td>
            <td style="border: none;"><input type="text" id="ttd2" style="width: 200px;" />&nbsp;&nbsp;<input type="checkbox" name="chkrancang" id="chkrancang" value="1"/> Rancangan
            </td> 
      </tr> 
        </table>


        <table class="narrow">
        <tr>
           <td width="10%" style="border: none;">Cetak Unit</td>
           <td style="border: none;">    
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(0,'unit');return false" >
                    <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="preview"/></a>
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(1,'unit');return false">                    
                    <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
           </td>    
        </tr>
        
        <tr>
           <td style="border: none;">Cetak SKPD</td>
            <td style="border: none;"> 
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(0,'skpd');return false" >
                    <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="preview"/></a>
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(1,'skpd');return false">                    
                    <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
           </td>    
        </tr>
        
        <tr>
           <td >Keseluruhan</td>
            <td > 
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(0,'all');return false" >
                    <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="preview"/></a>
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(1,'all');return false">                    
                    <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
           </td >    
        </tr>


        </table>        
        <div class="clear"></div>
  </div>