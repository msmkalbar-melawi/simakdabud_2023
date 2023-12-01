<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
   
   
    var no_lpj   = '';
    var kode     = '';
    var spd      = '';
    var st_12    = 'edit';
    var nidx     = 100
    var spd2     = '';
    var spd3     = '';
    var spd4     = '';
    var lcstatus = '';
    
    // created by tox
    function Left(str, n){
        str=str.trim();
    if (n <= 0)
        return "";
    else if (n > String(str).length)
        return str;
    else
        return String(str).substring(0,n);
    }
     
    function Right(str, n){
        str=str.trim();
    if (n <= 0)
       return "";
    else if (n > String(str).length)
       return str;
    else {
       var iLen = String(str).length;
       return String(str).substring(iLen, iLen - n);
    }
    }
    
// end of note created by tox
        
    $(document).ready(function() {
        
        $("#loading").dialog({
        resizable: false,
        width:200,
        height:130,
        modal: true,
        draggable:false,
        autoOpen:false,    
        closeOnEscape:false
        });
        
        
            $("#accordion").accordion();
            $("#lockscreen").hide();                        
            $("#frm").hide();
            $( "#dialog-modal").dialog({
            height: 240,
            width: 700,
            modal: true,
            autoOpen:false
        });
                get_tahun();
        });
   
       function get_tahun() {
            $.ajax({
                url:'<?php echo base_url(); ?>index.php/sp2d/config_tahun',
                type: "POST",
                dataType:"json",                         
                success:function(data){
                    tahun_anggaran = data;
                    }                                     
            });
             
        }  

    $(function(){

         $('#tgluji').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();
                return y+'-'+m+'-'+d;
            }
        });
        $('#tgl1').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();
                return y+'-'+m+'-'+d;
            }
        });
        $('#tgl2').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();
                return y+'-'+m+'-'+d;
            }
        });
        


         /*   $('#skpd').combogrid({  
           panelWidth:700,  
           idField:'kd_skpd',  
           textField:'kd_skpd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/skpd_2',  
           columns:[[  
               {field:'kd_skpd',title:'Kode SKPD',width:100},  
               {field:'nm_skpd',title:'Nama SKPD',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               kode = rowData.kd_skpd ;
               v_ats_beban = '3'
                $("#nm_skpd").attr("value",rowData.nm_skpd.toUpperCase());
                $('#sp2d').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/sp2d_tu_list',
                                   queryParams:({kode:kode,v_ats_beban:v_ats_beban})
                                   });              
                
           }  
           }); */
          
          
                $('#sp2d').combogrid({
                    url: '<?php echo base_url(); ?>index.php/sp2d/sp2d_list_uji',
                    panelWidth:400,
                    idField:'no_sp2d',  
                    textField:'no_sp2d',
                    mode:'remote',  
                    fitColumns:true,                    
                    columns:[[  
                        {field:'no_sp2d',title:'No SP2D',width:230},  
                        {field:'tgl_sp2d',title:'Tanggal',align:'left',width:70}                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    sp2d = rowData.no_sp2d;
                    tgl_sp2d = rowData.tgl_sp2d;
                    spm=rowData.no_spm;
                    tgl_spm=rowData.tgl_spm;
                    nilai_d=rowData.nilai;
                    //alert('teeeeee');
                    
                    tampil_sp2d(sp2d,tgl_sp2d,spm,tgl_spm,nilai_d);
                    //tampil_kegi(sp2d);                                                        
                    }   
                });
                
          $('#daftar_uji').edatagrid({
            url: '<?php echo base_url(); ?>index.php/sp2d/load_d_uji',
            idField:'id',            
            rownumbers:"true", 
            fitColumns:"true",
            singleSelect:"true",
            autoRowHeight:"false",
            loadMsg:"Tunggu Sebentar....!!",
            pagination:"true",
            nowrap:"true",                       
            columns:[[
                {field:'ck',
                title:'',
                width:20,
                checkbox:'true'},
                {field:'no_uji',
                title:'NO PENGUJI',
                width:60},
                {field:'tgl_uji',
                title:'Tanggal',
                width:40}
            ]],
            onSelect:function(rowIndex,rowData){
              nomer     = rowData.no_uji;                      
              tgluji    = rowData.tgl_uji;
              get(nomer,tgluji);
              detail_trans_3();
              lcstatus = 'edit';                                       
            },
            onDblClickRow:function(rowIndex,rowData){
                sp2d_refresh();
                section1();
            }
        });
                
           
//==grid view edit
              
    var nuji     = document.getElementById('no_uji').value;  
            $('#dg1').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/sp2d/select_detail_uji',
                 queryParams:({ vno_uji:nuji }),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"false",
                 singleSelect:"true",
                 nowrap:"true",
                 columns:[[
                    {field:'idx',title:'idx',width:100,align:'left',hidden:'true'},                                        
                    {field:'no_sp2d',title:'NO SP2D',width:190,align:'left'},
                    {field:'tgl_sp2d',title:'TGL SP2D',width:100,align:'left'},
                    {field:'no_spm',title:'NO SPM',width:190},
                    {field:'tgl_spm',title:'TGL SPM',width:100},
                    {field:'nilai1',title:'Nilai',width:140,align:'right'},
                    {field:'hapus',title:'',width:35,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                    }
                    }
                ]], 
                onSelect:function(rowIndex,rowData){                    
                      
                     // load_sum_spp(); 
                    //   load_sum_pot();
//edit = 'T' ;
                    //  lcstatus = 'edit';
                      },
                    onDblClickRow:function(rowIndex,rowData){
                        /* 
                      kdkegiatan       = rowData.kdkegiatan;            
                      kdrek5       = rowData.kdrek5;
                      nmrek5  = rowData.nmrek5;
                      nilai1  = rowData.nilai1;
                        tambah_rekening(kdkegiatan,kdrek5,nmrek5,nilai1);     */
                    }   
            }); 
            
    });
        
           
        
    function val_ttd(){
           $(function(){
            $('#ttd').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/sp2d/pilih_ttd_bud',  
                    idField:'nip',                    
                    textField:'nip',
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
           });              
         }
         

         function get(nomer,tgluji){
        $("#no_uji").attr("value",nomer);
        $("#no_uji_hide").attr("value",nomer);
        $("#tgluji").datebox("setValue",tgluji);
        cek_uji(nomer);        
       }

    function cek_uji(no){                
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({vno_uji:no}),
            url:"<?php echo base_url(); ?>index.php/sp2d/cek_uji/"+no,
            dataType:"json",
            success:function(data){ 
                cek = data;
                if(cek!=''){
                    $('#del').linkbutton('disable');
                    $('#save').linkbutton('enable');    
                }else{
                    $('#del').linkbutton('enable');
                    $('#save').linkbutton('enable');                        
                }
            }
         });
        });
    }
    

    function getRowIndex(target){  
            var tr = $(target).closest('tr.datagrid-row');  
            return parseInt(tr.attr('datagrid-row-index'));  
        } 
       
    
    function cetak(){
        document.getElementById("no_uji_cetak").value=document.getElementById("no_uji").value;
        val_ttd()
        $("#dialog-modal").dialog('open');
        
      //  $("#cspp").combogrid("setValue",nom);
    } 
    
    
    function keluar(){
        $("#dialog-modal").dialog('close');
    } 
    
    
    function keluar_no(){
        $("#dialog-modal-tr").dialog('close');
    }
      
    
    function cari(){
     var kriteria = document.getElementById("txtcari").value; 
     var skriteria = document.getElementById('skriteria').value;
      $(function(){ 
            $('#daftar_uji').edatagrid({
           url: '<?php echo base_url(); ?>/index.php/sp2d/load_d_uji',
         queryParams:({cari:kriteria,vkriteria:skriteria})
        });        
     });
    }
    
    
    function load_data() {
        $('#load').linkbutton('disable');
        $('#load_kosong').linkbutton('enable');
        var dtgl1        = $('#tgl1').datebox('getValue') ;
        var dtgl2        = $('#tgl2').datebox('getValue') ;
        //var ntotal_trans = document.getElementById('rektotal').value ; 
        //    ntotal_trans = angka(ntotal_trans) ;
        
        if ( dtgl1 == '' ) {
           alert('Isi Tanggal Awal Terlebih Dahulu...!!!'); 
           document.getElementById('tgl1').focus() ;
           exit();
           }       
        if ( dtgl2 == '' ) {
           alert('Isi Tanggal S/D Terlebih Dahulu...!!!'); 
           document.getElementById('tgl2').focus() ;
           exit();
           }
        $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/sp2d/load_data_sp2d_uji',
                data: ({tgl1:dtgl1,tgl2:dtgl2}),
                beforeSend:function(xhr){
                $("#loading").dialog('open');
                    },
                dataType:"json",
                success:function(data){                                          
                    $.each(data,function(i,n){                                    
                    xno_sp2d    = n['no_sp2d'];                                                                                        
                    xtgl_sp2d   = n['tgl_sp2d']; 
                    xno_spm     = n['no_spm'];
                    xtgl_spm    = n['tgl_spm'];
                    xnilai      = n['nilai'];
                    
                   // ntotal_trans = ntotal_trans + angka(xnilai) ;
                    
                   // $('#dg1').edatagrid('appendRow',{no_bukti:xnobukti,kdkegiatan:xgiat,kdrek5:xkdrek5,nmrek5:xnmrek5,nilai1:xnilai,idx:i}); 
                    $('#dg1').edatagrid('appendRow',{no_sp2d:xno_sp2d,tgl_sp2d:xtgl_sp2d,no_spm:xno_spm,tgl_spm:xtgl_spm,nilai1:xnilai,idx:i});                    
                    $('#dg1').edatagrid('unselectAll');
                    
                //    $('#rektotal').attr('value',number_format(ntotal_trans,2,'.',','));
                    });
                $("#loading").dialog('close');
                 }
            });
            });  
        // load_sum_rinci(); 
            
    }
     
    function section1(){
         $(document).ready(function(){    
             $('#section1').click();
         });
     }
     
    
    function section4(){
         $(document).ready(function(){    
             $('#section4').click();                                               
         });
     }
   

