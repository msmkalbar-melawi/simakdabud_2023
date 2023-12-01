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
            $("#accordion").accordion();
            $("#lockscreen").hide();                        
            $("#frm").hide();
            $( "#dialog-modal").dialog({
            height: 200,
            width: 700,
            modal: true,
            autoOpen:false
        });
		 $( "#dialog-modal-rek" ).dialog({
            height: 320,
            width: 900,
            modal: true,
            autoOpen:false
        });
        $( "#dialog-modal-tr").dialog({
            height: 320,
            width: 500,
            modal: true,
            autoOpen:false
        });
        get_skpd();
        });
   
    
    $(function(){

   	     $('#dd').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
        
        
        $('#dd1').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
        
        
        $('#dd2').datebox({  
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
           });
		  
		  
                $('#sp2d').combogrid({
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
					tglspd = rowData.tgl_sp2d;
							detail_trans_kosong();
					//alert('teeeeee');
					tampil_sp2d(sp2d);
                    //tampil_kegi(sp2d);                                                        
                    }    
                });
                
          $('#spp').edatagrid({
    		url: '<?php echo base_url(); ?>index.php/tukd/load_lpj_tu',
            idField:'id',            
            rownumbers:"true", 
            fitColumns:"true",
            singleSelect:"true",
            autoRowHeight:"false",
            loadMsg:"Tunggu Sebentar....!!",
            pagination:"true",
            nowrap:"true",                       
            columns:[[
        	    {field:'no_lpj',
        		title:'NO LPJ',
        		width:60},
                {field:'tgl_lpj',
        		title:'Tanggal',
        		width:40},
                {field:'nm_skpd',
        		title:'Nama SKPD',
        		width:170,
                align:"left"},
                {field:'ket',
        		title:'Keterangan',
        		width:110,
                align:"left"}
            ]],
            onSelect:function(rowIndex,rowData){
              nomer     = rowData.no_lpj;         
              kode      = rowData.kd_skpd;
              tgllpj	= rowData.tgl_lpj;
              cket		= rowData.ket;
              status_lpj	= rowData.status;
			  no_sp2d=rowData.no_sp2d;

              get(nomer,kode,tgllpj,cket,status_lpj,no_sp2d);
              detail_trans_3();
              load_sum_lpj(); 
              lcstatus = 'edit';                                       
            },
            onDblClickRow:function(rowIndex,rowData){
                section1();
            }
        });
                
           
//==grid view edit
              var nlpj      = document.getElementById('no_lpj').value;
			  
 			$('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1_lpj_ag',
				 queryParams:({ lpj:nlpj }),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"false",
                 singleSelect:"true",
                 nowrap:"true",
                 columns:[[
                    {field:'idx',title:'idx',width:100,align:'left',hidden:'true'},                                        
                    {field:'kdkegiatan',title:'Kegiatan',width:150,align:'left'},
					{field:'kdrek5',title:'Rekening',width:70,align:'left'},
					{field:'nmrek5',title:'Nama Rekening',width:380},
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
						
                      kdkegiatan       = rowData.kdkegiatan;            
                      kdrek5       = rowData.kdrek5;
                      nmrek5  = rowData.nmrek5;
                      nilai1  = rowData.nilai1;
                        tambah_rekening(kdkegiatan,kdrek5,nmrek5,nilai1);	 
                    }	
            }); 
			
   	});
        
           
        
    function val_ttd(dns){
           $(function(){
            $('#ttd').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/pilih_ttd/'+dns,  
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
           });              
         }
         

    
    function get_skpd()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#dn").attr("value",data.kd_skpd);
        								$("#nmskpd").attr("value",data.nm_skpd);
                                            kode   = data.kd_skpd;
                                            validate_spd(kode);
        							  }                                     
        	});  
        }         
    
    
    
    
	     function get(nomer,kode,tgllpj,cket,status_lpj,tgl_awal,tgl_akhir,no_sp2d){
        $("#no_lpj").attr("value",nomer);
        $("#no_lpj_hide").attr("value",nomer);
		$("#skpd").combogrid('setValue',kode);
		$("#sp2d").combogrid('setValue',no_sp2d);
		$("#dd").datebox("setValue",tgllpj);
		$("#keterangan").attr("Value",cket);

		/*
		if ((status_lpj == undefined) || (status =='')|| (status =='null') ){
			status_lpj='0';
		}else{		
			status_lpj='1';		
		}
		
		  //  alert(status);
		  */
        tombol(status_lpj);           
        }
	
                                 
   //     function get(no_lpj,kd_skpd,tgl_lpj){
