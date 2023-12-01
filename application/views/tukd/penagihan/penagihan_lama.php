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
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.css" />
 
    <style>    
    #tagih {
        position: relative;
        width: 500px;
        height: 70px;
        padding: 0.4em;
    }  
    
    </style>
    <script type="text/javascript">
    
    var kode     = '';
    var giat     = '';
    var jenis    = '';
    var nomor    = '';
    var cid      = 0;
    var lcstatus = '';
                      
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 650,
                width: 1050,
                modal: true,
                autoOpen:false                
            });              
            $("#tagih").hide();
            get_skpd();
            get_tahun();
            seting_tombol();
            cek_sumber(); 
             
            document.getElementById("nil_daknf").readOnly = false;
            document.getElementById("nil_dak").readOnly = true;
        });    
     
     
     $(function(){ 
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/penagihan/load_penagihan',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
            {field:'no_bukti',
            title:'Nomor Bukti',
            width:50},
            {field:'tgl_bukti',
            title:'Tanggal',
            width:30},
            {field:'nm_skpd',
            title:'Nama SKPD',
            width:100,
            align:"left"},
            {field:'ket',
            title:'Keterangan',
            width:100,
            align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          nomor = rowData.no_bukti;
          tgl   = rowData.tgl_bukti;
          kode  = rowData.kd_skpd;
          nama  = rowData.nm_skpd;
          ket   = rowData.ket;          
          jns   = rowData.jns_beban; 
          tot   = rowData.total;
          notagih=rowData.no_tagih;
          tgltagih=rowData.tgl_tagih;
          ststagih=rowData.sts_tagih;
          sts=rowData.status;
          jenis=rowData.jenis;
          kontrak=rowData.kontrak;
          get(nomor,tgl,kode,nama,ket,jns,tot,notagih,tgltagih,ststagih,sts,jenis,kontrak);   
          load_detail();  
          load_tot_tagih();
        },
        onDblClickRow:function(rowIndex,rowData){         
            section2(); 
            lcstatus = 'edit';
        }
    });
    
    
    $('#dg1').edatagrid({  
            toolbar:'#toolbar',
            rownumbers:"true", 
            fitColumns:"true",
            singleSelect:"true",
            autoRowHeight:"false",
            loadMsg:"Tunggu Sebentar....!!",            
            nowrap:"true",
            onSelect:function(rowIndex,rowData){                    
                    idx = rowIndex;
                    nilx = rowData.nilai;
            },                                                     
            columns:[[
            {field:'no_bukti',
            title:'No Bukti',           
            hidden:"true"},
            {field:'no_sp2d',
            title:'No SP2D',            
            hidden:"true"},
            {field:'kd_sub_kegiatan',
            title:'Kegiatan',
            width:50},
            {field:'nm_sub_kegiatan',
            title:'Nama Kegiatan',          
            hidden:"true"},
            {field:'kd_rek5',
            title:'Kode Rekening',
            width:30},
            {field:'nm_rek5',
            title:'Nama Rekening',
            width:100,
            align:"left"},
            {field:'pad',
            title:'PAD',
            align:"right",
            width:30},
            {field:'dak',
            title:'DAK FISIK',
            align:"right",
            width:30},
            {field:'daknf',
            title:'DAK NF',
            align:"right",
            width:30},            
            {field:'dau',
            title:'DAU',
            align:"right",
            width:30},
            {field:'dbhp',
            title:'DBHP',
            align:"right",
            width:30},
            {field:'did',
            title:'DID',
            align:"right",
            width:30},
            {field:'hpp',
            title:'HPP',
            align:"right",
            width:30},
            {field:'nilai',
            title:'Nilai',
            width:70,
            align:"right"},
            {field:'lalu',
            title:'Sudah Dibayarkan',
            align:"right",
            width:30,
            hidden:'true'},
            {field:'sp2d',
            title:'SP2D Non UP',
            align:"right",
            width:30,
            hidden:'true'},
            {field:'anggaran',
            title:'Anggaran',
            align:"right",
            width:30,
            hidden:'true'},
            {field:'kd_rek',
            title:'Rekening',
            width:30}
            ]]
        });    
                
    $('#dg2').edatagrid({                       
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"true",
        loadMsg:"Tunggu Sebentar....!!",              
        nowrap:"false",
        onSelect:function(rowIndex,rowData){                    
            cidx = rowIndex;            
        },                 
        columns:[[
            {field:'hapus',
            title:'Hapus',
            width:11,
            align:"center",
            formatter:function(value,rec){                                                                       
                return '<img src="<?php echo base_url(); ?>/assets/images/icon/cross.png" onclick="javascript:hapus_detail();" />';                  
                }                
            },
            {field:'no_bukti',
            title:'No Bukti',           
            hidden:"true",
            width:30},
            {field:'no_sp2d',
            title:'No SP2D',
            width:40,
            hidden:"true"},
            {field:'kd_sub_kegiatan',
            title:'Kegiatan',
            width:50},
            {field:'nm_sub_kegiatan',
            title:'Nama Kegiatan',          
            hidden:"true",
            width:30},
            {field:'kd_rek5',
            title:'Kode Rekening',
            width:25,
            align:'center'},
            {field:'nm_rek5',
            title:'Nama Rekening',
            align:"left",
            width:40},
            {field:'pad',
            title:'PAD',
            align:"right",
            width:30},
            {field:'dak',
            title:'DAK FISIK',
            align:"right",
            width:30},
            {field:'daknf',
            title:'DAK NF',
            align:"right",
            width:30},
            {field:'dau',
            title:'DAU',
            align:"right",
            width:30},
            {field:'dbhp',
            title:'DBHP',
            align:"right",
            width:30},
            {field:'did',
            title:'DID',
            align:"right",
            width:30},
            {field:'hpp',
            title:'HPP',
            align:"right",
            width:30},
            {field:'nilai',
            title:'Rupiah',
            align:"right",
            width:30},
            {field:'lalu',
            title:'Sudah Dibayarkan',
            align:"right",
            width:30,
            hidden:"true"},
            {field:'sp2d',
            title:'SP2D Non UP',
            align:"right",
            width:30,
            hidden:"true"},
            {field:'anggaran',
            title:'Anggaran',
            align:"right",
            width:30,
            hidden:"true"},
            {field:'kd_rek',
            title:'Rekening',
            width:30}
            ]]        
      });
        
        $('#tanggal').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();    
                return y+'-'+m+'-'+d;
           }, onSelect: function(date){
                cek_status_ang();
            }
        });
        
        $('#tgltagih').datebox({  
                required:true,
                formatter :function(date){
                    var y = date.getFullYear();
                    var m = date.getMonth()+1;
                    var d = date.getDate();    
                    return y+'-'+m+'-'+d;
                }
        });
                    
                                          
        $('#giat').combogrid({  
           panelWidth:700,  
           idField:'kd_sub_kegiatan',  
           textField:'kd_sub_kegiatan',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/penagihan/load_trskpd',
           queryParams:({kd:skpd}),             
           columns:[[  
               {field:'kd_sub_kegiatan',title:'Kode Kegiatan',width:140},  
               {field:'nm_sub_kegiatan',title:'Nama Kegiatan',width:700}
           ]],  
           onSelect:function(rowIndex,rowData){
               idxGiat = rowIndex;               
               giat = rowData.kd_sub_kegiatan;
               nm_giat = rowData.nm_sub_kegiatan;
               $("#nmkegiatan").attr("value",rowData.nm_sub_kegiatan);
               var nobukti = document.getElementById('nomor').value;
               var kode = document.getElementById('skpd').value;
               var frek = '';
               kosong2();
               $('#rek').combogrid({url:'<?php echo base_url(); ?>index.php/penagihan/load_rek_penagihan',
                                   queryParams:({no:nobukti,
                                                 giat:giat,
                                                 kd:kode,
                                                 rek:frek})
                                   });
               load_total_spd(giat);
               load_total_trans(giat);
               $("#rek").combogrid('disable');

           }
           
        });
        

        
        
        $('#kontrak').combogrid({  
                panelWidth:200,  
                url: '<?php echo base_url(); ?>/index.php/penagihan/kontrak',  
                    idField:'kontrak',  
                    textField:'kontrak',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'kontrak',title:'Kontrak',width:40} 
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    //$("#kode").attr("value",rowData.kode);
                    $("#kontrak").attr("value",rowData.kontrak);    
                    }   
        });
        
        
        
        $('#rek').combogrid({  
           panelWidth:750,  
           idField:'kd_rek6',  
           textField:'kd_rek6',  
           mode:'remote',                                   
           columns:[[  
               {field:'kd_rek',title:'Rekening Angg.',width:100,align:'center'},  
               {field:'kd_rek6',title:'Rekening LO',width:100,align:'center'},  
               {field:'nm_rek6',title:'Nama Rekening',width:200},
               {field:'lalu',title:'Lalu',width:120,align:'right'},
               {field:'sp2d',title:'SP2D',width:120,align:'right'},
               {field:'anggaran',title:'Anggaran',width:120,align:'right'}
           ]],
           onSelect:function(rowIndex,rowData){
                var anggaran = rowData.anggaran;
                var anggaran_semp = rowData.anggaran_semp;
                var anggaran_ubah = rowData.anggaran_ubah;
                var lalu = rowData.lalu;
                sisa = anggaran-lalu;
                sisa_semp = anggaran_semp-lalu;
                sisa_ubah = anggaran_ubah-lalu;
                $("#rek1").attr("value",rowData.kd_rek);
                $("#nmrek").attr("value",rowData.nm_rek6);
                $('#sisa').attr('value',number_format(sisa,2,'.',','));
                $('#sisa_semp').attr('value',number_format(sisa_semp,2,'.',','));
                $('#sisa_ubah').attr('value',number_format(sisa_ubah,2,'.',','));
                document.getElementById('nil_pad').select();
                total_sisa_spd();
                sisa_sumberdana();
           }
        });                        
    }); 
    
    function load_total_spd(giat){
        var kode = document.getElementById('skpd').value;
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/penagihan/load_total_spd",
            dataType:"json",
            data: ({giat:giat,kode:kode}),
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#tot_spd").attr("value",n['total_spd']);
                });
            }
         });
        });
    }
    function load_total_trans(giat){
        var no_simpan = document.getElementById('no_simpan').value;  
        var kode = document.getElementById('skpd').value;
        
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/penagihan/load_total_trans_tagih",
            dataType:"json",
            data: ({giat:giat,kode:kode,no_simpan:no_simpan}),
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#tot_trans").attr("value",n['total']);
                });
             $("#rek").combogrid('enable');
            }
         });
        });
    }
    
    function sisa_sumberdana(){
        var kode   = document.getElementById('skpd').value;
        var giat   = $('#giat').combogrid('getValue');
        var rek    = document.getElementById('rek1').value;
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/penagihan/load_sisa_sumberdana",
            dataType:"json",
            data: ({giat:giat,kode:kode,rek:rek}),
            success:function(data){ 
                $.each(data, function(i,n){
                    var pad_murni = n['pad_murni'];
                    var dak_murni = n['dak_murni'];
                    var daknf_murni = n['daknf_murni'];
                    var dau_murni = n['dau_murni'];
                    var dbhp_murni = n['dbhp_murni'];
                    var did_murni = n['did_murni'];
                    var hpp_murni = n['hpp_murni'];
                    
                    var pad_semp = n['pad_semp'];
                    var dak_semp = n['dak_semp'];
                    var daknf_semp = n['daknf_semp'];
                    var dau_semp = n['dau_semp'];
                    var dbhp_semp = n['dbhp_semp'];
                    var did_semp = n['did_semp'];
                    var hpp_semp = n['hpp_semp'];
                    
                    var pad_ubah = n['pad_ubah'];
                    var dak_ubah = n['dak_ubah'];
                    var daknf_ubah = n['daknf_ubah'];
                    var dau_ubah = n['dau_ubah'];
                    var dbhp_ubah = n['dbhp_ubah'];
                    var did_ubah = n['did_ubah'];
                    var hpp_ubah = n['hpp_ubah'];
                    
                    var real_pad = n['nil_pad'];
                    var real_dak = n['nil_dak'];
                    var real_daknf = n['nil_daknf'];
                    var real_dau = n['nil_dau'];
                    var real_dbhp = n['nil_dbhp'];
                    var real_did = n['nil_did'];
                    var real_hpp = n['nil_hpp'];
                    
                    var sisa_pad = pad_murni-real_pad;
                    var sisa_pad_semp = pad_semp-real_pad;
                    var sisa_pad_ubah = pad_ubah-real_pad;
                    var sisa_dak = dak_murni-real_dak;
                    var sisa_dak_semp = dak_semp-real_dak;
                    var sisa_dak_ubah = dak_ubah-real_dak;

                    var sisa_daknf = daknf_murni-real_daknf;
                    var sisa_daknf_semp = daknf_semp-real_daknf;
                    var sisa_daknf_ubah = daknf_ubah-real_daknf;

                    var sisa_dau = dau_murni-real_dau;
                    var sisa_dau_semp = dau_semp-real_dau;
                    var sisa_dau_ubah = dau_ubah-real_dau;
                    var sisa_dbhp = dbhp_murni-real_dbhp;
                    var sisa_dbhp_semp = dbhp_semp-real_dbhp;
                    var sisa_dbhp_ubah = dbhp_ubah-real_dbhp;
                    
                    var sisa_did = did_murni-real_did;
                    var sisa_did_semp = did_semp-real_did;
                    var sisa_did_ubah = did_ubah-real_did;

                    var sisa_hpp = hpp_murni-real_hpp;
                    var sisa_hpp_semp = hpp_semp-real_hpp;
                    var sisa_hpp_ubah = hpp_ubah-real_hpp;
                    
                    
                    $("#sisa_pad").attr("value",number_format(sisa_pad,2,'.',','));
                    $("#sisa_pad_semp").attr("value",number_format(sisa_pad_semp,2,'.',','));
                    $("#sisa_pad_ubah").attr("value",number_format(sisa_pad_ubah,2,'.',','));
                    $("#sisa_dak").attr("value",number_format(sisa_dak,2,'.',','));
                    $("#sisa_dak_semp").attr("value",number_format(sisa_dak_semp,2,'.',','));
                    $("#sisa_dak_ubah").attr("value",number_format(sisa_dak_ubah,2,'.',','));

                    $("#sisa_daknf").attr("value",number_format(sisa_daknf,2,'.',','));
                    $("#sisa_daknf_semp").attr("value",number_format(sisa_daknf_semp,2,'.',','));
                    $("#sisa_daknf_ubah").attr("value",number_format(sisa_daknf_ubah,2,'.',','));

                    $("#sisa_dau").attr("value",number_format(sisa_dau,2,'.',','));
                    $("#sisa_dau_semp").attr("value",number_format(sisa_dau_semp,2,'.',','));
                    $("#sisa_dau_ubah").attr("value",number_format(sisa_dau_ubah,2,'.',','));
                    $("#sisa_dbhp").attr("value",number_format(sisa_dbhp,2,'.',','));
                    $("#sisa_dbhp_semp").attr("value",number_format(sisa_dbhp_semp,2,'.',','));
                    $("#sisa_dbhp_ubah").attr("value",number_format(sisa_dbhp_ubah,2,'.',','));

                    $("#sisa_did").attr("value",number_format(sisa_did,2,'.',','));
                    $("#sisa_did_semp").attr("value",number_format(sisa_did_semp,2,'.',','));
                    $("#sisa_did_ubah").attr("value",number_format(sisa_did_ubah,2,'.',','));

                    $("#sisa_hpp").attr("value",number_format(sisa_hpp,2,'.',','));
                    $("#sisa_hpp_semp").attr("value",number_format(sisa_hpp_semp,2,'.',','));
                    $("#sisa_hpp_ubah").attr("value",number_format(sisa_hpp_ubah,2,'.',','));

                });
            }
         });
        });
    }
    
    function hitung_nilai(){
        var a = angka(document.getElementById('nil_pad').value);
        var b = angka(document.getElementById('nil_dak').value);
        var c = angka(document.getElementById('nil_dau').value); 
        var d = angka(document.getElementById('nil_dbhp').value); 
        var f = angka(document.getElementById('nil_daknf').value);
        var g = angka(document.getElementById('nil_did').value);
        var h = angka(document.getElementById('nil_hpp').value);
        var e = a+b+c+d+f+g+h;
        $("#nilai").attr("value",number_format(e,2,'.',','));
           
       }
    function total_sisa_spd(){ 
        var tot_spd   = angka(document.getElementById('tot_spd').value);  
       var tot_trans = angka(document.getElementById('tot_trans').value);  
           totsisa = tot_spd-tot_trans;
        
       $('#sisa_spd').attr('value',number_format(totsisa,2,'.',','));

    }
    
    
    function cek_sumber(){
      $.ajax({
                url:'<?php echo base_url(); ?>index.php/penagihan/cek_status_sumber',
                type: "POST",
                dataType:"json",                         
                success:function(data){
                    status_sumber = data.st_sumber;
                    if(status_sumber=='1'){
                        document.getElementById("nil_dak").readOnly = false;
                        
                        document.getElementById("nil_dak").style.backgroundColor = "white"; 
                    }else{
                        document.getElementById("nil_dak").readOnly = true;
                        document.getElementById("nil_dak").style.backgroundColor = "grey";
                    }
                }                                     
            });
        
      
    }
    
    function get_skpd()
        {
        
            $.ajax({
                url:'<?php echo base_url(); ?>index.php/penagihan/config_skpd',
                type: "POST",
                dataType:"json",                         
                success:function(data){
                                         if (data.kd_skpd=='1.02.01.01'){
                                        $('#rek_daknf').removeAttr('disabled');
                                      }else{
                                        $('#rek_daknf').attr('disabled','true');
                                      }
                                        $("#skpd").attr("value",data.kd_skpd);
                                        $("#nmskpd").attr("value",data.nm_skpd);
                                        skpd = data.kd_skpd;
                                        kegia(); 
                                        seting_tombol(skpd);
                                      }                                     
            });  
        } 
    
    function seting_tombol(skpd){
        
      /*if(skpd=='1.20.04.01'){
            $('#tambah').linkbutton('enable');
            $('#save').linkbutton('enable');
            $('#del').linkbutton('enable');
        }else{
            $('#tambah').linkbutton('disable');
            $('#save').linkbutton('disable');
            $('#del').linkbutton('disable');
        }*/  
        
    
              /*$('#tambah').linkbutton('disable');
            $('#save').linkbutton('disable');
            $('#del').linkbutton('disable'); 
         */
    }
    
    function get_tahun() {
            $.ajax({
                url:'<?php echo base_url(); ?>index.php/penagihan/config_tahun',
                type: "POST",
                dataType:"json",                         
                success:function(data){
                    tahun_anggaran = data;
                    }                                     
            });
             
        }

    function cek_status_ang(){
        var tgl_cek = $('#tanggal').datebox('getValue');      
            $.ajax({
                url:'<?php echo base_url(); ?>index.php/tukd/cek_status_ang',
                data: ({tgl_cek:tgl_cek}),
                type: "POST",
                dataType:"json",                         
                success:function(data){
                $("#status_ang").attr("value",data.status_ang);
            }  
            });
        }
    
    function kegia(){
      $('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/penagihan/load_trskpd_xxxxx',queryParams:({kd:skpd,jenis:'5'})});  
    }
               
    
    function hapus_detail(){
        var rows = $('#dg2').edatagrid('getSelected');
        cgiat    = rows.kd_sub_kegiatan;
        crek     = rows.kd_rek5;
        cnil     = rows.nilai;
        var idx = $('#dg2').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, Kegiatan : '+cgiat+' Rekening : '+crek+' Nilai : '+cnil);
        if (tny==true){
            $('#dg2').edatagrid('deleteRow',idx);
            $('#dg1').edatagrid('deleteRow',idx);
            total = angka(document.getElementById('total1').value) - angka(cnil);            
            $('#total1').attr('value',number_format(total,2,'.',','));    
            $('#total').attr('value',number_format(total,2,'.',','));
            kosong2();
            
        } 
    }
    
    function load_tot_tagih(){           
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({no_tagih:nomor}),
            url:"<?php echo base_url(); ?>index.php/tukd/load_tot_tagih",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#total").attr("value",n['total']);
                });
            }
         });
        });
    }
    
    function load_detail(){        
        var kk    = document.getElementById("nomor").value;
        var ctgl  = $('#tanggal').datebox('getValue');
        var cskpd = document.getElementById("skpd").value;            
        
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/penagihan/load_dtagih',
                data     : ({no:kk}),
                dataType : "json",
                success  : function(data){                                          
                    $('#dg1').datagrid('loadData',[]);     
                    $('#dg1').edatagrid('reload');                                                                                  
                    $.each(data,function(i,n){                                    
                    no        = n['no_bukti'];
                    nosp2d    = n['no_sp2d'];                                                                    
                    giat      = n['kd_sub_kegiatan'];
                    nmgiat    = n['nm_sub_kegiatan'];
                    rek5      = n['kd_rek5'];
                    rek       = n['kd_rek'];
                    nmrek5    = n['nm_rek5'];
                    nil       = number_format(n['nilai'],2,'.',',');
                    nil_pad   = number_format(n['nil_pad'],2,'.',',');
                    nil_dak   = number_format(n['nil_dak'],2,'.',',');
                    nil_daknf = number_format(n['nil_daknf'],2,'.',',');
                    nil_dau   = number_format(n['nil_dau'],2,'.',',');
                    nil_dbhp  = number_format(n['nil_dbhp'],2,'.',',');
                    nil_did  = number_format(n['nil_did'],2,'.',',');
                    nil_hpp  = number_format(n['nil_hpp'],2,'.',',');
                    clalu     = number_format(n['lalu'],2,'.',',');
                    csp2d     = number_format(n['sp2d'],2,'.',',');
                    canggaran = number_format(n['anggaran'],2,'.',',');
                    $('#dg1').edatagrid('appendRow',{no_bukti:no,no_sp2d:nosp2d,kd_sub_kegiatan:giat,nm_sub_kegiatan:nmgiat,kd_rek5:rek5,nm_rek5:nmrek5,nilai:nil,lalu:clalu,sp2d:csp2d,anggaran:canggaran,kd_rek:rek,pad:nil_pad,dak:nil_dak,daknf:nil_daknf,dau:nil_dau,dbhp:nil_dbhp,did:nil_did,hpp:nil_hpp});                                                                                                                                                                                                                                                                                                                                                                                             
                    });                                                                           
                }
            });
           });                
           set_grid();                                                  
    }
    
    
    
    function set_grid(){
        $('#dg1').edatagrid({                                                                   
            columns:[[
                {field:'no_bukti',
                title:'No Bukti',               
                hidden:"true"},
                {field:'no_sp2d',
                title:'No SP2D',                
                hidden:"true"},
                {field:'kd_sub_kegiatan',
                title:'Kegiatan',
                width:50},
                {field:'nm_sub_kegiatan',
                title:'Nama Kegiatan',              
                hidden:"true"},
                {field:'kd_rek5',
                title:'Kode Rekening',
                width:30},
                {field:'nm_rek5',
                title:'Nama Rekening',
                width:100,
                align:"left"},
                {field:'pad',
                title:'PAD',
                align:"right",
                width:30},
                {field:'dak',
                title:'DAK FISIK',
                align:"right",
                width:30},
                {field:'daknf',
                title:'DAK NF',
                align:"right",
                width:30},
                {field:'dau',
                title:'DAU',
                align:"right",
                width:30},
                {field:'dbhp',
                title:'DBHP',
                align:"right",
                width:30},
                {field:'did',
                title:'DID',
                align:"right",
                width:30},
                {field:'hpp',
                title:'HPP',
                align:"right",
                width:30},
                {field:'nilai',
                title:'Nilai',
                width:70,
                align:"right"},
                {field:'lalu',
                title:'Sudah Dibayarkan',
                align:"right",
                width:30,
                hidden:'true'},
                {field:'sp2d',
                title:'SP2D Non UP',
                align:"right",
                width:30,
                hidden:'true'},
                {field:'anggaran',
                title:'Anggaran',
                align:"right",
                width:30,
                hidden:'true'},
                {field:'kd_rek',
                title:'Rekening',
                width:30,
                hidden:'true'}
            ]]
        });                 
    }
    
    
    
    function load_detail2(){        
       $('#dg1').datagrid('selectAll');
       var rows = $('#dg1').datagrid('getSelections');             
       if (rows.length==0){
            set_grid2();
            exit();
       }                     
        for(var p=0;p<rows.length;p++){
            no      = rows[p].no_bukti;
            nosp2d  = rows[p].no_sp2d;
            giat    = rows[p].kd_sub_kegiatan;
            nmgiat  = rows[p].nm_sub_kegiatan;
            rek5    = rows[p].kd_rek5;
            rek     = rows[p].kd_rek;
            nmrek5  = rows[p].nm_rek5;
            nil     = rows[p].nilai;
            pad     = rows[p].pad;
            dak     = rows[p].dak;
            daknf     = rows[p].daknf;
            dau     = rows[p].dau;
            dbhp     = rows[p].dbhp;
            did     = rows[p].did;
            HPP     = rows[p].hpp;
            lal     = rows[p].lalu;
            csp2d   = rows[p].sp2d;
            canggaran   = rows[p].anggaran;                                                                                                                              
            $('#dg2').edatagrid('appendRow',{no_bukti:no,no_sp2d:nosp2d,kd_sub_kegiatan:giat,nm_sub_kegiatan:nmgiat,kd_rek5:rek5,nm_rek5:nmrek5,nilai:nil,lalu:lal,sp2d:csp2d,anggaran:canggaran,kd_rek:rek,pad:pad,dak:dak,daknf:daknf,dau:dau,dbhp:dbhp,did:did,hpp:hpp});            
        }
        $('#dg1').edatagrid('unselectAll');
    } 
    
    
    
    function set_grid2(){
        $('#dg2').edatagrid({      
         columns:[[
            {field:'hapus',
            title:'Hapus',
            width:11,
            align:"center",
            formatter:function(value,rec){                                                                       
                return '<img src="<?php echo base_url(); ?>/assets/images/icon/cross.png" onclick="javascript:hapus_detail();" />';                  
                }                
            },
            {field:'no_bukti',
            title:'No Bukti',           
            hidden:"true",
            width:30},
            {field:'no_sp2d',
            title:'No SP2D',
            hidden:"true",
            width:40},
            {field:'kd_sub_kegiatan',
            title:'Kegiatan',
            width:50},
            {field:'nm_sub_kegiatan',
            title:'Nama Kegiatan',          
            hidden:"true",
            width:30},
            {field:'kd_rek5',
            title:'Kode Rekening',
            width:25,
            align:'center'},
            {field:'nm_rek5',
            title:'Nama Rekening',
            align:"left",
            width:40},
            {field:'pad',
            title:'PAD',
            align:"right",
            width:30},
            {field:'dak',
            title:'DAK FISIK',
            align:"right",
            width:30},
            {field:'daknf',
            title:'DAK NF',
            align:"right",
            width:30},
            {field:'dau',
            title:'DAU',
            align:"right",
            width:30},
            {field:'dbhp',
            title:'DBHP',
            align:"right",
            width:30},
            {field:'did',
            title:'DID',
            align:"right",
            width:30},
            {field:'hpp',
            title:'HPP',
            align:"right",
            width:30},
            {field:'nilai',
            title:'Rupiah',
            align:"right",
            width:30},
            {field:'lalu',
            title:'Sudah Dibayarkan',
            align:"right",
            hidden:"true",
            width:30},
            {field:'sp2d',
            title:'SP2D Non UP',
            align:"right",
            hidden:"true",
            width:30},
            {field:'anggaran',
            title:'Anggaran',
            align:"right",
            width:30},
            {field:'kd_rek',
            title:'Rekening',
            width:30}
            ]]     
        });
    }
    
    function section1(){
         $(document).ready(function(){    
             $('#section1').click();                                               
         });         
         $('#dg').edatagrid('reload');
         set_grid();
    }
     
     
    function section2(){
         $(document).ready(function(){                
             $('#section2').click(); 
             document.getElementById("nomor").focus();                                              
         });                 
         set_grid();
    }
       
     
    function get(nomor,tgl,kode,nama,ket,jns,tot,notagih,tgltagih,ststagih,sts,jenis,kontrak){
        $("#nomor").attr("value",nomor);
        $("#nomor_hide").attr("value",nomor);
        $("#no_simpan").attr("value",nomor);
        $("#tanggal").datebox("setValue",tgl);
        $("#keterangan").attr("value",ket);        
        $("#beban").attr("value",jns);
        //$("#total").attr("value",number_format(tot,2,'.',','));
        $("#notagih").attr("value",notagih);        
        $("#tgltagih").datebox("setValue",tgltagih);    
        $("#status").attr("checked",false);
        $("#status_byr").attr("value",sts);
        $("#jns").attr("Value",jenis);
        $("#kontrak").combogrid("setValue",kontrak);
        if (ststagih==1){            
            $("#status").attr("checked",true);
            $("#tagih").show();
        } else {
            $("#status").attr("checked",false);
            $("#tagih").hide();
        }    
        
        tombol(ststagih);
    }
    
   
        function tombol(st){  
            if (st==1){
            $('#save').linkbutton('disable');
            $('#del').linkbutton('disable');
            $('#edit').linkbutton('disable');
             } else {
             $('#save').linkbutton('enable');
             $('#del').linkbutton('enable');
             }
            }
   
    function kosong(){
        cdate = '<?php echo date("Y-m-d"); ?>';        
        $("#nomor").attr("value",'');
        $("#nomor_hide").attr("value",'');
        $("#no_simpan").attr("value",'');
        $("#tanggal").datebox("setValue",'');
        $("#keterangan").attr("value",'');
        $("#kontrak").combogrid("setValue",'');
        $("#total").attr("value",'0');         
        document.getElementById("nomor").focus();  
        lcstatus = 'tambah';
        var cskpd        = document.getElementById('skpd').value;
            $('#save').linkbutton('enable');
        $('#del').linkbutton('enable');

    }
    

    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/tukd/load_penagihan',
        queryParams:({cari:kriteria})
        });        
     });
    }    
        

    function append_save(){
        cek_sumber();
        var no      = document.getElementById('nomor').value;
        var giat    = $('#giat').combogrid('getValue');
        var nmgiat  = document.getElementById('nmkegiatan').value;                
        var nosp2d  = '';
        var rek5    = document.getElementById('rek1').value;
        var rek     = $('#rek').combogrid('getValue');
        var nmrek   = document.getElementById('nmrek').value;
        var crek    = $('#rek').combogrid('grid');  
        var grek    = crek.datagrid('getSelected'); 
        var canggaran = number_format(grek.anggaran,2,'.',',');
        var csp2d   = 0;
        var clalu   = 0;
        var sisa    = angka(document.getElementById('sisa').value);                
        var sisa_semp = angka(document.getElementById('sisa_semp').value);                
        var sisa_ubah = angka(document.getElementById('sisa_ubah').value); 
        var sisa_pad  = angka(document.getElementById('sisa_pad').value);                
        var sisa_pad_semp = angka(document.getElementById('sisa_pad_semp').value);                
        var sisa_pad_ubah = angka(document.getElementById('sisa_pad_ubah').value); 
        var sisa_dau  = angka(document.getElementById('sisa_dau').value);                
        var sisa_dau_semp = angka(document.getElementById('sisa_dau_semp').value);                
        var sisa_dau_ubah = angka(document.getElementById('sisa_dau_ubah').value); 
        var sisa_dak  = angka(document.getElementById('sisa_dak').value);                
        var sisa_dak_semp = angka(document.getElementById('sisa_dak_semp').value);                
        var sisa_dak_ubah = angka(document.getElementById('sisa_dak_ubah').value);
        var sisa_daknf  = angka(document.getElementById('sisa_daknf').value);                
        var sisa_daknf_semp = angka(document.getElementById('sisa_daknf_semp').value);                
        var sisa_daknf_ubah = angka(document.getElementById('sisa_daknf_ubah').value);
        var sisa_dbhp  = angka(document.getElementById('sisa_dbhp').value);                
        var sisa_dbhp_semp = angka(document.getElementById('sisa_dbhp_semp').value);                
        var sisa_dbhp_ubah = angka(document.getElementById('sisa_dbhp_ubah').value);        
        var sisa_did  = angka(document.getElementById('sisa_did').value);                
        var sisa_did_semp = angka(document.getElementById('sisa_did_semp').value);                
        var sisa_did_ubah = angka(document.getElementById('sisa_did_ubah').value);
        var sisa_hpp  = angka(document.getElementById('sisa_hpp').value);                
        var sisa_hpp_semp = angka(document.getElementById('sisa_hpp_semp').value);                
        var sisa_hpp_ubah = angka(document.getElementById('sisa_hpp_ubah').value);      

        var nil_pad   = angka(document.getElementById('nil_pad').value);        
        var nil_dak   = angka(document.getElementById('nil_dak').value);        
        var nil_daknf   = angka(document.getElementById('nil_daknf').value);        
        var nil_dau   = angka(document.getElementById('nil_dau').value);        
        var nil_dbhp  = angka(document.getElementById('nil_dbhp').value);        
        var nil_did  = angka(document.getElementById('nil_did').value);        
        var nil_hpp  = angka(document.getElementById('nil_hpp').value);        
        var nil         = angka(document.getElementById('nilai').value);        
        var sisa_spd    = angka(document.getElementById('sisa_spd').value);        
        var rek_pad     = document.getElementById('nil_pad').value;        
        var rek_dak     = document.getElementById('nil_dak').value;        
        var rek_daknf     = document.getElementById('nil_daknf').value;        
        var rek_dau     = document.getElementById('nil_dau').value;        
        var rek_dbhp    = document.getElementById('nil_dbhp').value;        
        var rek_did    = document.getElementById('nil_did').value;        
        var rek_hpp    = document.getElementById('nil_hpp').value;        
        var nil_rek     = document.getElementById('nilai').value;        
        var status_ang  = document.getElementById('status_ang').value ;
        var total   = angka(document.getElementById('total1').value) + nil;

            if (status_ang==''){
                swal("Error", "Pilih Tanggal Dahulu", "error");
                 exit();
            }
            if ( nil == 0 ){
                swal("Error", "Nilai Nol.....!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( nil > sisa_spd){
                swal("Error", "Nilai Melebihi Sisa SPD...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( total > sisa_spd){
                swal("Error", "Nilai Melebihi Sisa SPD...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            
            //PAD
             if ( (status_ang=='Perubahan')&&(nil_pad > sisa_pad_ubah)){
                swal("Error", "Nilai PAD Melebihi Sisa Anggaran Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nil_pad > sisa_pad_ubah)){
                swal("Error", "Nilai PAD Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nil_pad > sisa_pad_semp)){
                swal("Error", "Nilai PAD Melebihi Sisa Anggaran Penyempurnaan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil_pad > sisa_pad_ubah)){
                swal("Error", "Nilai PAD Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil_pad > sisa_pad_semp)){
                swal("Error", "Nilai PAD Melebihi Sisa Anggaran Rencana Penyempurnaan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil_pad > sisa_pad)){
                swal("Error", "Nilai PAD Melebihi Sisa Anggaran Penyusunan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            
            
            //DAK FISIK
            
            if (nil_dak!=0){
                if(status_sumber!='1'){
                    swal("Error", "DAK FISIK Telah Terkunci...!!!", "error");
                 exit();
                }
            }
            
             if ( (status_ang=='Perubahan')&&(nil_dak > sisa_dak_ubah)){
                swal("Error", "Nilai DAK FISIK Melebihi Sisa Anggaran Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nil_dak > sisa_dak_ubah)){
                swal("Error", "Nilai DAK FISIK Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nil_dak > sisa_dak_semp)){
                swal("Error", "Nilai DAK FISIK Melebihi Sisa Anggaran Penyempurnaan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil_dak > sisa_dak_ubah)){
                swal("Error", "Nilai DAK FISIK Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil_dak > sisa_dak_semp)){
                swal("Error", "Nilai DAK FISIK Melebihi Sisa Anggaran Rencana Penyempurnaan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil_dak > sisa_dak)){
                swal("Error", "Nilai DAK FISIK Melebihi Sisa Anggaran Penyusunan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }


            //DAK NON FISIK
             if ( (status_ang=='Perubahan')&&(nil_daknf > sisa_daknf_ubah)){
                swal("Error", "Nilai DAK NON FISIK Melebihi Sisa Anggaran Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nil_daknf > sisa_daknf_ubah)){
                swal("Error", "Nilai DAK NON FISIK Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nil_daknf > sisa_daknf_semp)){
                swal("Error", "Nilai DAK NON FISIK Melebihi Sisa Anggaran Penyempurnaan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil_daknf > sisa_daknf_ubah)){
                swal("Error", "Nilai DAK NON FISIK Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil_daknf > sisa_daknf_semp)){
                swal("Error", "Nilai DAK NON FISIK Melebihi Sisa Anggaran Rencana Penyempurnaan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil_daknf > sisa_daknf)){
                swal("Error", "Nilai DAK NON FISIK Melebihi Sisa Anggaran Penyusunan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }

            
            //DAU
             if ( (status_ang=='Perubahan')&&(nil_dau > sisa_dau_ubah)){
                swal("Error", "Nilai DAU Melebihi Sisa Anggaran Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nil_dau > sisa_dau_ubah)){
                swal("Error", "Nilai DAU Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nil_dau > sisa_dau_semp)){
                swal("Error", "Nilai DAU Melebihi Sisa Anggaran Penyempurnaan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil_dau > sisa_dau_ubah)){
                swal("Error", "Nilai DAU Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil_dau > sisa_dau_semp)){
                swal("Error", "Nilai DAU Melebihi Sisa Anggaran Rencana Penyempurnaan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil_dau > sisa_dau)){
                swal("Error", "Nilai DAU Melebihi Sisa Anggaran Penyusunan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            
            //DBHP
             if ( (status_ang=='Perubahan')&&(nil_dbhp > sisa_dbhp_ubah)){
                swal("Error", "Nilai DBHP Melebihi Sisa Anggaran Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nil_dbhp > sisa_dbhp_ubah)){
                swal("Error", "Nilai DBHP Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nil_dbhp > sisa_dbhp_semp)){
                swal("Error", "Nilai DBHP Melebihi Sisa Anggaran Penyempurnaan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil_dbhp > sisa_dbhp_ubah)){
                swal("Error", "Nilai DBHP Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil_dbhp > sisa_dbhp_semp)){
                swal("Error", "Nilai DBHP Melebihi Sisa Anggaran Rencana Penyempurnaan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil_dbhp > sisa_dbhp)){
                swal("Error", "Nilai DBHP Melebihi Sisa Anggaran Penyusunan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            

            //DID
             if ( (status_ang=='Perubahan')&&(nil_did > sisa_did_ubah)){
                swal("Error", "Nilai DID Melebihi Sisa Anggaran Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nil_did > sisa_did_ubah)){
                swal("Error", "Nilai DID Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nil_did > sisa_did_semp)){
                swal("Error", "Nilai DID Melebihi Sisa Anggaran Penyempurnaan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil_did > sisa_did_ubah)){
                swal("Error", "Nilai DID Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil_did > sisa_did_semp)){
                swal("Error", "Nilai DID Melebihi Sisa Anggaran Rencana Penyempurnaan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil_did > sisa_did)){
                swal("Error", "Nilai DID Melebihi Sisa Anggaran Penyusunan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }

            //HPP
             if ( (status_ang=='Perubahan')&&(nil_hpp > sisa_hpp_ubah)){
                swal("Error", "Nilai HPP Melebihi Sisa Anggaran Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nil_hpp > sisa_hpp_ubah)){
                swal("Error", "Nilai HPP Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nil_hpp > sisa_hpp_semp)){
                swal("Error", "Nilai HPP Melebihi Sisa Anggaran Penyempurnaan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil_hpp > sisa_hpp_ubah)){
                swal("Error", "Nilai HPP Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil_hpp > sisa_hpp_semp)){
                swal("Error", "Nilai HPP Melebihi Sisa Anggaran Rencana Penyempurnaan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil_hpp > sisa_hpp)){
                swal("Error", "Nilai HPP Melebihi Sisa Anggaran Penyusunan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            
            //TOTAL
            if ( (status_ang=='Perubahan')&&(nil > sisa_ubah)){
                swal("Error", "Nilai Melebihi Sisa Anggaran Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nil > sisa_ubah)){
                swal("Error", "Nilai Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(nil > sisa_semp)){
                swal("Error", "Nilai Melebihi Sisa Anggaran Penyempurnaan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil > sisa_ubah)){
                swal("Error", "Nilai Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil > sisa_semp)){
                swal("Error", "Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(nil > sisa)){
                swal("Error", "Nilai Melebihi Sisa Anggaran Penyusunan...!!!, Cek Lagi...!!!", "error");
                 exit();
            }
            if (giat==''){
                swal("Error", "Pilih Kegiatan Dahulu", "error");
                 exit();
            }
            if (nmgiat==''){
                swal("Error", "Pilih Kegiatan Dahulu", "error");
                 exit();
            }
            var len = giat.length;
            if (len !=21){
                swal("Error", "Format Kegiatan Salah", "error");
                exit();
            }
            
            
            if (nmrek==''){
                swal("Error", "Pilih Rekening Dahulu", "error");
                 exit();
            }
            
                $('#dg1').edatagrid('appendRow',{no_bukti:no,
                                                 no_sp2d:nosp2d,
                                                 kd_sub_kegiatan:giat,
                                                 nm_sub_kegiatan:nmgiat,
                                                 kd_rek5:rek,
                                                 nm_rek5:nmrek,
                                                 nilai:nil_rek,
                                                 lalu:clalu,
                                                 sp2d:csp2d,
                                                 anggaran:canggaran,
                                                 kd_rek:rek5,
                                                 pad:rek_pad,
                                                 dak:rek_dak,
                                                 daknf:rek_daknf,
                                                 dau:rek_dau,
                                                 dbhp:rek_dbhp,
                                                 did:rek_did,
                                                 hpp:rek_hpp});
                $('#dg2').edatagrid('appendRow',{no_bukti:no,
                                                 no_sp2d:nosp2d,
                                                 kd_sub_kegiatan:giat,
                                                 nm_sub_kegiatan:nmgiat,
                                                 kd_rek5:rek,
                                                 nm_rek5:nmrek,
                                                 nilai:nil_rek,
                                                 lalu:clalu,
                                                 sp2d:csp2d,
                                                 anggaran:canggaran,
                                                 kd_rek:rek5,
                                                 pad:rek_pad,
                                                 dak:rek_dak,
                                                 daknf:rek_daknf,
                                                 dau:rek_dau,
                                                 dbhp:rek_dbhp,
                                                 did:rek_did,
                                                 hpp:rek_hpp});                                                 
                kosong2();
                $('#total1').attr('value',number_format(total,2,'.',','));
                $('#total').attr('value',number_format(total,2,'.',','));
        /*tot         = sisa - angka(nil);

        if (tot >= 0){                                    
            if (giat != '' && nil != 0 && canggaran != 0) {
                $('#dg1').edatagrid('appendRow',{no_bukti:no,
                                                 no_sp2d:nosp2d,
                                                 kd_sub_kegiatan:giat,
                                                 nm_sub_kegiatan:nmgiat,
                                                 kd_rek5:rek,
                                                 nm_rek5:nmrek,
                                                 nilai:nil,
                                                 lalu:clalu,
                                                 sp2d:csp2d,
                                                 anggaran:canggaran,
                                                 kd_rek:rek5});
                $('#dg2').edatagrid('appendRow',{no_bukti:no,
                                                 no_sp2d:nosp2d,
                                                 kd_sub_kegiatan:giat,
                                                 nm_sub_kegiatan:nmgiat,
                                                 kd_rek5:rek,
                                                 nm_rek5:nmrek,
                                                 nilai:nil,
                                                 lalu:clalu,
                                                 sp2d:csp2d,
                                                 anggaran:canggaran,
                                                 kd_rek:rek5});                                                 
                kosong2();
                total = angka(document.getElementById('total1').value) + angka(nil);
                $('#total1').attr('value',number_format(total,2,'.',','));
                $('#total').attr('value',number_format(total,2,'.',','));
            } else {
                alert('Kode Kegiatan,Nilai dan Anggaran tidak boleh kosong');
                exit();
            }
        } else {
            alert('Nilai Melebihi Sisa');
            exit();                
        }*/     
    }     
    
    function tambah(){
        var nor = document.getElementById('nomor').value;
        var tot = document.getElementById('total').value;
        var kd  = document.getElementById('skpd').value;
        var kontrak  = $('#kontrak').combogrid("getValue");
        $('#dg2').edatagrid('reload');
        $('#total1').attr('value',tot);
        $('#giat').combogrid('setValue','');
        $('#rek').combogrid('setValue','');
        var tgl = $('#tanggal').datebox('getValue');
        if (kd != '' && tgl != '' && nor !='' &&kontrak !=''){            
            $("#dialog-modal").dialog('open'); 
            load_detail2();           
        } else {
            swal("Error", "Harap Isi Kode, Tanggal, Nomor Penagihan & Nomor Kontrak", "error");
        }
    }
    
    function kosong2(){        
        $('#giat').combogrid('setValue','');
        $('#sp2d').combogrid('setValue','');
        $('#rek').combogrid('setValue','');
        $('#sisasp2d').attr('value','0');
        $('#sisa').attr('value','0');
        $('#sisa_semp').attr('value','0');
        $('#sisa_ubah').attr('value','0');
        $('#sisa_pad').attr('value','0');
        $('#sisa_dak').attr('value','0');
        $('#sisa_daknf').attr('value','0');
        $('#sisa_dau').attr('value','0');
        $('#sisa_dbhp').attr('value','0');
        $('#sisa_did').attr('value','0');
        $('#sisa_hpp').attr('value','0');

        $('#sisa_pad_semp').attr('value','0');
        $('#sisa_dak_semp').attr('value','0');
        $('#sisa_daknf_semp').attr('value','0');
        $('#sisa_dau_semp').attr('value','0');
        $('#sisa_dbhp_semp').attr('value','0');
        $('#sisa_did_semp').attr('value','0');
        $('#sisa_hpp_semp').attr('value','0');
        
        $('#sisa_pad_ubah').attr('value','0');
        $('#sisa_dak_ubah').attr('value','0');
        $('#sisa_daknf_ubah').attr('value','0');
        $('#sisa_dau_ubah').attr('value','0');
        $('#sisa_dbhp_ubah').attr('value','0');
        $('#sisa_did_ubah').attr('value','0');
        $('#sisa_hpp_ubah').attr('value','0');
        
        $('#nil_pad').attr('value','0');
        $('#nil_dak').attr('value','0');
        $('#nil_daknf').attr('value','0');
        $('#nil_dau').attr('value','0');
        $('#nil_dbhp').attr('value','0');
        $('#nil_did').attr('value','0');
        $('#nil_hpp').attr('value','0');
        $('#nilai').attr('value','0');
        $('#rek1').attr('value','');
        $('#nmgiat').attr('value','');        
        $('#sisa_spd').attr('value','');        
    }
    
    function keluar(){
        $("#dialog-modal").dialog('close');
        $('#dg2').edatagrid('reload');
        kosong2();                        
    }   
     
    function hapus_giat(){
         tot3 = 0;
         var tot = angka(document.getElementById('total').value);
         tot3 = tot - nilx;
         $('#total').attr('value',number_format(tot3,2,'.',','));        
         $('#dg1').datagrid('deleteRow',idx);              
    }
    
    
    function hapus(){
        var cnomor = document.getElementById('nomor_hide').value;
        var urll = '<?php echo base_url(); ?>index.php/tukd/hapus_penagihan';
        var tny = confirm('Yakin Ingin Menghapus Data, Nomor Penagihan : '+cnomor);        
        if (tny==true){
        $(document).ready(function(){
        $.ajax({url:urll,
                 dataType:'json',
                 type: "POST",    
                 data:({no:cnomor}),
                 success:function(data){
                        status = data.pesan;
                        if (status=='1'){
                            swal("Berhasil", "Data Berhasil Terhapus", "success");
                        } else {
                            swal("Error", "Gagal Hapus", "error");
                        }        
                 }
                 
                });           
        });
        }     
    }
    
    function simpan_transout2() {
        var cno          = (document.getElementById('nomor').value).split(" ").join("");
        var cno_hide     = document.getElementById('nomor_hide').value;
        var cjenis_bayar = document.getElementById('status_byr').value;
        var ctgl         = $('#tanggal').datebox('getValue');
        var cskpd        = document.getElementById('skpd').value;
        var cnmskpd      = document.getElementById('nmskpd').value;
        var cket         = document.getElementById('keterangan').value;
        var jns          = document.getElementById('jns').value;
        var kontrak      = $('#kontrak').combogrid("getValue");
        var status_ang   = document.getElementById('status_ang').value ;        
        var cjenis   = '6';
        var cstatus  = '';
        var csql     = '';
        
        var tahun_input = ctgl.substring(0, 4);
        if (tahun_input != tahun_anggaran){
            /*swal("Error", "Tahun tidak sama dengan tahun Anggaran", "error");
            exit();*/
        }
        if (status_ang==''){
            swal("Error", "Pilih Tanggal Dahulu", "error");
                 exit();
            }
        if (cstatus==false){
            cstatus=0;
        }else{
            cstatus=1;
        }
        
        var ctagih    = '';
        var ctgltagih = '2014-12-1';
        var ctotal    = angka(document.getElementById('total').value);        
        
        if ( cno=='' ){
            swal("Error", "Nomor Bukti Tidak Boleh Kosong", "error");
            exit();
        } 
        if ( ctgl=='' ){
            swal("Error", "Tanggal Bukti Tidak Boleh Kosong", "error");
            exit();
        }
        if ( cskpd=='' ){
            swal("Error", "Kode SKPD Tidak Boleh Kosong", "error");
            exit();
        }
        if ( cnmskpd=='' ){
            swal("Error", "Nama SKPD Tidak Boleh Kosong", "error");
            exit();
        }
        if ( kontrak=='' ){
            swal("Error", "Kontrak Tidak Boleh Kosong", "error");
            exit();
        }
        if ( cket=='' ){
            swal("Error", "Keterangan Tidak boleh kosong", "error");
            exit();
        }
        var lenket = cket.length;
        if ( lenket>1000 ){
            swal("Error", "Keterangan Tidak boleh lebih dari 1000 karakter", "error");
            exit();
        }
        
        $(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'trhtagih',field:'no_bukti'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
                        if(status_cek==1 && cno!=cno_hide){
                        swal("Error", "Nomor Telah Dipakai!", "error");
                        exit();
                        } 
                        if(status_cek==0 || cno==cno_hide){
                        alert("Nomor Bisa dipakai");
        //--------
         lcquery    = " UPDATE trhtagih  SET username='', tgl_update='', status='"+cjenis_bayar+"', jenis='"+jns+"' where no_bukti='"+cno_hide+"' AND kd_skpd='"+cskpd+"' "; 
            
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/tukd/update_penagihan_header_ar',
                data     : ({st_query:lcquery,tabel:'trhtagih',cid:'no_bukti',lcid:cno,lcid_h:cno_hide,status : cjenis_bayar}),
                dataType : "json",
                success  : function(data){
                           status=data ;
                        
                    }
            });
            });
        //----------
            }
            }
        });
     });
    
    }
        
     
    function simpan_transout() {
        var cno          = (document.getElementById('nomor').value).split(" ").join("");
        var cno_hide     = document.getElementById('nomor_hide').value;
        var cjenis_bayar = document.getElementById('status_byr').value;
        var ctgl         = $('#tanggal').datebox('getValue');
        var cskpd        = document.getElementById('skpd').value;
        var cnmskpd      = document.getElementById('nmskpd').value;
        var cket         = document.getElementById('keterangan').value;
        var jns          = document.getElementById('jns').value;
        var kontrak      = $('#kontrak').combogrid("getValue");
        var status_ang   = document.getElementById('status_ang').value ;        
        var cjenis   = '6';
        var cstatus  = '';
        var csql     = '';
        var kolom ='';
        var tahun_input = ctgl.substring(0, 4);
        if (tahun_input != tahun_anggaran){
            /*swal("Error", "Tahun tidak sama dengan tahun Anggaran", "error");
            exit();*/
        }
        if (status_ang==''){
            swal("Error", "Pilih Tanggal Dahulu", "error");
                 exit();
            }
        if (cstatus==false){
            cstatus=0;
        }else{
            cstatus=1;
        }
        
        var ctagih    = '';
        var ctgltagih = '2014-12-1';
        var ctotal    = angka(document.getElementById('total').value);        
        
        if ( ctotal==0 ){
            swal("Error", "Rekening tidak boleh Kosong", "error");
            exit();
        } 
       if ( cno=='' ){
            swal("Error", "Nomor Bukti Tidak Boleh Kosong", "error");
            exit();
        } 
        if ( ctgl=='' ){
            swal("Error", "Tanggal Bukti Tidak Boleh Kosong", "error");
            exit();
        }
       if ( cskpd=='' ){
            swal("Error", "Kode SKPD Tidak Boleh Kosong", "error");
            exit();
        }
        if ( cnmskpd=='' ){
            swal("Error", "Nama SKPD Tidak Boleh Kosong", "error");
            exit();
        }
        if ( kontrak=='' ){
            swal("Error", "Kontrak Tidak Boleh Kosong", "error");
            exit();
        }
        if ( cket=='' ){
            swal("Error", "Keterangan Tidak boleh kosong", "error");
            exit();
        }
        var lenket = cket.length;
        if ( lenket>1000 ){
            swal("Error", "Keterangan Tidak boleh lebih dari 1000 karakter", "error");
            exit();
        }
        
        if(lcstatus == 'tambah'){
        $(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'trhtagih',field:'no_bukti'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
                        if(status_cek==1){
                        swal("Error", "Nomor Telah Dipakai", "error");
                        document.getElementById("nomor").focus();
                        exit();
                        } 
                        if(status_cek==0){
                        swal({
                              title: "Nomor Bisa Dipakai",
                              text: "Harap Tunggu sampai muncul pesan tersimpan!",
                              timer: 2000,
                              showConfirmButton: false
                            });
                        
    //---------------------------
            lcinsert     = " ( no_bukti,  tgl_bukti,  ket,        username, tgl_update, kd_skpd,     nm_skpd,       total,        no_tagih,     sts_tagih,  status ,   tgl_tagih,       jns_spp, jenis, kontrak      ) " ; 
            lcvalues     = " ( '"+cno+"', '"+ctgl+"', '"+cket+"', '',       '',         '"+cskpd+"', '"+cnmskpd+"', '"+ctotal+"', '"+ctagih+"', '"+cstatus+"','"+cjenis_bayar+"', '"+ctgltagih+"', '"+cjenis+"', '"+jns+"', '"+kontrak+"' ) " ;
            $(document).ready(function(){
                $.ajax({
                    type     : "POST",
                    url      : '<?php echo base_url(); ?>/index.php/tukd/simpan_penagihan_ar',
                    data     : ({tabel    : 'trhtagih',  kolom    :lcinsert,    nilai    : lcvalues,    cid    : 'no_bukti',   lcid    : cno,
                                 proses   : 'header', status_byr : cjenis_bayar }),

                    dataType : "json",
                    success  : function(data) {
                        status = data;
                        if ( status == '0') {
                            swal("Error", "Gagal Simpan", "error");
                            exit();
                        } else if(status=='1') {
                            swal("Error", "Data Sudah Ada", "error");
                                  exit();
                               } else {
                                
                                    $('#dg1').datagrid('selectAll');
                                    var rows = $('#dg1').datagrid('getSelections');           
                                    for(var p=0;p<rows.length;p++){
                        
                                        cnobukti   = rows[p].no_bukti;
                                        cnosp2d    = rows[p].no_sp2d;
                                        ckdgiat    = rows[p].kd_sub_kegiatan;
                                        cnmgiat    = rows[p].nm_sub_kegiatan;
                                        crek       = rows[p].kd_rek5;
                                        cnmrek     = rows[p].nm_rek5;
                                        cpad       = angka(rows[p].pad);
                                        cdak       = angka(rows[p].dak);
                                        cdaknf       = angka(rows[p].daknf);
                                        cdau       = angka(rows[p].dau);
                                        cdbhp      = angka(rows[p].dbhp);
                                        cdid      = angka(rows[p].did);
                                        chpp      = angka(rows[p].hpp);
                                        cnilai     = angka(rows[p].nilai);
                                        crek5      = rows[p].kd_rek;
                        
                                        if ( p > 0 ) {
                                           csql = csql+","+"('"+cno+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+crek5+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"','"+cpad+"','"+cdak+"','"+cdau+"','"+cdbhp+"','"+cdaknf+"','"+cdid+"','"+chpp+"')";
                                        } else {
                                            csql = "values('"+cno+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+crek5+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"','"+cpad+"','"+cdak+"','"+cdau+"','"+cdbhp+"','"+cdaknf+"','"+cdid+"','"+chpp+"')";                                            
                                        }
                                    }
                                    //alert(csql);
                                                  
                                    $(document).ready(function(){
                                    $.ajax({
                                         type     : "POST",   
                                         dataType : 'json',                 
                                         data     : ({tabel_detail:'trdtagih',no_detail:cno,sql_detail:csql,proses:'detail', status_byr : cjenis_bayar}),
                                         url      : '<?php echo base_url(); ?>/index.php/tukd/simpan_penagihan_ar',
                                         success  : function(data){                        
                                                    status = data;   
                                                    if ( status=='5' ) {               
                                                    swal("Error", "Data Detail Gagal Simpan", "error");
                                                    } 
                                                    }
                                                    });
                                    });            

                                    swal("Berhasil", "Data Tersimpan", "success");
                                     $("#nomor_hide").attr("value",cno);
                                     $("#no_simpan").attr("value",cno);
                                    lcstatus = 'edit';
                                    exit();
                              
                               }
                    }
                });
            });
            //--------------            
            
        }
        }
        });
        });
    } else{
        $(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'trhtagih',field:'no_bukti'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
                        if(status_cek==1 && cno!=cno_hide){
                        swal("Error", "Nomor Telah Dipakai", "error");
                        exit();
                        } 
                        if(status_cek==0 || cno==cno_hide){
                        swal({
                              title: "Nomor Bisa Dipakai",
                              text: "Harap Tunggu sampai muncul pesan tersimpan!",
                              timer: 2000,
                              showConfirmButton: false
                            });
        //--------
         lcquery    = " UPDATE trhtagih  SET no_bukti='"+cno+"',   tgl_bukti='"+ctgl+"',   ket='"+cket+"', username='', tgl_update='', nm_skpd='"+cnmskpd+"', total='"+ctotal+"',   no_tagih='"+ctagih+"', sts_tagih='"+cstatus+"', status='"+cjenis_bayar+"', tgl_tagih='"+ctgltagih+"', jns_spp='"+cjenis+"', jenis='"+jns+"', kontrak='"+kontrak+"' where no_bukti='"+cno_hide+"' AND kd_skpd='"+cskpd+"' "; 
            
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/tukd/update_penagihan_header_ar',
                data     : ({st_query:lcquery,tabel:'trhtagih',cid:'no_bukti',lcid:cno,lcid_h:cno_hide,status : cjenis_bayar}),
                dataType : "json",
                success  : function(data){
                           status=data ;
                        
                        if ( status=='1' ){
                            swal("Error", "Nomor Bukti sudah dipakai", "error");
                            exit();
                        }
                        
                        if ( status=='2' ){
                              
                              var a         = document.getElementById('nomor').value; 
                              var a_hide    = document.getElementById('nomor_hide').value; 
                              
                              $('#dg1').datagrid('selectAll');
                              var rows = $('#dg1').datagrid('getSelections');           
                              for(var p=0;p<rows.length;p++){
                        
                                        //cnobukti   = rows[p].no_bukti;
                                        cnobukti   = a ;
                                        cnosp2d    = rows[p].no_sp2d;
                                        ckdgiat    = rows[p].kd_sub_kegiatan;
                                        cnmgiat    = rows[p].nm_sub_kegiatan;
                                        crek       = rows[p].kd_rek5;
                                        cnmrek     = rows[p].nm_rek5;
                                        cpad       = angka(rows[p].pad);
                                        cdak       = angka(rows[p].dak);
                                        cdaknf     = angka(rows[p].daknf);
                                        cdau       = angka(rows[p].dau);
                                        cdbhp      = angka(rows[p].dbhp);
                                        cdid       = angka(rows[p].did);
                                        chpp       = angka(rows[p].hpp);
                                        cnilai     = angka(rows[p].nilai);
                                        crek5      = rows[p].kd_rek;
                        
                                        if ( p > 0 ) {
                                           csql = csql+","+"('"+cno+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+crek5+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"','"+cpad+"','"+cdak+"','"+cdau+"','"+cdbhp+"','"+cdaknf+"','"+cdid+"','"+chpp+"')";
                                        } else {
                                            csql = "values('"+cno+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+crek5+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"','"+cpad+"','"+cdak+"','"+cdau+"','"+cdbhp+"','"+cdaknf+"','"+cdid+"','"+chpp+"')";                                            
                                        }
                              }
                                
                                                  
                              $(document).ready(function(){
                                    $.ajax({
                                         type     : "POST",   
                                         dataType : 'json',                 
                                         data     : ({tabel_detail:'trdtagih',no_detail:cno,sql_detail:csql,
                                                      nomor:a_hide,lcid:a,lcid_h:a_hide}),
                                         url      : '<?php echo base_url(); ?>/index.php/tukd/update_penagihan_detail_ar',
                                         success  : function(data){                        
                                                    status = data;  
                                                    if(status=='1'){
                                                        $("#nomor_hide").attr("Value",cno) ;
                                                        $("#no_simpan").attr("Value",cno) ;
                                                        $('#dg1').edatagrid('unselectAll');
                                                        swal("Berhasil", "Data Tersimpan", "success");
                                                        lcstatus = 'edit';
                                                        $('#dg1').edatagrid('unselectAll');
                                                        } 
                                                        else {               
                                                        swal("Error", "Detail data Gagal simpan", "error");
                                                    } 
                                                    }
                                                    });
                                }); 
                            }
                        if ( status=='0' ){
                            swal("Error", "Gagal simpan", "error");
                            exit();
                        }
                        
                    }
            });
            });
        //----------
            }
            }
        });
     });
    
    }
        
       
    
    }

    
    function sisa_bayar(){
        
        var sisa     = angka(document.getElementById('sisa').value);             
        var nil      = angka(document.getElementById('nilai').value);        
        var sisasp2d = angka(document.getElementById('sisasp2d').value);
        var tot      = 0;
        tot          = sisa - nil;
        
        if (nil > sisasp2d) {    
                    swal("Error", "Nilai Melebihi Sisa Sp2d", "error");
                    exit();
        } else {
            if (tot < 0){
                    swal("Error", "Nilai Melebihi Sisa", "error");
                    exit();                
            }
        }           
    }       
                         
                  
    function runEffect() {
        var selectedEffect = 'blind';            
        var options = {};                      
        $( "#tagih" ).toggle( selectedEffect, options, 500 );
    };              
                             
       
                        
   
    
    
    
   
    </script>

</head>
<body>



<div id="content">    
<div id="accordion">
<h3><a href="#" id="section1" onclick="javascript:$('#dg').edatagrid('reload')" >LIST PENAGIHAN <br/>
    <!-- <font color="#F60B0B">Pengajuan SPP dengan sumber dana DAK FISIK untuk sementara dihentikan. Trims</font> -->
</a>
</a></h3>
    <div>
    <p align="right">         
        <button type="submit" id="tambah" class="easyui-linkbutton"  plain="true" onclick="javascript:section2();kosong();load_detail();"><i class="fa fa-plus"></i> Tambah</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="text" value="" id="txtcari"/>
        <button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:cari();"><i class="fa fa-search"></i></button>
        <table id="dg" title="List Pembayaran Transaksi" style="width:870px;height:600px;" >  
        </table>                          
    </p> 
    </div>   

<h3><a href="#" id="section2">PENAGIHAN</a></h3>
   <div  style="height: 350px;">
   <p>       
   <div id="demo"></div>
        <table align="center" style="width:100%;">
            <tr>
                <td style="border-bottom: double 1px red;"><i>No. Tersimpan<i></td>
                <td style="border-bottom: double 1px red;"><input type="text" id="no_simpan" style="border:0;width: 200px;" readonly="true";/></td>
                <td style="border-bottom: double 1px red;">&nbsp;&nbsp;</td>
                <td style="border-bottom: double 1px red;" colspan = "2"><i>Tidak Perlu diisi atau di Edit</i></td>
                    
            </tr>
            <tr>
                <td>No. Penagihan</td>
                <td>&nbsp;<input type="text" id="nomor" style="width: 200px;" onclick="javascript:select();"/> <input  id="nomor_hide" style="width: 20px;" onclick="javascript:select();" hidden /></td>
                <td>&nbsp;&nbsp;</td>
                <td>Tanggal </td>
                <td><input type="text" id="tanggal" style="width: 140px;" /></td>     
            </tr>                        
            <tr>
                <td>S K P D</td>
                <td>&nbsp;<input id="skpd" name="skpd" readonly="true" style="width: 140px;border: 0;" /></td>
                <td></td>
                <td>Nama SKPD :</td> 
                <td><input type="text" id="nmskpd" style="border:0;width: 400px;border: 0;" readonly="true"/></td>                                
            </tr>
            
            <tr>
                <td>Keterangan</td>
                <td colspan="4"><textarea id="keterangan" style="width: 760px; height: 40px;"></textarea></td>
           </tr> 
                <td>Status</td>
                 <td>
                     <select name="status_byr" id="status_byr">
                         <option value="1">SELESAI</option>
                         <option value="0">BELUM SELESAI</option>
                     </select>
                 </td> 
            </tr>
            <tr>
                 <td>Jenis</td>
                 <td>
                     <select name="jns" id="jns">
                         <option value="">TANPA TERMIN / SEKALI PEMBAYARAN</option>
                         <option value="1">KONSTRUKSI DALAM PENGERJAAN</option>
                         <option value="2">UANG MUKA</option>
                         <option value="3">HUTANG TAHUN LALU</option>
                     </select>
                 </td>
            </tr>
            <tr>
                <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Kontrak</td>
                <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><input id="kontrak" name="kontrak" style="width:190px"/> 
                <td colspan="3" align="right">
                    <!--<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();load_detail();">Baru</a>-->
                     <button type="submit" id="edit" class="easyui-linkbutton"  plain="true" onclick="javascript:simpan_transout2();"><i class="fa fa-save"></i> Simpan Edit</button>
                     <button type="primary" id="save" class="easyui-linkbutton"  plain="true" onclick="javascript:simpan_transout();"><i class="fa fa-save"></i> Simpan</button>
                     <button type="delete" id="del" class="easyui-linkbutton"  plain="true" onclick="javascript:hapus();section1();"><i class="fa fa-trash"></i> Hapus</button>
                     <button type="edit" class="easyui-linkbutton"  plain="true" onclick="javascript:section1();"><i class="fa fa-arrow-left"></i> Kembali</button>

                                                       
                </td>
            </tr>
        </table>          
        <table id="dg1" title="Rekening" style="width:870px;height:350px;" >  
        </table>  
        <div id="toolbar" align="right">
            <button type="submit" class="easyui-linkbutton" plain="true" onclick="javascript:tambah();"><i class="fa fa-plus"></i> Tambah Kegiatan</button>
            <button type="delete" class="easyui-linkbutton" plain="true" onclick="javascript:hapus_giat();"><i class="fa fa-trash"></i> Hapus Kegiatan</button>
                </td>
            </tr>
        </table>          
        <table id="dg1" title="Rekening" style="width:870px;height:350px;" >  
        </table>  
        <div id="toolbar" align="right">
            <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Tambah Kegiatan</a>
            <!--<input type="checkbox" id="semua" value="1" /><a onclick="">Semua Kegiatan</a>-->
            <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus_giat();">Hapus Kegiatan</a>
                    
        </div>
        <table align="center" style="width:100%;">
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td ></td>
            <td align="right">Total : <input type="text" id="total" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true"/></td>
        </tr>
        </table>
                
   </p>
   </div>
   
</div>
</div>


<div id="dialog-modal" title="Input Kegiatan">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
    <table>
        <tr>
            <td>Kode Kegiatan</td>
            <td>:</td>
            <td width="300"><input id="giat" name="giat" style="width: 200px;" /></td>
            <td>Nama Kegiatan</td>
            <td>:</td>
            <td><input type="text" id="nmkegiatan" readonly="true" style="border:0;width: 400px;"/></td>
        </tr>        
         <tr>
            <td >Kode Rekening</td>
            <td>:</td>
            <td><input id="rek" name="rek" style="width: 200px;" />
            <input id="rek1" name="rek1" style="width: 200px; border:0;" readonly="true"/></td>
            <td >Nama Rekening</td>
            <td>:</td>
            <td><input type="text" id="nmrek" readonly="true" style="border:0;width: 400px;"/></td>
        </tr>
        <tr>
            <td >Status</td>
            <td>:</td>
            <td colspan="3"><input type="text" id="status_ang" readonly="true" style="text-align:right;border:0;width: 150px;"/></td>            
        </tr>
        <tr style="background-color:#fff5af">
            <td >Sisa SPD</td>
            <td>:</td>
            <td colspan="4"><input type="text" id="tot_spd" readonly="true" style="text-align:right;border:0;width: 140px;"/> - 
            <input type="text" id="tot_trans" readonly="true" style="text-align:right;border:0;width: 140px;"/>  =
            <input type="text" id="sisa_spd" readonly="true" style="text-align:right;border:0;width: 150px;"/></td>            
        </tr>
    </table> 
    <table border="0">  
        <tr>
        <td></td>
        <td></td>
        <td align="center"><b>PAD</b></td>
        <td align="center"><b>DAK FISIK</b></td>
        <td align="center"><b>DAK NON FISIK</b></td>
        <td align="center"><b>DAU</b></td>
        <td align="center"><b>DBPH</b></td>
        <td align="center"><b>DID</b></td>
        <td align="center"><b>HPP</b></td>
        <td align="center"><b>TOTAL</b></td>
        </tr>
        <tr style="background-color:#c6f4ff">
            <td > Sisa Penyusunan</td>
            <td>:</td>
            <td><input type="text" id="sisa_pad" readonly="true" style="text-align:right;border:0;width: 100px;"/></td>            
            <td><input type="text" id="sisa_dak" readonly="true" style="text-align:right;border:0;width: 100px;"/></td>            
            <td><input type="text" id="sisa_daknf" readonly="true" style="text-align:right;border:0;width: 100px;"/></td>            
            <td><input type="text" id="sisa_dau" readonly="true" style="text-align:right;border:0;width: 100px;"/></td>            
            <td><input type="text" id="sisa_dbhp" readonly="true" style="text-align:right;border:0;width: 100px;"/></td>
            <td><input type="text" id="sisa_did" readonly="true" style="text-align:right;border:0;width: 100px;"/></td>
            <td><input type="text" id="sisa_hpp" readonly="true" style="text-align:right;border:0;width: 100px;"/></td>            
            <td><input type="text" id="sisa" readonly="true" style="text-align:right;border:0;width: 100px;"/></td>            
        </tr>
        <tr style="background-color:#c6f4ff">
            <td> Sisa Penyempurnaan</td>
            <td>:</td>
            <td><input type="text" id="sisa_pad_semp" readonly="true" style="text-align:right;border:0;width: 100px;"/></td>            
            <td><input type="text" id="sisa_dak_semp" readonly="true" style="text-align:right;border:0;width: 100px;"/></td>
            <td><input type="text" id="sisa_daknf_semp" readonly="true" style="text-align:right;border:0;width: 100px;"/></td>            
            <td><input type="text" id="sisa_dau_semp" readonly="true" style="text-align:right;border:0;width: 100px;"/></td>            
            <td><input type="text" id="sisa_dbhp_semp" readonly="true" style="text-align:right;border:0;width: 100px;"/></td> 
            <td><input type="text" id="sisa_did_semp" readonly="true" style="text-align:right;border:0;width: 100px;"/></td>
            <td><input type="text" id="sisa_hpp_semp" readonly="true" style="text-align:right;border:0;width: 100px;"/></td>            
            <td><input type="text" id="sisa_semp" readonly="true" style="text-align:right;border:0;width: 100px;"/></td>            
        </tr>
        <tr style="background-color:#c6f4ff">
            <td> Sisa Perubahan</td>
            <td>:</td>
            <td><input type="text" id="sisa_pad_ubah" readonly="true" style="text-align:right;border:0;width: 100px;"/></td>            
            <td><input type="text" id="sisa_dak_ubah" readonly="true" style="text-align:right;border:0;width: 100px;"/></td>
            <td><input type="text" id="sisa_daknf_ubah" readonly="true" style="text-align:right;border:0;width: 100px;"/></td>            
            <td><input type="text" id="sisa_dau_ubah" readonly="true" style="text-align:right;border:0;width: 100px;"/></td>            
            <td><input type="text" id="sisa_dbhp_ubah" readonly="true" style="text-align:right;border:0;width: 100px;"/></td> 
            <td><input type="text" id="sisa_did_ubah" readonly="true" style="text-align:right;border:0;width: 100px;"/></td>
            <td><input type="text" id="sisa_hpp_ubah" readonly="true" style="text-align:right;border:0;width: 100px;"/></td>            
            <td><input type="text" id="sisa_ubah" readonly="true" style="text-align:right;border:0;width: 100px;"/></td>            
        </tr>
        
        
        <tr>
            <td>Nilai</td>
            <td>:</td>
            <td><input type="text" id="nil_pad" style="text-align: right;width: 100px" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:hitung_nilai();"/></td>            
            <!--<td><input type="text" id="nil_dak" style="text-align: right;width: 100px" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:hitung_nilai();"/></td>
            <td><input type="text" id="nil_daknf" style="text-align: right;width: 100px" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:hitung_nilai();"/></td>-->
            <td><input type="text" id="nil_dak" style="text-align: right;width: 100px;" disabled="true" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:hitung_nilai();"/></td>
            <td><input type="text" id="nil_daknf" style="text-align: right;width: 100px;"disabled="true" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:hitung_nilai();" /></td>

            <td><input type="text" id="nil_dau" style="text-align: right;width: 100px" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:hitung_nilai();"/></td>            
            <td><input type="text" id="nil_dbhp" style="text-align: right;width: 100px" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:hitung_nilai();"/></td>
             <td><input type="text" id="nil_did" style="text-align: right;width: 100px" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:hitung_nilai();"/></td>
             <td><input type="text" id="nil_hpp" style="text-align: right;width: 100px" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:hitung_nilai();"/></td>            
            <td><input type="text" id="nilai" style="text-align: right;width: 100px" onkeypress="return(currencyFormat(this,',','.',event))" readonly="true" disabled/></td>            
        </tr>
    </table>  
    </fieldset>
    <fieldset>
    <table align="center">
        <tr>
            <td><button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:append_save();"><i class="fa fa-save"></i> Simpan</button>
                <button type="edit" class="easyui-linkbutton" plain="true" onclick="javascript:keluar();"><i class="fa fa-arrow-left"></i> Keluar</button>                                 
            </td>
        </tr>
    </table>   
    </fieldset>
    <fieldset>
        <table align="right">           
            <tr>
                <td>Total</td>
                <td>:</td>
                <td><input type="text" id="total1" readonly="true" style="font-size: large;text-align: right;border:0;width: 200px;"/></td>
            </tr>
        </table>
        <table id="dg2" title="Input Rekening" style="width:1000px;height:270px;"  >  
        </table>  
     
    </fieldset>  
</div>
</body>
</html>