function simpan(){    
        var nno_uji     = document.getElementById('no_uji').value;
        var no_uji_hide     = document.getElementById('no_uji_hide').value;
        var tgl_uji     = $('#tgluji').datebox('getValue');
        var no_blk      = '/AD/BL/'+tahun_anggaran;
        if (tgl_uji==''){
            alert('Tanggal Bukti Tidak Boleh Kosong');
            exit();
        }
        
        // Mulai SImpan Header Anguz
        if ( lcstatus=='tambah' ) {
        $(document).ready(function(){
            $.ajax({
                type: "POST",       
                dataType : 'json',         
                url      : "<?php  echo base_url(); ?>index.php/sp2d/simpan_daftar_uji",
                data     : ({tabel:'trhuji',no_uji:nno_uji,tgl_uji:tgl_uji,lcst:lcstatus,no_blk:no_blk}),
                beforeSend:function(xhr){
                $("#loading").dialog('open');
                    },
                success:function(data){
                status = data;
                if (status=='0'){
                   $("#loading").dialog('close');
                   alert('Gagal Simpan...!!');
                   exit();
                } else if (status !='0'){ 
                var nomor_baru = data;
                alert(nomor_baru);
                $('#dg1').datagrid('selectAll');
                var rows = $('#dg1').datagrid('getSelections'); 
                for(var q=0;q<rows.length;q++){
                    xno_sp2d = rows[q].no_sp2d;
                    xtgl_sp2d = rows[q].no_spm;
                    xno_spm       = rows[q].no_spm;
                    xtgl_spm     = rows[q].tgl_spm;
                    xnilai     = angka(rows[q].nilai1);
                        if (q>0) {
                            csql = csql+","+"('"+nomor_baru+"','"+tgl_uji+"','"+xno_sp2d+"')";
                        } else {
                            csql = "values('"+nomor_baru+"','"+tgl_uji+"','"+xno_sp2d+"')";                                            
                            }                                             
                        }                         
                        $(document).ready(function(){
                            //alert(csql);
                            //exit();
                            $.ajax({
                                type: "POST",   
                                dataType : 'json',                 
                                data: ({tabel:'trduji',nomor_baru:nomor_baru,sql:csql}),
                                url: '<?php echo base_url(); ?>/index.php/sp2d/simpan_daftar_uji',
                                success:function(data){                        
                                    status = data;   
                                     if (status=='1'){
                                        $("#loading").dialog('close');
                                        alert('Data Berhasil Tersimpan...!!!');
                                        $("#no_uji").attr("value",nomor_baru);
                                        $("#no_uji_hide").attr("value",nomor_baru);
                                        lcstatus='edit';
                                        //$("#no_simpan").attr("value",cnokas);
                                    } else{ 
                                        $("#loading").dialog('close');
                                        lcstatus='tambah';
                                        alert('Detail Gagal Tersimpan...!!!');
                                    }                                             
                                }
                            });
                            });            
                        }
        
        //Akhir
        sp2d_refresh();
                }
            });
        });
        
        }else{
        $(document).ready(function(){
            $.ajax({
                type: "POST",       
                dataType : 'json',         
                url      : "<?php  echo base_url(); ?>index.php/sp2d/edit_daftar_uji",
                data     : ({tabel:'trhuji',no_uji:nno_uji,no_uji_hide:no_uji_hide,tgl_uji:tgl_uji,lcst:lcstatus,no_blk:no_blk}),
                beforeSend:function(xhr){
                $("#loading").dialog('open');
                    },
                success:function(data){
                status = data;
                if (status=='0'){
                   $("#loading").dialog('close');
                   alert('Gagal Simpan...!!');
                   exit();
                } else if (status !='0'){ 
                var nomor_baru = data;
                alert(nomor_baru);
                $('#dg1').datagrid('selectAll');
                var rows = $('#dg1').datagrid('getSelections'); 
                for(var q=0;q<rows.length;q++){
                    xno_sp2d = rows[q].no_sp2d;
                    xtgl_sp2d = rows[q].no_spm;
                    xno_spm       = rows[q].no_spm;
                    xtgl_spm     = rows[q].tgl_spm;
                    xnilai     = angka(rows[q].nilai);
                        if (q>0) {
                            csql = csql+","+"('"+no_uji_hide+"','"+tgl_uji+"','"+xno_sp2d+"')";
                        } else {
                            csql = "values('"+no_uji_hide+"','"+tgl_uji+"','"+xno_sp2d+"')";                                            
                            }                                             
                        }                         
                        $(document).ready(function(){
                            //alert(csql);
                            //exit();
                            $.ajax({
                                type: "POST",   
                                dataType : 'json',                 
                                data: ({tabel:'trduji',no_uji_hide:no_uji_hide,sql:csql}),
                                url: '<?php echo base_url(); ?>/index.php/sp2d/edit_daftar_uji',
                                success:function(data){                        
                                    status = data.pesan;   
                                     if (status=='1'){
                                        $("#loading").dialog('close');
                                        alert('Data Berhasil Tersimpan...!!!');
                                        $("#no_uji").attr("value",nomor_baru);
                                        $("#no_uji_hide").attr("value",nomor_baru);
                                        lcstatus='edit';
                                        //$("#no_simpan").attr("value",cnokas);
                                    } else{ 
                                        $("#loading").dialog('close');
                                        lcstatus='tambah';
                                        alert('Detail Gagal Tersimpan...!!!');
                                    }                                             
                                }
                            });
                            });            
                        }
        
        //Akhir
                }
            });
        }); 
            
            
        }
        

        
        

        }
     
       