//        $("#no_lpj").attr("value",no_spj);
//        $("#dn").attr("Value",kd_skpd);		
//		$("#dd").datebox("setValue",tgl_lpj);    
//        tombol(status);           
//        }
        

		
    function getRowIndex(target){  
			var tr = $(target).closest('tr.datagrid-row');  
			return parseInt(tr.attr('datagrid-row-index'));  
		} 
       
    
    function cetak(){
        var nom=document.getElementById("no_spp").value;
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
        $(function(){ 
            $('#spp').edatagrid({
	       url: '<?php echo base_url(); ?>/index.php/tukd/load_lpj_tu',
         queryParams:({cari:kriteria})
        });        
     });
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
     
     
     function section5(){
         $(document).ready(function(){    
             $("#dialog-modal-tr").click();                                               
         });
     }
     
    function tambah_no(){
        judul = 'Input Data No Transaksi';
        $("#dialog-modal-tr").dialog({ title: judul });
        $("#dialog-modal-tr").dialog('open');
        
        document.getElementById("no_spp").focus();
        
        if ( st_12 == 'baru' ){
        $("#no1").attr("value",'');
        $("#no2").attr("value",'');
        $("#no3").attr("value",'');
        $("#no4").attr("value",'');
        $("#no5").attr("value",'');
        }
     }
     
     function tambah_no2(){
        judul = 'Input Data No Transaksi';
        $("#dialog-modal-tr").dialog({ title: judul });
        $("#dialog-modal-tr").dialog('open');
        document.getElementById("no_spp").focus();
        
        if ( st_12 == 'baru' ){
        $("#no1").attr("value",'');
        $("#no2").attr("value",'');
        $("#no3").attr("value",'');
        $("#no4").attr("value",'');
        $("#no5").attr("value",'');
        }
     } 
     
     
     function simpan(){        

        var kd_skpd      =$("#skpd").combogrid('getValue') ;
        var nlpj      = document.getElementById('no_lpj').value;
        var nsp2d      =$("#sp2d").combogrid('getValue') ;
   		var tgl_lpj      = $('#dd').datebox('getValue');  
	    var nket      = document.getElementById('keterangan').value;

		if ( lcstatus=='tambah' ) {

		$.ajax({url: '<?php echo base_url(); ?>index.php/tukd/cek_lpj',   
            type: "POST",
            dataType:'json',                             
			data:({nlpj:nlpj}),	
									 
				 success:function(data)				 
				 {
				      
	  if (data.jml >0) {

		 alert('Data Dengan No LPJ '+nlpj +' Sudah Ada Ganti Dengan Yang Lain...!!!');
		
		exit();
		
		}
		else
		{
		
		$(document).ready(function(){
            $.ajax({
                type: "POST",       
                dataType : 'json',         
                url      : "<?php  echo base_url(); ?>index.php/tukd/simpan_hlpj_tu",
				data     : ({nlpj:nlpj,tgllpj:tgl_lpj,ket:nket,kode:kd_skpd,sp2d:nsp2d}),
                success:function(data){
                    status = data.pesan;                                                               
                }
            });
        });

//==========DN
		
		$('#dg1').datagrid('selectAll');
        var rows = $('#dg1').datagrid('getSelections');
        
        for(var i=0;i<rows.length;i++){            
            
            cidx      = rows[i].idx;
            ckdgiat   = rows[i].kdkegiatan;
            cnmgiat   = rows[i].nmkegiatan;
            ckdrek    = rows[i].kdrek5;
            cnmrek    = rows[i].nmrek5;
            cnilai    = angka(rows[i].nilai1);
                       
            no        = i + 1 ;      
            
            $(document).ready(function(){      
            $.ajax({
            type     : 'POST',
            url      : "<?php  echo base_url(); ?>index.php/tukd/simpan_lpj_tu",
            data     : ({nlpj:nlpj,tgllpj:tgl_lpj,ket:nket,cnkdgiat:ckdgiat,cnkdrek:ckdrek,cnnmrek:cnmrek,cnnilai:cnilai,kode:kd_skpd}),
            dataType : "json",
			
			             success  : function(data){
                         status = data;


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
                                  exit();
                               }
               
						
        $('#dg1').edatagrid('unselectAll');
		
//=========DNA

		
		}
}
});


 } else {
 
 
 //==========DN
 
         var tny = confirm('Yakin Ingin Update Data LPJ No :  '+nlpj+'  ..?');
        
        if ( tny == true ) {            
        var kd_skpd      =$("#skpd").combogrid('getValue') ;
        var nlpj      = document.getElementById('no_lpj').value;
		var nlpj_hide      = document.getElementById('no_lpj_hide').value;
   		var tgl_lpj      = $('#dd').datebox('getValue');  
	    var nket      = document.getElementById('keterangan').value;		
		var nsp2d      =$("#sp2d").combogrid('getValue') ;		
		 $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:nlpj_hide,tabel:'trhlpj',field:'no_lpj'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1){
						alert("Nomor Telah Dipakai!");
						document.getElementById("no_lpj").focus();
						exit();
						} 
						if(status_cek==0){
						alert("Nomor Bisa dipakai");
			
		$(document).ready(function(){
            $.ajax({
                type: "POST",       
                dataType : 'json',         
                url      : "<?php  echo base_url(); ?>index.php/tukd/update_hlpj",
				data     : ({nlpj:nlpj,tgllpj:tgl_lpj,ket:nket,kode:kd_skpd,sp2d:nsp2d,nlpj_hide:nlpj_hide}),
                success:function(data){
                    status = data.pesan;                                                               
                }
            });			
        });
						}
					}
		});
		
		$('#dg1').datagrid('selectAll');
        var rows = $('#dg1').datagrid('getSelections');
        
        for(var i=0;i<rows.length;i++){            
            
            cidx      = rows[i].idx;
            ckdgiat   = rows[i].kdkegiatan;
            cnmgiat   = rows[i].nmkegiatan;
            ckdrek    = rows[i].kdrek5;
            cnmrek    = rows[i].nmrek5;
            cnilai    = angka(rows[i].nilai1);
                       
            no        = i + 1 ;      
            
            $(document).ready(function(){      
            $.ajax({
            type     : 'POST',
            url      : "<?php  echo base_url(); ?>index.php/tukd/update_lpj",
            data     : ({nlpj:nlpj,tgllpj:tgl_lpj,ket:nket,cnkdgiat:ckdgiat,cnkdrek:ckdrek,cnnmrek:cnmrek,cnnilai:cnilai,kode:kd_skpd}),
            dataType : "json",
			
			             success  : function(data){
                         status = data;


                    }

        });
        });
        }
		

		                   if (status=='0'){
                            alert('Gagal Update..!!');
                            exit();
                        } else {
                                  alert('Data Update...!!!');
                                  lcstatus = 'edit';
                                  exit();
                               }
               
		 $("#no_lpj_hide").attr("Value",nlpj_hide) ;	
        $('#dg1').edatagrid('unselectAll');
		
//=========DNA


}
       }
    }
    
     
    
    function kembali(){
        $('#kem').click();
    }                
    
    
     function load_sum_lpj(){          
        
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd/load_sum_lpj",
            data:({lpj:nomer}),
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){

                    $("#rektotal").attr('value',number_format(n['cjumlah'],2,'.',','));
                  //  $("#rektotal1").attr('value',number_format(n['rektotal'],2,'.',','));
                });
            }
         });
        });
    }
    
    
    function load_sum_tran(){                
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({no_bukti:no_bukti}),
            url:"<?php echo base_url(); ?>index.php/tukd/load_sum_tran",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    //$("#rektotal").attr("value",n['rektotal']);
                    //$("#rektotal1").attr("value",n['rektotal1']);
                    $("#rektotal").attr('value',number_format(n['rektotal'],2,'.',','));
                    $("#rektotal1").attr('value',number_format(n['rektotal'],2,'.',','));

                });
            }
         });
        });
    }
   
   
    function tombol(status_lpj){
	
    if (status_lpj=='1') {
        $('#save').linkbutton('disable');
        $('#del').linkbutton('disable');
        $('#sav').linkbutton('disable');
        $('#dele').linkbutton('disable');   
        $('#load').linkbutton('disable');
        $('#load_kosong').linkbutton('disable'); 
        document.getElementById("p1").innerHTML="Sudah di Buat SPP GU...!!!";
     } else {
         $('#save').linkbutton('enable');
         $('#del').linkbutton('enable');
         $('#sav').linkbutton('enable');
         $('#dele').linkbutton('enable');
         $('#load').linkbutton('enable');
         $('#load_kosong').linkbutton('enable'); 
         document.getElementById("p1").innerHTML="";
     }
    }	
    
        
    function openWindow(url)
    {
        var vnospp  =  $("#cspp").combogrid("getValue");
         
		        lc  =  "?nomerspp="+vnospp+"&kdskpd="+kode+"&jnsspp="+jns ;
        window.open(url+lc,'_blank');
        window.focus();
    }
    
        
    function detail_trans_3(){
       // detail_trans_kosong();
	   nomer= document.getElementById('no_lpj').value;
        $(function(){
			$('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1_lpj_ag',
                queryParams:({ lpj:nomer }),
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
                     {field:'kdkegiatan',
					 title:'Kegiatan',
					 width:150,
					 align:'left'
					 },
					{field:'kdrek5',
					 title:'Rekening',
					 width:70,
					 align:'left'
					 },
					{field:'nmrek5',
					 title:'Nama Rekening',
					 width:280
					 },
                    {field:'nilai1',
					 title:'Nilai',
					 width:140,
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
		$('#dg1').datagrid('selectAll');
		var rows = $('#dg1').datagrid('getSelections');  // get all selected rows
		var ids = [];
		for(var i=0; i<rows.length; i++){	
		var index = $('#dg1').datagrid('getRowIndex',rows[i]);  // get the row index
		ids.push(index);
		}
		ids.sort();  // sort index
		ids.reverse();  // sort reverse
		for(var i=0; i<ids.length; i++){
		$('#dg1').datagrid('deleteRow',ids[i]);
		}	

        }
    
        
   
    
    function hapus_detail(){
        
        var a          = document.getElementById('no_lpj').value;
        var rows       = $('#dg1').edatagrid('getSelected');
        var ctotal_lpj = document.getElementById('rektotal').value;
        
        bbukti      = rows.no_bukti;
        bkdrek      = rows.kdrek5;
        bkdkegiatan = rows.kdkegiatan;
        bnilai      = rows.nilai1;
        ctotal_lpj  = angka(ctotal_lpj) - angka(bnilai) ;
        
        var idx = $('#dg1').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, No Bukti :  '+bbukti+'  Rekening :  '+bkdrek+'  Nilai :  '+bnilai+' ?');
        
        if ( tny == true ) {
            
            $('#rektotal').attr('value',number_format(ctotal_lpj,2,'.',','));
            $('#dg1').datagrid('deleteRow',idx);     
            $('#dg1').datagrid('unselectAll');
              
             var urll = '<?php echo base_url(); ?>index.php/tukd/dsimpan_lpj';
             $(document).ready(function(){
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
             });    
        }     
    }
    
  function hhapus(){				
            var lpj = document.getElementById("no_lpj").value;              
            var urll= '<?php echo base_url(); ?>/index.php/tukd/hhapuslpj';             			    
         	if (spp !=''){
				var del=confirm('Anda yakin akan menghapus LPJ '+lpj+'  ?');
				if  (del==true){
					$(document).ready(function(){
                    $.post(urll,({no:lpj}),function(data){
                    status = data;                       
                    });
                    });				
				}
				} 
	}
  
    function hapus_detail_grid(){
        
        var a          = document.getElementById('no_lpj').value;
        var rows       = $('#dg1').edatagrid('getSelected');
        var ctotal_lpj = document.getElementById('rektotal').value;
        
        bkdrek      = rows.kdrek5;
        bkdkegiatan = rows.kdkegiatan;
        bnilai      = rows.nilai1;
        ctotal_lpj  = angka(ctotal_lpj) - angka(bnilai) ;
        
        var idx = $('#dg1').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data,  Rekening :  '+bkdrek+'  Nilai :  '+bnilai+' ?');
        
        if ( tny == true ) {
            
            $('#rektotal').attr('value',number_format(ctotal_lpj,2,'.',','));
            $('#dg1').datagrid('deleteRow',idx);     
            $('#dg1').datagrid('unselectAll');
              
            // var urll = '<?php echo base_url(); ?>index.php/tukd/dsimpan_lpj';
//             $(document).ready(function(){
//             $.post(urll,({cnolpj:a,cnobukti:bbukti}),function(data){
//             status = data;
//                if (status=='0'){
//                    alert('Gagal Hapus..!!');
//                    exit();
//                } else {
//                    alert('Data Telah Terhapus..!!');
//                    exit();
//                }
//             });
//             });    
        }     
    }

  
  
    
    function load_datax() {

        var dtgl1        = $('#dd1').datebox('getValue') ;
        var dtgl2        = $('#dd2').datebox('getValue') ;
        var ntotal_trans = document.getElementById('rektotal').value ; 
            ntotal_trans = angka(ntotal_trans) ;
        
        if ( dtgl1 == '' ) {
           alert('Isi Tanggal Awal Terlebih Dahulu...!!!'); 
           document.getElementById('dd1').focus() ;
           exit();
           }       
        if ( dtgl2 == '' ) {
           alert('Isi Tanggal S/D Terlebih Dahulu...!!!'); 
           document.getElementById('dd2').focus() ;
           exit();
           }
          
        $(document).ready(function(){
            
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/tukd/load_data_transaksi_lpj_tu',
                data: ({tgl1:dtgl1,tgl2:dtgl2,kdskpd:kode}),
                dataType:"json",
                success:function(data){                                          
                    $.each(data,function(i,n){                                                                                                                           
                    xgiat    = n['kdkegiatan']; 
                    xkdrek5  = n['kdrek5'];
                    xnmrek5  = n['nmrek5'];
                    xnilai   = n['nilai1'];
                    
                    ntotal_trans = ntotal_trans + angka(xnilai) ;
                    
                    $('#dg1').edatagrid('appendRow',{kdkegiatan:xgiat,kdrek5:xkdrek5,nmrek5:xnmrek5,nilai1:xnilai,idx:i}); 
                    $('#dg1').edatagrid('unselectAll');
                    $('#rektotal').attr('value',number_format(ntotal_trans,2,'.',','));
                    });
                 }
            });
            });   
    }
	
    function tampil_sp2d(no_sp2d) {

 var ntotal_trans = document.getElementById('rektotal').value ; 
            ntotal_trans = angka(ntotal_trans) ;
            $(document).ready(function(){            
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/tukd/load_transaksi_sp2d_tu',
                data : ({kode:kode,sp2d:no_sp2d }),
                dataType:"json",
                success:function(data){                                          
                    $.each(data,function(i,n){                                                            
                    xgiat    = n['kdkegiatan']; 
                    xkdrek5  = n['kdrek5'];
                    xnmrek5  = n['nmrek5'];
                    xnilai   = n['nilai1'];
                    ntotal_trans = ntotal_trans + angka(xnilai) ;                    
                    $('#dg1').edatagrid('appendRow',{kdkegiatan:xgiat,kdrek5:xkdrek5,nmrek5:xnmrek5,nilai1:xnilai,idx:i}); 
                    $('#dg1').edatagrid('unselectAll');
                    $('#rektotal').attr('value',number_format(ntotal_trans,2,'.',','));
                    });
					
                 }
            });
            });
    }  
	
	
        function kosong(){
        $("#no_lpj").attr("value",'');
        $("#dd").datebox("setValue",'');
      $("#keterangan").attr("value",'');
	  $("#no_lpj").focus();
	  $("#rektotal").attr("value",0)
        $("#sp2d").combogrid('setValue','');
        $("#skpd").combogrid('setValue','');        
	  $("#nm_skpd").attr("value",'')
	  $('#save').linkbutton('enable');
		 $("#rektotal").attr('value',0);
         $("#rektotal1").attr('value',0);

        st_12 = 'baru';
        detail_trans_kosong();


        lcstatus = 'tambah'
        }
	
    function keluar_rek(){
        $("#dialog-modal-rek").dialog('close');
        $("#dg1").datagrid("unselectAll");
        $("#rek_nilai").attr("Value",0);
        $("#nilai_sp2d").attr("Value",0);
        $("#sisa_sp2d").attr("Value",0);
    }  

    function tambah_rekening(kdkegiatan,kdrek5,nmrek5,nilai1){
	var vnilai9=0;
	var no_sp2d=$("#sp2d").combogrid('getValue');
		// $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd/ambil_nilai_sp2d_tu",
            data:({sp2d:no_sp2d,kegi:kdkegiatan,rek:kdrek5}),
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
				vnilai9=angka(n['nilai_sp2d']);	
				var vnilai7=number_format(vnilai9,2,'.',',');
				$("#nilai_sp2d").attr("Value",vnilai7);
				var sisa=vnilai9-angka(nilai1); 
		   $("#sisa_sp2d").attr("Value",sisa); 			
		   $("#rek_skpd").attr("disable");
           $("#rek_kegi").attr("disable");
		   $("#rek_skpd").attr("Value",kode);		   
		   $("#rek_kegi").attr("Value",kdkegiatan);

           $("#rek_reke").attr("Value",kdrek5);

           $("#nm_rek_reke").attr("Value",nmrek5); 
		   var xx=angka(nilai1);
		   xx7=number_format(xx,2,'.',',');
		   
           $("#rek_nilai").attr("Value",xx7);
		   $("#dialog-modal-rek").dialog('open'); 
				
                });
            }
         });		                
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
						
			$("#dialog-modal-rek").dialog('close'); 
            
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
<div id="accordion" style="width:970px;height=970px;" >
<h3><a href="#" id="section4" onclick="javascript:$('#spp').edatagrid('reload')">List LPJ </a></h3>
<div>
    <p align="right">  
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section1();kosong();">Tambah</a>               
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="spp" title="List LPJ" style="width:910px;height:450px;" >  
        </table>
    </p> 
