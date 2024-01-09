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
   
    // var no_sp3b   = ''; 
    var no_lpjx   = '';
    var kode     = '';
    var spd      = '';
    var st_12    = 'edit';
    var nidx     = 100
    var spd2     = '';
    var spd3     = '';
    var spd4     = '';
    var lcstatus = '';
    var skpdd    = '';
    var tahun_anggaran ='';
    

    $(document).ready(function() {
            $("#accordion").accordion();
            $("#lockscreen").hide();                        
            $("#frm").hide();
            $( "#dialog-modal").dialog({
            height: 200,
            width: 550,
            modal: true,
            autoOpen:false
        });
        $( "#dialog-modal-tr").dialog({
            height: 320,
            width: 500,
            modal: true,
            autoOpen:false
        });
		get_tahun();
        get_skpd();
        $('#revisi').attr('checked', false);
              document.getElementById("revisi").checked = false;
		$("#div1").hide();
		
		$("#loading").dialog({
				resizable: false,
				width:200,
				height:130,
				modal: true,
				draggable:false,
				autoOpen:false,    
				closeOnEscape:false
				});
		
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
        
		$('#dd_sp2b').datebox({  
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

	$('#nosp2d').combogrid({
           panelWidth:255,  
           idField:'no_sp2d',  
           textField:'no_sp2d',  
           mode:'remote',
           //url:'<?php echo base_url(); ?>index.php/tukd/load_sp2d_lpj_tu',
           //url:'<?php echo base_url(); ?>index.php/rka/load_trskpd/'+kode,             
           columns:[[  
               {field:'no_sp2d',title:'NO SP2D',width:150},
               {field:'tgl_cair',title:'Tgl Pencairan',width:100}  
           ]],
        });
    //   $('#sp2d').combogrid({
    //        panelWidth:255,  
    //        idField:'no_sp2d',  
    //        textField:'no_sp2d',  
    //        mode:'remote',
    //        queryParams:({kode:kode}),
    //        url:'<?php echo base_url(); ?>index.php/tukd/load_sp2d_lpj_tu',
    //        //url:'<?php echo base_url(); ?>index.php/rka/load_trskpd/'+kode,             
    //        columns:[[  
    //            {field:'no_sp2d',title:'NO SP2D',width:150},
    //            {field:'tgl_cair',title:'Tgl Pencairan',width:100}  
    //        ]],  
    //        onSelect:function(rowIndex,rowData){
	// 		   $("#tgl_sp2d").attr("value",rowData.tgl_cair);
	// 		   //detail_trans_kosong();
	// 		   //load_data();
    //        }
    //     });
		
		$('#ttd1').combogrid({  
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/blud/SP2BBludController/load_ttd/BUD',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},
                    {field:'nama',title:'Nama',width:400}
                ]],  
           onSelect:function(rowIndex,rowData){
               $("#nm_ttd1").attr("value",rowData.nama);
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
			
         
            $('#ttd2').combogrid({  
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/PA',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},
                    {field:'nama',title:'Nama',width:400}
                ]],  
           onSelect:function(rowIndex,rowData){
               $("#nm_ttd2").attr("value",rowData.nama);
           } 
            });
		
        	//cetak sp2b elvara
		$('#cspp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/blud/SP2BBludController/load_sp2b',  
                    idField:'no_sp2b',                    
                    textField:'no_sp2b',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_sp2b',title:'NO SP2B',width:60},                          
                        {field:'tgl_sp2b',title:'Tanggal',width:60} 
                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    nomer = rowData.no_sp2b;
                    kode = rowData.kd_skpd;
					// pilih_giat(nomer);
                    //jns = rowData.jns_spp;
                    //val_ttd(kode);
                    }   
                });
                
          $('#spp').edatagrid({
			rowStyler:function(index,row){
				if (row.status_bud==1){
				   return 'background-color:#4bbe68;color:white';
				}
			},
    		url: '<?php echo base_url(); ?>/index.php/blud/SP2BBludController/load_sp2b',
            idField:'id',            
            rownumbers:"true", 
            fitColumns:"true",
            singleSelect:"true",
            autoRowHeight:"false",
            loadMsg:"Tunggu Sebentar....!!",
            pagination:"true",
            nowrap:"true",  
            columns:[[
        	    {field:'no_sp2b',
        		title:'SP2B',
        		width:80},
                {field:'tgl_sp2b',
        		title:'Tanggal SP2B',
        		width:40},
                {field:'nm_skpd',
        		title:'Nama SKPD',
        		width:80,
                align:"left"},
                // {field:'no_sp3b',
        		// title:'SP3B',
        		// width:100,
                // align:"left"},
                // {field:'tgl_sp3b',
        		// title:'Tanggal SP3B',
        		// width:40,
                // align:"left"},
				{field:'status',
				title:'Status',
				width:5,
				align:"left",
				hidden:"true"
				}
            ]],
            onSelect:function(rowIndex,rowData){
            //   nomer_sp3b     = rowData.no_sp3b; 
      		//   tglsp3b	     = rowData.tgl_sp3b;
      		  nomer_sp2b     = rowData.no_sp2b;  
      		  tglsp2b	     = rowData.tgl_sp2b;
              kode           = rowData.skpd;
              nmskpd         = rowData.nm_skpd;
              cket		     = rowData.ket;
              tglawl         = rowData.tglawl;
              tglakr         = rowData.tglakr;
              total          = rowData.total;
              cstatus        = rowData.status;
              crevisi        = rowData.revisi;
              get(nomer_sp2b,tglsp2b,kode,nmskpd,cket,tglawl,tglakr,total,cstatus,crevisi);
              detail_trans_3(nomer_sp2b, tglawl, tglakr, kode);
             //load_sum_lpj();                            
            },
            onDblClickRow:function(rowIndex,rowData){
                section1();
                detail_trans_3(nomer_sp2b, tglawl, tglakr, kode);
            }/* end dblclik*/
        });
                
              var nlpj      = no_lpjx;          			  

			$('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/blud/SP2BBludController/select_data1_lpj_ag',
                queryParams:({kdskpd: kode }),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"false",
                 singleSelect:"true",
                 nowrap:"true",				 
				columns:[[
                    {field:'idx',title:'idx',width:50,align:'left',hidden:'true'},
                    {field:'no_sp3b',title:'No SP3B',width:150,align:'right', hidden:'true'},
                    {field:'no_sp2b',title:'No SP2B',width:150,align:'right', hidden:'true'},
                    {field:'kd_sub_kegiatan',title:'Kd Sub Kegiatan',width:150,align:'right'},
                    {field:'tgl_sp3b',title:'Tgl SP3B',width:150,align:'right', hidden:'true'},
                    {field:'ket',title:'Keterangan',width:150,align:'right', hidden:'true'},
                    {field:'kd_skpd',title:'Kd SKPD',width:150,align:'right', hidden:'true'},
                    {field:'kd_rek6',title:'Kd rek6',width:120,align:'left'},
                    {field:'nm_rek6',title:'Nm Rek6',width:260,align:'right'},                                                                                     
                    {field:'nilai',title:'Nilai',width:200,align:'right'}
				]]	
			});

   	});
      

    function get_nomor_sp2b()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/SP2BBludController/no_urut_sp2b',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#no_sp2b").attr("value",data.no_urut);
        							  }                                     
        	});  
        }       
        
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
        		url:'<?php echo base_url(); ?>index.php/blud/SP2BBludController/skpd_rsud',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#dn").attr("value",data.kd_skpd);
        								$("#nmskpd").attr("value",data.nm_skpd);
                                            kode   = data.kd_skpd;
                                        }                                     
        	});  
        }         

   function get_tahun() {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd/config_tahun',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        			tahun_anggaran = data;
        			}                                     
        	});
             
        } 
    
    
	    function get(nomer_sp2b,tglsp2b,kode,nmskpd,cket,tglawl,tglakr,total,cstatus,crevisi){
        $("#no_sp2b").attr("value",nomer_sp2b);
        // $("#no_sp3b").attr("value",nomer_sp3b);
        if (crevisi!=0 || crevisi!='0'){
            document.getElementById("revisi").checked = true;
           } else{
            document.getElementById("revisi").checked = false;
           }
            
           
        $("#dn").attr("setValue",kode);
        $("#nmskpd").attr("Value",nmskpd);		
        // $("#dd").datebox("setValue",tglsp3b);
        $("#dd_sp2b").datebox("setValue",tglsp2b);
        $("#keterangan").attr("Value",cket);
        $("#dd1").datebox("setValue",tglawl);
        $("#dd2").datebox("setValue",tglakr);
        $("#rektotal").attr("Value",total);
        tombol1edit(cstatus);
        }
	
        function tombol1new() {
            $('#save').show();    
            $('#delete').hide();
            $('#print').hide();
            $('#exit').show();
        }

        function tombol1edit(cstatus) {
            if(cstatus==1){
            $('#save').hide();    
            $('#delete').show();
            $('#print').show();
            $('#exit').show();
            }else{
            $('#save').show();    
            $('#delete').hide();
            $('#print').hide();
            $('#exit').show();
            }
        }
        
		
        function kosong(){
        $("#no_sp2b").attr("value",'');
        document.getElementById("revisi").checked = false;
        // $("#no_sp3b").attr("value",'');
        $("#dd_sp2b").datebox("setValue",'');
        // $("#dd").datebox("setValue",'');
        $("#dd1").datebox("setValue",'');
        $("#dd2").datebox("setValue",'');
        $("#keterangan").attr("value",'');
	    $("#rektotal").attr("value",0);
        st_12 = 'baru';
        detail_trans_kosong();
        tombol1new();
        }

		
    function getRowIndex(target){  
			var tr = $(target).closest('tr.datagrid-row');  
			return parseInt(tr.attr('datagrid-row-index'));  
		} 
       
    
    function cetak(){

        var no_sp2bb = document.getElementById('no_sp2b').value;
        // var no_sp3b = document.getElementById('no_sp3b').value;
        //var no_sp2bd = no_sp2bb + no_sp2bc;
        $("#cspp").combogrid("setValue",no_sp2bb);
        $("#dialog-modal").dialog('open');


    } 
    
    function pilih_giat(nomer){
		$('#giat_print').combogrid({  
                panelWidth:600,  
                idField:'kd_kegiatan',  
                textField:'kd_kegiatan',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_giat_lpj', 
				queryParams:({ lpj:nomer }),
                columns:[[  
                    {field:'kd_kegiatan',title:'NIP',width:200},
                    {field:'nm_kegiatan',title:'Nama',width:400}
                ]]
            });
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
	       url: '<?php echo base_url(); ?>/index.php/tukd_pusk/load_sp3b_fktp',
         queryParams:({cari:kriteria})
        });        
     });
    }
    
     
    function section1(){
         $(document).ready(function(){    
             $('#section1').click();
         });
     }
     
    //e
    function section4(){
         $(document).ready(function(){    
            //  $('#exit').click(function(){
            //     location.reload(true);
            //  });          
            $('#section4').click();                                          
         });
     }

     function keluar_trans(){
        $("#section1").dialog('close');
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
     
     function cetakup(cetak){
        var no_sp2b = document.getElementById('no_sp2b').value;
        var no_sp2b1 = no_sp2b.split("/").join("123456789");   	
		// var no_sp3b = document.getElementById('no_sp3b').value;    
        // var no_sp3b1 = no_sp3b.split("/").join("123456789");   
        var tgl_sp2b  = document.getElementById('dd_sp2b').value;		
		var skpd   = kode; 
		var ttd1   = $("#ttd1").combogrid('getValue');
		 
		if(ttd1==''){
			alert('Penandatangan tidak boleh kosong!');
			exit();
		}		
		var ttd_1 =ttd1.split(" ").join("a");
		//var ttd_2 =ttd2.split(" ").join("a");
		
			var url    = "<?php echo site_url(); ?>tukd_pusk/cetak_sp2b_fktp";  
			window.open(url+'/'+ttd_1+'/'+skpd+'/'+cetak+'/'+'/'+tgl_sp2b+'/'+no_sp2b1, '_blank');
			window.focus();
		
     }	 
	 
     function simpan(){        
        var nosp2b    = document.getElementById('no_sp2b').value;
        // var nosp3b    = document.getElementById('no_sp3b').value;
        var tglsp2b   = $('#dd_sp2b').datebox('getValue'); 
        // var tglsp3b   = $('#dd').datebox('getValue'); 
   		var nket      = document.getElementById('keterangan').value;
        var kdskpd    = document.getElementById('dn').value;
        var nmskpd    = document.getElementById('nmskpd').value;
        if (document.getElementById('revisi').checked == true) {
            revisi = '1';
        } else {
            revisi = '0';
        }
        var ctgl1     = $('#dd1').datebox('getValue');
        var ctgl2     = $('#dd2').datebox('getValue');
        var total     = angka(document.getElementById('rektotal').value);
        var a = nosp2b.substr(0,4);
        var b = nosp2b.substr(9,15);
        var nosp3b = a+'SP3B/'+b;
        // var tahun_input = tglsp3b.substring(0, 4);
		if (!nosp2b){
			alert('Silahkan Isi No SP2B !');
			exit();
		}
        if (!tglsp2b) {
            alert('Silahkan Pilih Tanggal SP2B!');
            exit();
        }
		if (!nket) {
            alert('Silahkan Isi Keterangan!');
            exit();
        }
        if (!ctgl1) {
            alert('Silahkan pilih tanggal awal!');
            exit();
        }
        if (!ctgl2) {
            alert('Silahkan pilih tanggal akhir!');
            exit();
        }
        if (total == 0 || total == '') {
            alert('Rincian SP3B tidak boleh kosong!');
            exit();
        }
		
		
		//simpan Anguz
			$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({nosp2b:nosp2b,kode:kdskpd,tabel:'trhsp2b_blud',field:'no_sp2b'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd_pusk/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1){
						alert("Nomor Telah Dipakai!");
						document.getElementById("nomor").focus();
						exit();
						} 
						if(status_cek==0){
						alert("Nomor Bisa dipakai");
                        
                   
			
			//-----
			
			$(document).ready(function(){
			$.ajax({
				type: "POST",       
				dataType : 'json',         
				 url      : "<?php  echo base_url(); ?>index.php/tukd_pusk/simpan_hsp2b",
				data     : ({nosp2b:nosp2b,nosp3b:nosp3b,ket:nket,tglsp2b:tglsp2b,kd:kdskpd,nm:nmskpd,revisi:revisi,tgl1:ctgl1,tgl2:ctgl2,total1:total}),
				beforeSend:function(xhr){
                $("#loading").dialog('open');
					},
				success:function(data){
				status = data;
				if (status == '0'){
				   $("#loading").dialog('close');
				   alert('Gagal Simpan...!!');
				   exit();
				} else if (status !='0'){ 
				
		        $('#dg1').datagrid('selectAll');
				var rows = $('#dg1').datagrid('getSelections'); 
				for(var i=0;i<rows.length;i++){            
						cidx      = rows[i].idx;
						ckdgiat   = rows[i].kd_sub_kegiatan;
						cnosp3b   = nosp2b;
                        ckdskpd   = rows[i].kd_skpd;
						ckdrek    = rows[i].kd_rek6;
						cnmrek    = rows[i].nm_rek6;
						cnilai    = angka(rows[i].nilai);
						no        = i + 1 ; 
						if (i>0) {
							csql = csql+","+"('"+cnosp3b+"','"+ckdrek+"','"+cnmrek+"','"+cnilai+"','"+ckdskpd+"','"+ckdgiat+"')";
						} else {
							csql = "values('"+cnosp3b+"','"+ckdrek+"','"+cnmrek+"','"+cnilai+"','"+ckdskpd+"','"+ckdgiat+"')";                                            
							}                                             
						}   	                  
						$(document).ready(function(){
							// alert(csql);    
							//exit();
							$.ajax({
								type: "POST",   
								dataType : 'json',                 
								data: ({sql:csql}),
								url: '<?php echo base_url(); ?>/index.php/tukd_pusk/dsimpan_lpj',
								success:function(data){                        
									status = data.pesan;   
									 if (status=='1'){
										$("#loading").dialog('close');
										alert('Data Berhasil Tersimpan...!!!');
                                        section4();
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
		//----
		 }
		}
		});
		});
	
	 }
		
	
    function dsimpan(){
        var a = document.getElementById('no_spp').value;
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({cno_spp:a}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/tukd/dsimpan_spp"            
         });
        });
    } 
    
    
    function detsimpan(){

        var a      = document.getElementById('no_spp').value; 
        var a_hide = document.getElementById('no_spp_hide').value; 
        
        $(document).ready(function(){      
           $.ajax({
           type     : 'POST',
           url      : "<?php  echo base_url(); ?>index.php/tukd/dsimpan_hapus",
           data     : ({cno_spp:a_hide,lcid:a,lcid_h:a_hide}),
           dataType : "json",
           success  : function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Hapus Detail Old');
                            exit();
                        } 
                        }
                        });
        });
        
        // $('#dg1').datagrid('selectAll');
        // var rows = $('#dg1').datagrid('getSelections');
        
        // for(var i=0;i<rows.length;i++){            
            
        //     cidx      = rows[i].idx;
        //     cnobukti1 = rows[i].no_bukti;
        //     ckdgiat   = rows[i].kdkegiatan;
        //     cnmgiat   = rows[i].nmkegiatan;
        //     ckdrek    = rows[i].kdrek5;
        //     cnmrek    = rows[i].nmrek5;
        //     cnilai    = angka(rows[i].nilai1);
                       
        //     no        = i + 1 ;      
            
        //     $(document).ready(function(){      
        //     $.ajax({
        //     type     : 'POST',
        //     url      : "<?php  echo base_url(); ?>index.php/tukd/dsimpan",
        //     data     : ({cno_spp:a,cskpd:kode,cgiat:ckdgiat,crek:ckdrek,ngiat:cnmgiat,nrek:cnmrek,nilai:cnilai,kd:no,no_bukti1:cnobukti1}),
        //     dataType : "json"
        // });
        // });
        // }
        // $("#no_spp_hide").attr("Value",a) ;
        // $('#dg1').edatagrid('unselectAll');
    } 
    
    

        
    
    function kembali(){
        $('#kem').click();
    }                
    
    
     function load_sum_lpj(nomer_sp3b,skpdd){          
        
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd_pusk/jumlah_belanjasp3b",
            data:({no:nomer_sp3b,skpd:skpdd}),
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    
                    $("#rektotal").attr('value',number_format(n['nilai'],2,'.',','));
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
   
    function tombol2(){
       
        if (lcstatus = 'tambah'){
            // document.getElementById('delete').style.visibility= 'hidden';
            // document.getElementById('print').style.visibility= 'hidden';
            $('#delete').remove();
            $('#print').remove();
        }else{
            $('#delete').remove();
             $('#print').remove();
        }
        // alert(lcstatus);
    }
   
    function tombol(status_lpj){
    if (status_lpj=='1') {
        document.getElementById("p1").innerHTML="SP3B SUDAH DI SETUJUI !!";
		document.getElementById("btcair").value="BATAL SETUJU";        
        $('#idcetak').linkbutton('enable');
     } else {
        document.getElementById("p1").innerHTML="";
		document.getElementById("btcair").value="SETUJU";
        $('#idcetak').linkbutton('disable');
	 }
    }	
    
        
    function openWindow(url)
    {
        var vnospp  =  $("#cspp").combogrid("getValue");
         
		        lc  =  "?nomerspp="+vnospp+"&kdskpd="+kode+"&jnsspp="+jns ;
        window.open(url+lc,'_blank');
        window.focus();
    }
    
        
    function detail_trans_3(nomor_sp2b, tglawl, tglakr, kode){
        
        $(function(){
			$('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd_pusk/select_data1_lpj_ag',
                queryParams:({ nosp2b:nomor_sp2b,tglawl:tglawl, tglakr:tglakr,kode:kode}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"false",
                 singleSelect:"true",
                 nowrap:"true",				 
				columns:[[
                    {field:'idx',title:'idx',width:50,align:'left',hidden:'true'},
                    {field:'no_sp3b',title:'No SP3B',width:150,align:'right', hidden:'true'},
                    {field:'no_sp2b',title:'No SP2B',width:150,align:'right', hidden:'true'},
                    {field:'kd_sub_kegiatan',title:'Kd Sub Kegiatan',width:150,align:'right'},
                    {field:'tgl_sp3b',title:'Tgl SP3B',width:150,align:'right', hidden:'true'},
                    {field:'ket',title:'Keterangan',width:150,align:'right', hidden:'true'},
                    {field:'kd_skpd',title:'Kd SKPD',width:150,align:'right', hidden:'true'},
                    {field:'kd_rek6',title:'Kd rek6',width:120,align:'left'},
                    {field:'nm_rek6',title:'Nm Rek6',width:260,align:'right'},                                                                                     
                    {field:'nilai',title:'Nilai',width:200,align:'right'}
				]]	
			});
		});
        }
        

        function detail_trans_kosong(){
        var no_kos = '' ;
        $(function(){
			$('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd_pusk/select_data1_lpj_ag',
                queryParams:({ lpj:no_kos,kdskpd: kode }),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"false",
                 singleSelect:"true",
                 nowrap:"true",
                 columns:[[         
                    {field:'idx',title:'idx',width:50,align:'left',hidden:'true'},
                    {field:'no_sp3b',title:'No SP3B',width:150,align:'right', hidden:'true'},
                    {field:'no_sp2b',title:'No SP2B',width:150,align:'right', hidden:'true'},
                    {field:'kd_sub_kegiatan',title:'Kd Sub Kegiatan',width:150,align:'right'},
                    {field:'tgl_sp3b',title:'Tgl SP3B',width:150,align:'right', hidden:'true'},
                    {field:'ket',title:'Keterangan',width:150,align:'right', hidden:'true'},
                    {field:'kd_skpd',title:'Kd SKPD',width:150,align:'right', hidden:'true'},
                    {field:'kd_rek6',title:'Kd rek6',width:120,align:'left'},
                    {field:'nm_rek6',title:'Nm Rek6',width:260,align:'right'},                                                                                     
                    {field:'nilai',title:'Nilai',width:200,align:'right'}
				]]	
			});
		});
        }
    
   
    
    function hapus(){
        
        var nosp2b = document.getElementById("no_sp2b").value;   
        var kode   = document.getElementById("dn").value;   
                 
            var urll= '<?php echo base_url(); ?>/index.php/tukd_pusk/hapus_sp2b';             			    
         	var del=confirm('Anda yakin akan menghapus SP2B '+nosp2b+
                    '  ?');
				if  (del==true){
					$(document).ready(function(){
                    $.post(urll,({nosp2b:nosp2b}),function(data){
                    status = data;   
                    if(status==1){
                        alert('Data Berhasil Di Hapus');
                    }else{
                        alert('Data Gagal di Hapus');
                    }                    
                    });
                    section4();
                    });				
				}
	}

    function hapus_detail_grid(){
        
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

	
  
    
    function load_data() {
		$('#dg1').datagrid('loadData', []);
        var dtgl1        = $('#dd1').datebox('getValue') ;
        var dtgl2        = $('#dd2').datebox('getValue') ;
        var ntotal_trans = 0;
        
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
                url: '<?php echo base_url(); ?>/index.php/blud/SP2BBludController/load_data_transaksi_sp3b',
                data: ({tgl1:dtgl1,tgl2:dtgl2,kdskpd:kode}),
                dataType:"json",
                success:function(data){                                          
                    $.each(data,function(i,n){                                    
                    // xnobukti = n['no_sp3b'];
                    xnosp2b  = n['no_sp2b'];
                    xtgl     = n['tgl_sp3b']; 
                    xket     = n['keterangan'];                                                                                        
                    xgiat    = n['kd_sub_kegiatan']; 
                    xkdrek5  = n['kd_rek6'];
                    xnmrek5  = n['nm_rek6'];
                    xkdskpd  = n['kd_skpd'];
                    xnilai   = n['nilai'];
                    
                    ntotal_trans = ntotal_trans + angka(xnilai) ;
                    
                    $('#dg1').edatagrid('appendRow',{no_sp2b:xnosp2b,ket:xket,kd_sub_kegiatan:xgiat,kd_rek6:xkdrek5,nm_rek6:xnmrek5,kd_skpd:xkdskpd,nilai:xnilai,idx:i}); 
                    $('#dg1').edatagrid('unselectAll');
                    $('#rektotal').attr('value',number_format(ntotal_trans,2,'.',','));
                    });
                 }
            });
            });   
    }
	
	function cetaktu1(cetak)
        {
			var no_sp2d  = $('#nosp2d').combogrid('getValue');   
            var no_sp2d =no_sp2d.split("/").join("abcdefghij");
			var no_lpj = $('#cspp').combogrid('getValue');   
            var no_lpj = no_lpj.split("/").join("abcdefghij");				
			var skpd   = kode; 
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			var ttd1   = $("#ttd1").combogrid('getValue');
			var ttd2   = $("#ttd2").combogrid('getValue'); 
			if(ctglttd==''){
			alert('Tanggal tidak boleh kosong!');
			exit();
			}
			if(ttd1==''){
				alert('Bendahara Pengeluaran tidak boleh kosong!');
				exit();
			}
			if(ttd2==''){
				alert('Pengguna Anggaran tidak boleh kosong!');
				exit();
			}
			var ttd_1 =ttd1.split(" ").join("a");
			var ttd_2 =ttd2.split(" ").join("a");
			var url    = "<?php echo site_url(); ?>/tukd/cetaklpjtu_ag";  
			window.open(url+'/'+no_sp2d+'/'+ttd_1+'/'+skpd+'/'+cetak+'/'+ctglttd+'/'+ttd_2+'/'+no_lpj+'/LPJ-TU', '_blank');
			window.focus();
        }
		
		function cetaktu2(cetak){
			var no_sp2d  = $('#nosp2d').combogrid('getValue');   
            var no_sp2d =no_sp2d.split("/").join("abcdefghij");
			var no_lpj = $('#cspp').combogrid('getValue');   
            var no_lpj = no_lpj.split("/").join("abcdefghij");	
			var skpd   = kode; 
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			var ttd1   = $("#ttd1").combogrid('getValue');
			var ttd2   = $("#ttd2").combogrid('getValue'); 
			if(ctglttd==''){
			alert('Tanggal tidak boleh kosong!');
			exit();
			}
			if(ttd1==''){
				alert('Bendahara Pengeluaran tidak boleh kosong!');
				exit();
			}
			if(ttd2==''){
				alert('Pengguna Anggaran tidak boleh kosong!');
				exit();
			}
			var ttd_1 =ttd1.split(" ").join("a");
			var ttd_2 =ttd2.split(" ").join("a");
			var url    = "<?php echo site_url(); ?>/tukd/cetaklpjtusptb";  
			window.open(url+'/'+no_sp2d+'/'+ttd_1+'/'+skpd+'/'+cetak+'/'+ctglttd+'/'+ttd_2+'/'+no_lpj+'/LPJ-TU', '_blank');
			window.focus();
        }
	
	function setuju(){
		var cap=document.getElementById("btcair").value;
		if (cap=='SETUJU'){
			simpan_setuju();
			
		}else{
			batal_setuju();
		}
	}
	
	function simpan_setuju(){
		var no_sp3bb = document.getElementById('no_sp3b').value;
        var no_sp2bb = document.getElementById('no_sp2b').value;
        var no_sp2bc = document.getElementById('no_sp2b_tambahan').value;
        var no_sp2bd = no_sp2bb+no_sp2bc;
        var tgl      = $('#dd_sp2b').datebox('getValue');
		$(function(){      
			 $.ajax({
				type: 'POST',
				data: ({no_sp3b:no_sp3bb,kd_skpd:kode,tgl_sah:tgl,no_sp2b:no_sp2bd,number_sp2b:no_sp2bb}),
				dataType:"json",
				url:"<?php echo base_url(); ?>index.php/tukd_pusk/setuju_sp3b",
				success:function(data){
					if (data = 2){
						alert('SP3B telah disetujui');
						document.getElementById("p1").innerHTML="SP3B SUDAH DISETUJUI!!";
						document.getElementById("btcair").value="BATAL SETUJU";
                        $('#idcetak').linkbutton('enable');
					}
				}
			 });
			});
	}
	
	function batal_setuju(){
		var no_sp3bb = document.getElementById('no_sp3b').value;
        var no_sp2bb = document.getElementById('no_sp2b').value;
        var no_sp2bc = document.getElementById('no_sp2b_tambahan').value;
        var no_sp2bd = no_sp2bb+no_sp2bc;
        var tgl      = $('#dd').datebox('getValue');
		$(function(){      
			 $.ajax({
				type: 'POST',
				data: ({no_sp3b:no_sp3bb,kd_skpd:kode,tgl_sah:tgl,no_sp2b:no_sp2bd}),
				dataType:"json",
				url:"<?php echo base_url(); ?>index.php/tukd_pusk/batalsetuju_sp3b",
				success:function(data){
					if (data = 2){
						alert('SP3B telah dibatalkan');
						document.getElementById("p1").innerHTML="SP3B TELAH DIBATALKAN!!";
						document.getElementById("btcair").value="SETUJU";
                        $('#idcetak').linkbutton('disable');
                        $("#no_sp2b").attr("value",'');
                        $("#no_simpan_sp2b").attr("value",'');
                        $("#no_sp2b_tambahan").attr("value",'');
                        section4();
					}
				}
			 });
			});
	}

  function cetaksp3b(ctk)
        { 
      var nosp3b = document.getElementById('no_sp3b').value;//$('#cmb_sp3b').combogrid('getValue');
      nosp3b = nosp3b.split("/").join("hhh");
      var skpd   = $('#dn').combogrid('getValue'); 
      var bulan   =  document.getElementById('bulan').value;
      var ctglttd = $('#dd').datebox('getValue');
      var pusk = $('#dn').combogrid('getValue');        
      var  ttd2 = $('#ttd1').combogrid('getValue');
        ttd2 = ttd2.split(" ").join("123456789");
      var atas   =  "15";
      var bawah   =  "15";
      var kanan   =  "15";
      var kiri   =  "15";
      var sp3b = "Cetak SP3B";

      var url    = "<?php echo site_url(); ?>tukd_pusk/cetak_sp2b_fktp";  
      if(bulan==0){
      alert('Pilih Bulan dulu')
      exit()
      }
      if(ctglttd==''){
      alert('Pilih Tanggal tanda tangan dulu')
      exit()
      }     
      if(ttd2==''){
      alert('Pilih Pengguna Anggaran dulu')
      exit()
      }
      if(pusk==''){
      alert('Pilih FKTP dulu')
      exit()
      }
      
      window.open(url+'/'+skpd+'/'+bulan+'/'+ctk+'/'+pusk+'/'+ttd2+'/'+ctglttd+'/'+atas+'/'+bawah+'/'+kiri+'/'+kanan+'/'+nosp3b+'/'+sp3b, '_blank');
      window.focus();
        }	
	function validate_jenis() {
		var jns   =  document.getElementById('jenis').value;
		 if (jns=='2') {
			$("#div1").show();
		} else {
			$("#div1").hide();
		}         	
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
<div id="accordion" style="width:100%;height:1000px;" >
<h3><a href="#" id="section4" onclick="javascript:$('#spp').edatagrid('reload')">List SP2B </a></h3>
<div>
    <p align="right">  
         <button class="button" onclick="javascript:section1();kosong();"><i class="fa fa-plus"></i> Tambah</button>
        <!-- <input type="text" value="" id="txtcari" class="input" placeholder="Pencarian: Ketik dan enter" onkeyup="javascript:cari();"/> -->
        <table id="spp" title="List SP2B" style="height:450px;" >  
        </table>
    </p> 
</div>

<h3><a href="#" id="section1">Input SP2B</a></h3>

   <div  style="height: 350px;">
   <p id="p1" style="font-size: x-large;color: red;"></p>
   <p>

<fieldset style="width:100%;height:650px;border-color:white;border-style:hidden;border-spacing:0;padding:0;">            
<table border='0' style="font-size:11px; width: 100%" >
	
   <!-- <INPUT TYPE="button" class="button" name="btback" id="btback" VALUE="KEMBALI" ONCLICK="javascript:section4();" style="height:40px;width:120px">			   	 
   <INPUT TYPE="button" class="button" name="btcair" id="btcair" VALUE="SETUJU" ONCLICK="setuju()" style="height:40px;width:120px">		 -->
	
   <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
   <td  width='20%'>No SP2B</td>
   <td width='80%'><input type="text"  maxlength="23" name="no_sp2b" id="no_sp2b" style="width:300px" value ="/SP2B/RSUD-BLUD/2023"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   Tanggal SP2B 
   &nbsp;<input id="dd_sp2b" name="dd_sp2b" type="text" style="width:95px"/>
   <font color="red"> 002/SP2B/RSUD-BLUD/2023</font>
   </td>
   
   </tr>
   <!-- <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
   <td  width='20%'>No SP3B</td>
   <td width='80%'><input type="text" maxlength="23" value ="/SP3B/RSUD-BLUD/2023" name="no_sp3b" id="no_sp3b" style="width:300px" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   Tanggal SP3B
   &nbsp;<input id="dd" name="dd" type="text" style="width:95px"/>
   <font color="red"> XXX/SP3B/RSUD-BLUD/2023</font>
   </td> -->
   	<tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
   <td width='20%'>SKPD</td>
   <td width="80%">     
        <input id="dn" name="dn"  readonly="true" style="width:200px; border: 0; " /> <input id="nmskpd" name="nmskpd"  readonly="true" style="width:200px; border: 0; " />                   
        </td> 
	</tr>
   <!-- <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
   <td width='10%'>No. LPJ JKN</td>
   <td width='80%'><input type="text" name="sp2d" id="sp2d"  style="width:250px" disabled />
   <input type="hidden" name="hidd_sp2d" id="hidd_sp2d"  style="width:225px" />
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="tgl_sp2d" name="tgl_sp2d" type="text" style="width:95px;border:0" />
   </td>
   </tr>   -->
  <tr>
  
   
      <td width='20%'  style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">KETERANGAN</td>
     <td width='31%' style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"><textarea name="keterangan" id="keterangan" style="width: 400px;"></textarea></td>
  
  </tr>
    <tr>

    <td width='20%' style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"></td>
     <td width='31%' style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"><input type="checkbox" name="revisi" id="revisi" style="width: 0px;"><label></label></td>
    </tr>
   
   
 <tr style="height: 30px;">
	<td colspan="4">
		<div align="right">
			<button class="button button-hijau" id="save" name="save" onclick="javascript:simpan();"><i class=" fa fa-print"></i> Simpan</button>
            <button class="button button-hijau" id="delete" name="delete" onclick="javascript:hapus();"><i class=" fa fa-print"></i> Hapus</button>
            <button class="button button-hijau" id="print" name="print" onclick="javascript:cetak();"><i class=" fa fa-print" ></i> Cetak</button>
            <button class="button button-hijau" id="exit" name="exit" onclick="javascript:section4()"><i class=" fa fa-arrow-left  "></i> Kembali</button>
		</div>
	</td>
 </tr>
 <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"> 
  
  <td colspan='6' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tanggal Transaksi</td>  
</tr>

<tr style="height: 10px;">
  <td colspan='4' >
  <input id="dd1" name="dd1" type="text" style="width:95px" />&nbsp;S/D&nbsp;<input id="dd2" name="dd2" type="text" style="width:95px"/>
  &nbsp;&nbsp;&nbsp;&nbsp;<a id="load" style="width:70px" class="easyui-linkbutton" iconCls="icon-add" plain="true"  onclick="javascript:load_data();" >Tampil</a>
  &nbsp;&nbsp;&nbsp;&nbsp;<a id="load_kosong" style="width:70px" class="easyui-linkbutton" iconCls="icon-remove" plain="true"  onclick="javascript:detail_trans_kosong();" >Kosong</a>
  </td>  
</tr>
  </table>
   
        <table id="dg1" title="Detail SP3B" style="width:850px;height:200px;" >  
        </table>
        
         
        <table border='0' style="width:100%;height:5%;"> 
             <td width='34%'></td>
             <td width='28%'><input class="right" type="hidden" name="rektotal1" id="rektotal1"  style="width:140px" align="right" readonly="true" ></td>
             <td width='10%'><B>Total Saldo</B></td>
             <td width='28%'><input class="right" type="text" name="rektotal" id="rektotal"  style="width:200px" align="right" readonly="true" ></td>
        </table>

   </p>

</fieldset>     
</div>
</div>
</div> 
			<div id="loading" title="Loading...">
			<table align="center">
			<tr align="center"><td><img id="search1" height="50px" width="50px" src="<?php echo base_url();?>/image/loadingBig.gif"  /></td></tr>
			<tr><td>Loading...</td></tr>
			</table>
			</div>

<div id="dialog-modal" title="CETAK SP2B">	  
    <fieldset>
        <table>
            <tr>            
                <td width="200px" >NO SP2B:</td>
                <td><input id="cspp" name="cspp" style="width: 200px; " disabled/></td>
            </tr>
            <tr >
				<td>Penandatangan:</td>
				<td><input type="text" id="ttd1" style="width:200px" /></td>&nbsp;&nbsp;
				<td><input type="text" id="nm_ttd1" readonly="true" style="width:150px;border:0" /></td>
			</tr>			
			<tr>
				<td colspan="3">
					<div id="div1">
						<table style="width:100%;" border="0">
							<td width="200px"></td>
							<td><input id="giat_print" style="width: 200px;"/></td>
						</table>
					</div>
				</td>
			</tr>
        </table>  
    </fieldset>
	<div>
	</div>
	<center>
	<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetakup(0);">Cetak Layar</a>
	<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetakup(1);">Cetak PDF</a>      
    <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>
</div>

 	
<div id="dialog-modal-tr" title="">
    <p class="validateTips">Pilih Nomor Transaksi</p> 
    <fieldset>
    <table align="center" style="width:100%;" border="1">
            
            <tr>
                <td>1. No Transaksi</td>
                <td></td>
                <td><input id="no1" name="no1" style="width: 320px;" />  </td>                            
            </tr>
            
            <tr>
                <td>2. No Transaksi</td>
                <td></td>
                <td><input id="no2" name="no2" style="width: 320px;" />  </td>                            
            </tr>
            
            <tr>
                <td>3. No Transaksi</td>
                <td></td>
                <td><input id="no3" name="no3" style="width: 320px;" />  </td>                            
            </tr>
            
            <tr>
                <td>4. No Transaksi</td>
                <td></td>
                <td><input id="no4" name="no4" style="width: 320px;" />  </td>                            
            </tr>
            
            <tr>
                <td>5. No Transaksi</td>
                <td></td>
                <td><input id="no5" name="no5" style="width: 320px;" />  </td>                            
            </tr>
            
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>                            
            </tr>
            
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>                            
            </tr>
            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:detail_trans_2();">Pilih</a>
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar_no();">Kembali</a>
                </td>                
            </tr>
        
    </table>       
    </fieldset>
</div>
</body>
</html>