/*    
    function simpan(){        
        var nno_uji     = document.getElementById('no_uji').value;
        var tgl_uji     = $('#tgluji').datebox('getValue');
        var no_blk      = '/AD/BL/'+tahun_anggaran;
        var no_baru     ='';
        if ( lcstatus=='tambah' ) {
            
                                $(document).ready(function(){
                                $.ajax({
                                    type: "POST",       
                                    dataType : 'json',         
                                    url      : "<?php  echo base_url(); ?>index.php/tukd/simpan_update_d_uji",
                                    data     : ({no_uji:nno_uji,tgl_uji:tgl_uji,lcst:lcstatus,no_blk:no_blk}),
                                    success:function(data){
                                    status = data;  
                                    no_baru=data;
                                    $("#no_uji").attr("value",data);
                                    
                                    }
                                });
                            });

        }
     else 
     {
 
// Update data
         var tny = confirm('Yakin Ingin Update Data LPJ No :  '+nno_uji+'  ..?');
        
        if ( tny == true ) {            
        var nuji_hide      = document.getElementById('no_uji_hide').value;      
        no_baru=nno_uji;
                        $(document).ready(function(){
                            $.ajax({
                                type: "POST",       
                                dataType : 'json',         
                                url      : "<?php  echo base_url(); ?>index.php/tukd/simpan_update_d_uji",
                                data     : ({no_uji:nno_uji,tgl_uji:tgl_uji,lcst:lcstatus,no_blk:no_blk}),
                                success:function(data){
                                    status = data;                                                               
                                
                                }
                            });         
                        });
                    }

//=========DNA
 else {
            exit();
        }
}

// -------- DETAIl
    if (status=='0'){
        alert('Gagal Simpan..!!');
        exit();
    } else 
    {
        alert('posisi simpan Head');
        dsimpan(no_baru,tgl_uji);       
    }
// DETAIL
    }
    */
     
    function dsimpan(no_baru,tgl_uji)   {
         $(document).ready(function(){ 
                       $.ajax({
                       type     : 'POST',
                       url      : "<?php  echo base_url(); ?>index.php/sp2d/simpan_detail_d_uji_hapus",
                       data     : ({no_uji:no_baru}),
                       dataType : "json",
                       success  : function(data){
                                    status = data;
                                    if (status=='0'){
                                        alert('Gagal Hapus Detail Old');
                                        exit();
                                    } 
                                    }
                                    });
        alert('posisi delete data');
        });
                             
        $('#dg1').datagrid('selectAll');
        var rows = $('#dg1').datagrid('getSelections');
    
        for(var i=0;i<rows.length;i++){            
            cidx      = rows[i].idx;
            cno_sp2d   = rows[i].no_sp2d;
            ctgl_sp2d   = rows[i].tgl_sp2d;
            cno_spm    = rows[i].no_spm;
            ctgl_spm    = rows[i].tgl_spm;
            cnilai    = angka(rows[i].nilai);

           no        = i + 1 ;      
            $(document).ready(function(){      
            $.ajax({
            type     : 'POST',
            url      : "<?php  echo base_url(); ?>index.php/sp2d/simpan_detail_d_uji",
            data     : ({no_sp2d:cno_sp2d,tgl_uji:tgl_uji,no_uji:no_baru}),
            dataType : "json",
            
                         success  : function(data){
                         status = data;
alert('posisi simpan detail data');
                    }

        });
        });
        }

                         if (status=='0'){
                            alert('Gagal Simpan..!!');
                            exit();
                        } else {
                                //  detsimpan() ;
                                alert('Data Tersimpan...!!!');
                                lcstatus = 'edit';
                                sp2d_refresh();
                                section1();
                               }
               
                        
        $('#dg1').edatagrid('unselectAll');
    }
    
    function kembali(){
        $('#kem').click();
    }                
   
        
    function openWindow($cetak)
    {
            var vnouji  =document.getElementById('no_uji').value;
            vnouji =vnouji.split("/").join("123456789");
            var cetak = $cetak;             
            var ttd    = nip;                           
            var ttd1 =ttd.split(" ").join("abcdefg"); 
            var skpd   = kode;
            var dcetak = $('#tgluji').datebox('getValue'); 
            var atas   =  document.getElementById('atas').value;
            var bawah   =  document.getElementById('bawah').value;
            var kanan   =  document.getElementById('kanan').value;
            var kiri   =  document.getElementById('kiri').value;            
            //alert(dcetak);
            var url    = "<?php echo site_url(); ?>sp2d/cetak_daftar_penguji"; 
    
            window.open(url+'/'+vnouji+'/'+ttd1+'/'+dcetak+'/'+cetak+'/'+atas+'/'+bawah+'/'+kiri+'/'+kanan);
            window.focus();
    }
    
        
    function detail_trans_3(){
       // detail_trans_kosong();
       nomer= document.getElementById('no_uji').value;
        $(function(){
            $('#dg1').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/sp2d/select_detail_uji',
                queryParams:({ vno_uji:nomer }),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"false",
                 singleSelect:"true",
                 nowrap:"true",
                 onLoadSuccess:function(data){                       
                 },                              
                onSelect:function(rowIndex,rowData){
                kd  = rowIndex ;  
                idx =  rowData.idx ;                                           
                },
                 columns:[[
                     {field:'idx',
                     title:'idx',
                     width:100,
                     align:'left',
                     hidden:'true'
                     },                                         
                     {field:'no_sp2d',
                     title:'NO SP2D',
                     width:150,
                     align:'left'
                     },
                    {field:'tgl_sp2d',
                     title:'TGL SP2D',
                     width:100,
                     align:'left'
                     },
                    {field:'no_spm',
                     title:'NO SPM',
                     width:150,
                     align:'left'
                     },
                    {field:'tgl_spm',
                     title:'Tgl SPM',
                     width:100,
                     },
                    {field:'nilai1',
                     title:'Nilai',
                     width:100,
                     align:'right'
                     },
                    {field:'hapus',title:'',width:35,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                    }
                    }
                ]]  
            });
        });
        }
        

    function detail_trans_kosong(){
        $('#load').linkbutton('enable');
        $('#load_kosong').linkbutton('disable');
         nomer= '';
        $(function(){
            $('#dg1').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/sp2d/select_detail_uji',
                queryParams:({ vno_uji:nomer }),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"false",
                 singleSelect:"true",
                 nowrap:"true",
                 onLoadSuccess:function(data){                       
                 },                              
                onSelect:function(rowIndex,rowData){
                kd  = rowIndex ;  
                idx =  rowData.idx ;                                           
                },
                 columns:[[
                     {field:'idx',
                     title:'idx',
                     width:100,
                     align:'left',
                     hidden:'true'
                     },                                         
                     {field:'no_sp2d',
                     title:'NO SP2D',
                     width:150,
                     align:'left'
                     },
                    {field:'tgl_sp2d',
                     title:'TGL SP2D',
                     width:100,
                     align:'left'
                     },
                    {field:'no_spm',
                     title:'NO SPM',
                     width:150,
                     align:'left'
                     },
                    {field:'tgl_spm',
                     title:'Tgl SPM',
                     width:100,
                     },
                    {field:'nilai1',
                     title:'Nilai',
                     width:100,
                     align:'right'
                     },
                    {field:'hapus',title:'',width:35,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                    }
                    }
                ]]  
            });
        }); 

        }
    
        
   
    
    function hapus_detail(){
        
        var a          = document.getElementById('no_uji').value;
        var rows       = $('#dg1').edatagrid('getSelected');
        
        bno_sp2d      = rows.no_sp2d;
        bnilai      = rows.nilai;
        
        var idx = $('#dg1').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, No SP2D :  '+bno_sp2d+' - Nilai :  '+bnilai+' ?');
        
        if ( tny == true ) {
            
            $('#dg1').datagrid('deleteRow',idx);     
            $('#dg1').datagrid('unselectAll');
              
            // var urll = '<?php echo base_url(); ?>index.php/tukd/dsimpan_lpj';
            /*  $(document).ready(function(){
             $.post(urll,({cnolpj:a,cnobukti:bbukti}),function(data){
             status = data;
                if (status=='0'){
                    alert('Gagal Hapus..!!');
                    exit();
                } else {
                    alert('Data Telah Terhapus..!!');
                    exit();
                }
             });
             });  */   
        }     
    }
    
  function hhapus(){                
            var no_uji = document.getElementById("no_uji_hide").value;              
            var urll= '<?php echo base_url(); ?>/index.php/sp2d/hhapusuji';                             
            if (no_uji !=''){
                var del=confirm('Anda yakin akan menghapus No Uji '+no_uji+'  ?');
                if  (del==true){
                    $(document).ready(function(){
                    $.post(urll,({no:no_uji}),function(data){
                    status = data;                       
                    });
                    });             
                }
                } 
    }
  
   

  
    function tampil_sp2d(sp2d,tgl_sp2d,spm,tgl_spm,nilai_d) {
                    //,idx:i
                    
            $('#dg1').datagrid('selectAll');
            var rows = $('#dg1').datagrid('getSelections');           
            for(var p=0;p<rows.length;p++){
                cno_sp2d  = rows[p].no_sp2d;
                if (cno_sp2d==sp2d) {
                    alert('Nomor ini sudah ada di LIST');
                    exit();
            }
            }       
            $('#dg1').datagrid('selectAll');            
            jgrid     = rows.length ; 
            pidx = jgrid + 1 ;
                    $('#dg1').edatagrid('appendRow',{no_sp2d:sp2d,tgl_sp2d:tgl_sp2d,no_spm:spm,tgl_spm:tgl_spm,nilai1:nilai_d,idx:pidx}); 
                    $('#dg1').edatagrid('unselectAll');

    }  
    
    
        function kosong(){
        $("#no_uji").attr("value",'');
        $("#tgluji").datebox("setValue",'');
        $("#sp2d").combogrid("setValue",'');
        detail_trans_kosong();
        lcstatus = 'tambah';
        $('#load').linkbutton('enable');
        $('#load_kosong').linkbutton('disable');
        sp2d_refresh();
        $('#save').linkbutton('enable');
        $('#del').linkbutton('enable');

        }
    
    function keluar_rek(){
        $("#dialog-modal-sp2d").dialog('close');
        $("#dg1").datagrid("unselectAll");
        $("#rek_nilai").attr("Value",0);
        $("#nilai_sp2d").attr("Value",0);
        $("#sisa_sp2d").attr("Value",0);
    }  
    
    function sp2d_refresh(){
        $("#sp2d").combogrid("clear");
        $('#sp2d').combogrid({url:'<?php echo base_url(); ?>index.php/sp2d/sp2d_list_uji'});
    }  
        
       function update_save() {
        
            $('#dg1').datagrid('selectAll');
           // var rows  = $('#dg1').datagrid('getSelections') ;
            var rows       = $('#dg1').datagrid('getSelected');
                jgrid = rows.length ;
                nilai_awal=rows.nilai1;

                var idx = $('#dg1').datagrid('getRowIndex',rows);
            
            var jumtotal  = document.getElementById('rektotal').value ;
                jumtotal  = angka(jumtotal);
        
            var vrek_skpd =  document.getElementById('rek_skpd').value;
            var vrek_kegi =  document.getElementById('rek_kegi').value;
            var vrek_reke =  document.getElementById('rek_reke').value;
            var cnil      = document.getElementById('rek_nilai').value;
            var cnilai    = cnil;      
            
            
            var cnil_sisa   = angka(document.getElementById('nilai_sp2d').value) ;
            var cnil_input  = angka(document.getElementById('rek_nilai').value) ;
            
            if ( cnil_input > cnil_sisa ){
                 alert('Nilai Melebihi nilai SP2D...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( cnil_input == 0 ){
                 alert('Nilai Nol.....!!!, Cek Lagi...!!!') ;
                 exit();
            }
            
            
            var vnm_rek_reke = document.getElementById('nm_rek_reke').value;
            
                pidx = jgrid ;
                pidx = pidx + 1 ;
            $('#dg1').datagrid('updateRow',{index:idx,row:{kdkegiatan:vrek_kegi,kdrek5:vrek_reke,nmrek5:vnm_rek_reke,nilai1:cnilai,idx:idx}});
                        
            $("#dialog-modal-sp2d").dialog('close'); 
            
            jumtotal = jumtotal + angka(cnil)-angka(nilai_awal);
            
            $("#rektotal").attr('value',number_format(jumtotal,2,'.',','));
            $("#rektotal1").attr('value',number_format(jumtotal,2,'.',','));
            $("#dg1").datagrid("unselectAll");
            
       }
       
        
        
    </script>
    
    <STYLE TYPE="text/css"> 
         input.right{ 
         text-align:right; 
         } 
    </STYLE> 

</head>
<body>

<div id="content">
<div id="accordion" style="width:950px;height=970px;" >
<h3><a href="#" id="section4" onclick="javascript:$('#daftar_uji').edatagrid('reload')">List Daftar Penguji </a></h3>
<div>
    <p align="right">  
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section1();kosong();">Tambah</a>               
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
         <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">Cetak</a>
        <select name="skriteria" id="skriteria" style="width:200px;" >
            <option value="">No Advice </option>
            <option value="1">No SP2D</option>
        </select>

        <input type="text" value="" id="txtcari"/>
        <table id="daftar_uji" title="List Daftar Penguji" style="width:910px;height:450px;" >  
        </table>
    </p> 
</div>
<h3><a href="#" id="section1">Input Daftar Penguji</a></h3>

   <div  style="height: 350px;">
 <fieldset style="width:850px;height:650px;border-color:white;border-style:hidden;border-spacing:0;padding:0;">            

<table border='0' style="font-size:11px" >
 
  
  <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
   <td  width='20%'>No Penguji</td>
   <td width='80%'><input type="text" name="no_uji" id="no_uji" style="width:225px" readonly="true" placeholder="Terisi Otomatis sesuai urutan"/> 
   <input type="hidden" name="no_uji_hide" id="no_uji_hide" style="width:140px"/></td>
 </tr>
  <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"> 
   <td  width='20%'>Tanggal</td>
 <td>&nbsp;<input id="tgluji" name="tgluji" type="text" style="width:95px" /></td>   
 </tr>
        <tr style="height: 30px;">
      <td colspan="2">
                  <div align="right">
                    <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Baru</a>
                    <a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true"  onclick="javascript:simpan();">Simpan</a>
                    <a id="del"class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hhapus();javascript:section4();">Hapus</a>
                    <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a>
                    <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section4();">Kembali</a>
                  </div>
        </td>                
  </tr>
   <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
    <td width='20%'>Pilih No SP2D</td>
   <td width="80%">&nbsp;<input id="sp2d" name="sp2d" style="width:300px" /></td> 
</tr>     
<!--
<tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"> 
 <td colspan="2">&nbsp;<input id="tgl1" name="tgl1" type="text" style="width:95px" /> S/D <input id="tgl2" name="tgl2" type="text" style="width:95px" /> 
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
<a id="load" style="width:70px" class="easyui-linkbutton" iconCls="icon-add" plain="true"  onclick="javascript:load_data();" >Tampil</a>
     &nbsp;&nbsp;&nbsp;&nbsp;<a id="load_kosong" style="width:70px" class="easyui-linkbutton" iconCls="icon-remove" plain="true"  onclick="javascript:detail_trans_kosong();" >Kosong</a> </td>   
 </tr>
-->
  </table>
  
        <table id="dg1" title="Input Detail LPJ" style="width:900%;height:300%;" >  
        </table>

   </p>

</fieldset>     
</div>
</div>
        <div id="loading" title="Loading...">
        <table align="center">
        <tr align="center"><td><img id="search1" height="50px" width="50px" src="<?php echo base_url();?>/image/loadingBig.gif"  /></td></tr>
        <tr><td>SEDANG MEMUAT...</td></tr>
        </table>
        </div>
</div> 

<div id="dialog-modal" title="CETAK">
    
    <p class="validateTips"></p>  
    <fieldset>
        <table>
            <tr>            
                <td width="110px" >No Daftar penguji:</td>
                <td><input id="no_uji_cetak" name="no_uji_cetak" style="width: 210px; " readonly="true"/></td>
            </tr>
            
            <tr>
                <td width="110px">Penandatangan:</td>
                <td><input id="ttd" name="ttd" style="width: 170px;" /></td>
            </tr>
            <tr >
            <td colspan='2'width="20%" height="40" ><strong>Ukuran Margin Untuk Cetakan PDF (Milimeter)</strong></td>
        </tr>
        <tr >
            <td colspan='2'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Kiri  : &nbsp;<input type="number" id="kiri" name="kiri" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
            Kanan : &nbsp;<input type="number" id="kanan" name="kanan" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
            Atas  : &nbsp;<input type="number" id="atas" name="atas" style="width: 50px; border:1" value="22" /> &nbsp;&nbsp;
            Bawah : &nbsp;<input type="number" id="bawah" name="bawah" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
            </td>
        </tr>
            
            
        </table>  
    </fieldset>
    
    <div>
    </div>     
    <a  class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:openWindow(0);return false;">Cetak Layar</a> 
    <a  class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:openWindow(1);return false;">Cetak PDF</a> 
    <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>  

</div>
    
</body>
</html>