</div>

<h3><a href="#" id="section1">Input LPJ</a></h3>

   <div  style="height: 350px;">
   <p id="p1" style="font-size: x-large;color: red;"></p>
   <p>


 
 
 <fieldset style="width:850px;height:650px;border-color:white;border-style:hidden;border-spacing:0;padding:0;">            

<table border='0' style="font-size:11px" >
 
  
  <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
   <td  width='20%'>No LPJ</td>
   <td width='80%'><input type="text" name="no_lpj" id="no_lpj" onclick="javascript:select();" style="width:225px" onkeypress="javascript:enter(event.keyCode,'dd');"/> 
   <input type="hidden" name="no_lpj_hide" id="no_lpj_hide" style="width:140px"/></td>
 </tr>
  <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"> 
   <td  width='20%'>Tanggal</td>
 <td>&nbsp;<input id="dd" name="dd" type="text" style="width:95px" onkeypress="javascript:enter(event.keyCode,'keterangan');"/></td>   
 </tr>
  <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
 
   <td width='20%'>SKPD</td>
   <td width="80%">     
        <input id="skpd" name="skpd"  readonly="true" style="width:130px; border: 0; " />       
        </td>
  <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
 
     <td width='20%'  style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"></td>
     <td width='31%' style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
	 <input id="nm_skpd" name="nm_skpd" style="width:730px; border: 0;"/></td>
		
		
		    <td width='20%'  style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"></td>
     <td width='31%' style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"></td>	
</tr>
<tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
 
   <td width='20%'>No SP2D</td>
   <td width="80%">&nbsp;<input id="sp2d" name="sp2d" style="width:300px" />  
      
        </td> 
</tr>

  <tr>
      <td width='20%'  style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">KETERANGAN</td>
     <td width='50%' style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"><textarea name="keterangan" id="keterangan" cols="100" rows="2"  ></textarea></td>
  
  </tr>
		<tr style="height: 30px;">
      <td colspan="4">
                  <div align="right">
                    <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Baru</a>
                    <a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true"  onclick="javascript:simpan();">Simpan</a>
                    <a id="del"class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hhapus();javascript:section4();">Hapus</a>
                    <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a>
                    <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section4();">Kembali</a>
                  </div>
        </td>                
  </tr>
        

  </table>
   
        <table id="dg1" title="Input Detail LPJ" style="width:900%;height:300%;" >  
        </table>

        <table border='0' style="width:100%;height:5%;"> 
             <td width='34%'></td>
             <td width='35%'><input class="right" type="hidden" name="rektotal1" id="rektotal1"  style="width:140px" align="right" readonly="true" ></td>
             <td width='6%'><B>Total</B></td>
             <td width='25%'><input class="right" type="text" name="rektotal" id="rektotal"  style="width:140px" align="right" readonly="true" ></td>
        </table>

   </p>

</fieldset>     
</div>
</div>
</div> 

<div id="dialog-modal" title="CETAK SPP">
    
    <p class="validateTips">SILAHKAN PILIH SPP</p>  
    <fieldset>
        <table>
            <tr>            
                <td width="110px" >NO SPP:</td>
                <td><input id="cspp" name="cspp" style="width: 210px; " /></td>
            </tr>
            
            <tr>
                <td width="110px">Penandatangan:</td>
                <td><input id="ttd" name="ttd" style="width: 170px;" /></td>
            </tr>
        </table>  
    </fieldset>
    
    <div>
    </div>     

    <a href="<?php echo site_url(); ?>/tukd/cetakspp1_ar" class="easyui-linkbutton" plain="true" onclick="javascript:openWindow(this.href);return false;">SPP1</a>
    <a href="<?php echo site_url(); ?>/tukd/cetakspp2_ar" class="easyui-linkbutton" plain="true" onclick="javascript:openWindow(this.href);return false;">SPP2</a>
    <a href="<?php echo site_url(); ?>/tukd/cetakspp3_ar" class="easyui-linkbutton" plain="true" onclick="javascript:openWindow(this.href);return false;">SPP3</a>
	     
    <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>  

</div>
 	
<div id="dialog-modal-rek" title="Input Rekening">
    <p class="validateTips"></p>  
    <fieldset>
    <table align="center" style="width:100%;" border="0">
       
            <tr>
                <td width='15%'>SKPD</td>
                <td width='3%'>:</td>
                <td colspan="4" width='82%'><input id="rek_skpd" name="rek_skpd" style="width: 200px;"  readonly="true"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="rek_nmskpd" style="border:0;width: 300px;"  readonly="true"/></td>                            
            </tr>

            <tr>
                <td>KEGIATAN</td>
                <td>:</td>
                <td colspan="4"><input id="rek_kegi" name="rek_kegi" style="width: 200px;"  readonly="true"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="nm_rek_kegi" style="border:0;width: 300px;"  readonly="true"/></td>                            
            </tr>

            <tr>
                <td>REKENING</td>
                <td>:</td>
                <td colspan="4"><input id="rek_reke" name="rek_reke" style="width: 200px;"  readonly="true"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="nm_rek_reke" style="border:0;width: 300px;"  readonly="true"/></td>                            
            </tr>
            <tr>
                <td>NILAI</td>
                <td>:</td>
                <td><input type="text" id="rek_nilai" style="background-color:#99FF99; width: 196px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td> 
            </tr>

            <tr>
                <td>NILAI SP2D</td>
                <td>:</td>
                <td><input type="text" id="nilai_sp2d" style="width: 196px; text-align: right; "  readonly="true" /></td> 

            </tr>
            <tr>
                <td>SISA SP2D</td>
                <td>:</td>
                <td><input type="text" id="sisa_sp2d" style="width: 196px; text-align: right; "  readonly="true" /></td> 

            </tr>
                       
                       
            <tr>
                <td colspan="3" align="center">
                <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:update_save();">Simpan</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar_rek();">Keluar</a>  
                </td>
            </tr>
            
    </table>
	
    </fieldset>
	
</div>
</body>
</html>