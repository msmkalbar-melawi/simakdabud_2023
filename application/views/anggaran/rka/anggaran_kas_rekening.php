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

    var kode     = '';
    var kegiatan = '';
		
    $(document).ready(function() {
            $("#accordion").accordion();
			 //get_skpd();
        });
        
    
    
    $(function(){
        
        
        $(function(){
            $('#cc').combogrid({  
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
                kode = rowData.kd_skpd;
                sta  = rowData.statu;
                giat();
            }  
            }); 
            });     
    
    
          $(function(){  
            $('#ck').combogrid({  
                panelWidth:700,  
                idField:'kd_kegiatan',  
                textField:'kd_kegiatan',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/rka/load_giat',  
                columns:[[  
                    {field:'kd_kegiatan',title:'Kode Kegiatan',width:130},  
                    {field:'nm_kegiatan',title:'Nama Kegiatan',width:570}    
                ]]  
            });          
            }); 
     
     
     
         $(function(){ 
         $('#dg').edatagrid({
    		url: '<?php echo base_url(); ?>/index.php/rka/select_rka',
            idField:'id',
            toolbar:"#toolbar",              
            rownumbers:"true", 
            fitColumns:"true",
            singleSelect:"true",
            columns:[[
        	    {field:'kd_rek5',
        		title:'Kode Rekening',
        		width:50},
                {field:'nm_rek5',
        		title:'Nama Rekening',
        		width:170},
                {field:'nilai',
        		title:'Nilai',
        		width:100,
                align:"right"}
            ]]
        });
        });
    
    
    });     
    
    
    
    function get_skpd()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#cc").attr("value",data.kd_skpd);
        								kode = data.kd_skpd;
                                        sta  = data.statu;
                                        giat();
        							  }                                     
        	});
        }
      
     


    function giat(){
        
          var kode = document.getElementById('cc').value ;

          $(function(){  
            $('#ck').combogrid({  
                panelWidth:700,  
                idField:'kd_kegiatan',  
                textField:'kd_kegiatan',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/rka/load_giat/'+kode,
                onSelect:function(rowIndex,rowData){
                    kegiatan = rowData.kd_kegiatan;
                    total    = rowData.total;
                    $("#ck2").attr("value",kegiatan);
                    rek();
                    $("#dg").datagrid('unselectAll');
                                        
                }
            });          
         }); 

     }
     
     
     function rek(){
        
        var kegiatan = document.getElementById('ck2').value ;
        
         $(function(){ 
         $('#dg').edatagrid({
    		url: '<?php echo base_url(); ?>/index.php/rka/select_rka/'+kegiatan,
            idField:'id',
            toolbar:"#toolbar",              
            rownumbers:"true", 
            fitColumns:"true",
            singleSelect:"true",
            onSelect:function(rowIndex,rowData){
                
                var zrek5   = rowData.kd_rek5 ;
                var znmrek5 = rowData.nm_rek5 ;
                var znilai  = rowData.nilai ;
                
                $("#rekening").attr("value",zrek5);
                $("#nmrekening").attr("value",znmrek5);
                $("#jumlah").attr("value",znilai);  
                
                load(zrek5);
            },
            columns:[[
        	    {field:'kd_rek5',
        		title:'Kode Rekening',
        		width:50},
                {field:'nm_rek5',
        		title:'Nama Rekening',
        		width:170},
                {field:'nilai',
        		title:'Nilai',
        		width:100,
                align:"right"}
            ]]
            
        });
        });
     }
     
     
     function section1(){
         $(document).ready(function(){    
             $('#section1').click();  
         });
         
         $("#dg").datagrid("unselectAll");
         
         $("#rekening").attr("value",'');
         $("#nmrekening").attr("value",'');
         $("#jumlah").attr("value",number_format(0,2,'.',','));
         
         kosongkan();
         
     }
     

     function section2(){
        
         var x = document.getElementById('rekening').value ;
         
         if ( x==''){
            alert("Pilih Rekening Terlebih Dahulu...!!!");
            exit();
         }
         
         $(document).ready(function(){    
             $('#section2').click();                                               
         });
     }
     

    
    /*
	function hitung(){    
       
        var jumlah = document.getElementById('jumlah').value;
        
        var a = document.getElementById('jan').value;
        var b = document.getElementById('feb').value;
        var c = document.getElementById('mar').value; 
        var d = document.getElementById('apr').value;
        var e = document.getElementById('mei').value;
        var f = document.getElementById('jun').value;
        var g = document.getElementById('jul').value;
        var h = document.getElementById('ags').value;
        var i = document.getElementById('sep').value; 
        var j = document.getElementById('okt').value;
        var k = document.getElementById('nov').value;
        var l = document.getElementById('des').value;  
        tr1=eval(a+'+'+b+'+'+c);
        tr2=eval(d+'+'+e+'+'+f);
        tr3=eval(g+'+'+h+'+'+i);
        tr4=eval(j+'+'+k+'+'+l);
        $("#tr1").attr("value",tr1);  
        $("#tr2").attr("value",tr2);
        $("#tr3").attr("value",tr3);
        $("#tr4").attr("value",tr4);
        kas=tr1+tr2+tr3+tr4;
        $("#kas").attr("value",kas);
        selisih=jumlah-kas;
        $("#selisih").attr("value",selisih);
        if (selisih < 0){
            alert('Total Anggaran Kas lebih Besar Dari Anggaran Kegiatan....!!!!');        
        }
    }
    */
    
    
     
	
    
	function hitung(){    
        
        var jumlah = angka(document.getElementById('jumlah').value);
        var a = angka(document.getElementById('jan').value);
        var b = angka(document.getElementById('feb').value);
        var c = angka(document.getElementById('mar').value); 
        var d = angka(document.getElementById('apr').value);
        var e = angka(document.getElementById('mei').value);
        var f = angka(document.getElementById('jun').value);
        var g = angka(document.getElementById('jul').value);
        var h = angka(document.getElementById('ags').value);
        var i = angka(document.getElementById('sep').value); 
        var j = angka(document.getElementById('okt').value);
        var k = angka(document.getElementById('nov').value);
        var l = angka(document.getElementById('des').value);  
        tr1=eval(a+'+'+b+'+'+c);
        tr2=eval(d+'+'+e+'+'+f);
        tr3=eval(g+'+'+h+'+'+i);
        tr4=eval(j+'+'+k+'+'+l);
        
        kas=tr1+tr2+tr3+tr4;
        selisih=jumlah-kas;
        
        if (selisih < 0){
            alert('Total Anggaran Kas lebih Besar Dari Nilai Rekeningnya....!!!!');        
        }
        
        $("#tr1").attr("value",number_format(tr1,2,".",","));  
        $("#tr2").attr("value",number_format(tr2,2,".",","));
        $("#tr3").attr("value",number_format(tr3,2,".",","));
        $("#tr4").attr("value",number_format(tr4,2,".",","));
        
        $("#kas").attr("value",number_format(kas,2,".",","));
        $("#selisih").attr("value",number_format(selisih,2,".",","));
        
    }
    
     
    
    
    function bagi(){
        
        var total = angka(document.getElementById('jumlah').value);
		var tot   = angka(document.getElementById('jumlah').value);
		var rata  = Math.round(total/12);

        $("#jan").attr("value",number_format(rata,2,'.',','));
        $("#feb").attr("value",number_format(rata,2,'.',','));
        $("#mar").attr("value",number_format(rata,2,'.',','));
        $("#apr").attr("value",number_format(rata,2,'.',','));
        $("#mei").attr("value",number_format(rata,2,'.',','));
        $("#jun").attr("value",number_format(rata,2,'.',','));
        $("#jul").attr("value",number_format(rata,2,'.',','));
        $("#ags").attr("value",number_format(rata,2,'.',','));
        $("#sep").attr("value",number_format(rata,2,'.',','));
        $("#okt").attr("value",number_format(rata,2,'.',','));
        $("#nov").attr("value",number_format(rata,2,'.',','));
        $("#des").attr("value",number_format(rata,2,'.',','));
        $("#tr1").attr("value",number_format(rata*3,2,'.',','));
        $("#tr2").attr("value",number_format(rata*3,2,'.',','));
        $("#tr3").attr("value",number_format(rata*3,2,'.',','));
        $("#tr4").attr("value",number_format(rata*3,2,'.',','));
        $("#kas").attr("value",number_format(tot,2,'.',','));
        $("#selisih").attr("value",number_format(0,2,'.',','));		
	}

    
    function kosongkan(){
        
        $("#jan").attr("value",number_format(0,2,'.',','));
        $("#feb").attr("value",number_format(0,2,'.',','));
        $("#mar").attr("value",number_format(0,2,'.',','));
        $("#apr").attr("value",number_format(0,2,'.',','));
        $("#mei").attr("value",number_format(0,2,'.',','));
        $("#jun").attr("value",number_format(0,2,'.',','));
        $("#jul").attr("value",number_format(0,2,'.',','));
        $("#ags").attr("value",number_format(0,2,'.',','));
        $("#sep").attr("value",number_format(0,2,'.',','));
        $("#okt").attr("value",number_format(0,2,'.',','));
        $("#nov").attr("value",number_format(0,2,'.',','));
        $("#des").attr("value",number_format(0,2,'.',','));
        $("#tr1").attr("value",number_format(0,2,'.',','));
        $("#tr2").attr("value",number_format(0,2,'.',','));
        $("#tr3").attr("value",number_format(0,2,'.',','));
        $("#tr4").attr("value",number_format(0,2,'.',','));
        $("#kas").attr("value",number_format(0,2,'.',','));
        $("#selisih").attr("value",number_format(0,2,'.',','));
        
     }      
  
    
    function simpan(){

        var a = angka(document.getElementById('jan').value);
        var b = angka(document.getElementById('feb').value);
        var c = angka(document.getElementById('mar').value); 
        var d = angka(document.getElementById('apr').value);
        var e = angka(document.getElementById('mei').value);
        var f = angka(document.getElementById('jun').value);
        var g = angka(document.getElementById('jul').value);
        var h = angka(document.getElementById('ags').value);
        var i = angka(document.getElementById('sep').value); 
        var j = angka(document.getElementById('okt').value);
        var k = angka(document.getElementById('nov').value);
        var l = angka(document.getElementById('des').value);  
        var m = angka(document.getElementById('tr1').value);
        var n = angka(document.getElementById('tr2').value);
        var o = angka(document.getElementById('tr3').value);
        var p = angka(document.getElementById('tr4').value);
        
        var rek5 = document.getElementById('rekening').value ;
        
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({rek:rek5,csts:'susun',cskpd:kode,cgiat:kegiatan,jan:a,feb:b,mar:c,apr:d,mei:e,jun:f,jul:g,ags:h,sep:i,okt:j,nov:k,des:l,tr1:m,tr2:n,tr3:o,tr4:p}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/rka/simpan_trskpd_rek",
            success:function(data){
                if (data = 1){
                    alert('Data Berhasil Tersimpan');
                }else{
                    alert('Data Gagal Berhasil Tersimpan');
                }
            }
         });
        });
    
    }
    
    
    /*
    function load(){
        
        var kegi = document.getElementById('ck2').value ;
        
        alert(kegi);
        
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({p:kegi,jns:'susun'}),
            url:"<?php echo base_url(); ?>index.php/rka/load_trdskpd",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    
                    bulan = n['bulan'];
                    
                     switch (bulan) {
                        case '1':
                             $("#jan").attr("value",n['nilai']);
                        break;
                        case '2':
                             $("#feb").attr("value",n['nilai']);
                        break;
                        case '3':
                             $("#mar").attr("value",n['nilai']);
                        break;
                        case '4':
                             $("#apr").attr("value",n['nilai']);
                        break;
                        case '5':
                             $("#mei").attr("value",n['nilai']);
                        break;
                        case '6':
                             $("#jun").attr("value",n['nilai']);
                        break;
                        case '7':
                             $("#jul").attr("value",n['nilai']);
                        break;
                        case '8':
                             $("#ags").attr("value",n['nilai']);
                        break;
                        case '9':
                             $("#sep").attr("value",n['nilai']);
                        break;
                        case '10':
                             $("#okt").attr("value",n['nilai']);
                        break;
                        case '11':
                             $("#nov").attr("value",n['nilai']);
                        break;
                        case '12':
                             $("#des").attr("value",n['nilai']);
                        break;
                        default:
                             $("#jan").attr("value",0);
                     }
                     hitung();
                });
                format_angka();
            }
         });
        });
    }
    */
    
    
    
        
    function load(reke){
        
        kosongkan();
        var kegi  = document.getElementById('ck2').value ;
        var reke5 = reke ;
        
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({p:kegi,jns:'susun',rek:reke5}),
            url:"<?php echo base_url(); ?>index.php/rka/load_trdskpd_rek",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    
                    bulan = n['bulan'];
                    
                        if ( bulan == '1'){
                             $("#jan").attr("value",n['nilai']);
                        } else if ( bulan == '2'){
                             $("#feb").attr("value",n['nilai']);
                        } else if ( bulan == '3'){    
                             $("#mar").attr("value",n['nilai']);
                        } else if ( bulan == '4'){     
                             $("#apr").attr("value",n['nilai']);
                        } else if ( bulan == '5'){    
                             $("#mei").attr("value",n['nilai']);
                        } else if ( bulan == '6'){     
                             $("#jun").attr("value",n['nilai']);
                        } else if ( bulan == '7'){     
                             $("#jul").attr("value",n['nilai']);
                        } else if ( bulan == '8'){     
                             $("#ags").attr("value",n['nilai']);
                        } else if ( bulan == '9'){    
                             $("#sep").attr("value",n['nilai']);
                        } else if ( bulan == '10'){     
                             $("#okt").attr("value",n['nilai']);
                        } else if ( bulan == '11'){     
                             $("#nov").attr("value",n['nilai']);
                        } else if ( bulan == '12'){     
                             $("#des").attr("value",n['nilai']);
                        } 
                        hitung();
                     
                });
                format_angka();
            }
         });
        });
    }
    

    
    
    function format_angka(){
    
        var a = angka(document.getElementById('jan').value);
        var b = angka(document.getElementById('feb').value);
        var c = angka(document.getElementById('mar').value); 
        var d = angka(document.getElementById('apr').value);
        var e = angka(document.getElementById('mei').value);
        var f = angka(document.getElementById('jun').value);
        var g = angka(document.getElementById('jul').value);
        var h = angka(document.getElementById('ags').value);
        var i = angka(document.getElementById('sep').value); 
        var j = angka(document.getElementById('okt').value);
        var k = angka(document.getElementById('nov').value);
        var l = angka(document.getElementById('des').value);  
        var m = angka(document.getElementById('tr1').value);
        var n = angka(document.getElementById('tr2').value);
        var o = angka(document.getElementById('tr3').value);
        var p = angka(document.getElementById('tr4').value);
        
        var q = angka(document.getElementById('kas').value);
        var r = angka(document.getElementById('selisih').value);
        var s = angka(document.getElementById('jumlah').value);

        $("#jan").attr("value",number_format(a,2,".",","));
        $("#feb").attr("value",number_format(b,2,".",","));
        $("#mar").attr("value",number_format(c,2,".",","));
        $("#apr").attr("value",number_format(d,2,".",","));
        $("#mei").attr("value",number_format(e,2,".",","));
        $("#jun").attr("value",number_format(f,2,".",","));
        $("#jul").attr("value",number_format(g,2,".",","));
        $("#ags").attr("value",number_format(h,2,".",","));
        $("#sep").attr("value",number_format(i,2,".",","));
        $("#okt").attr("value",number_format(j,2,".",","));
        $("#nov").attr("value",number_format(k,2,".",","));
        $("#des").attr("value",number_format(l,2,".",","));
        
        $("#tr1").attr("value",number_format(m,2,".",","));
        $("#tr2").attr("value",number_format(n,2,".",","));
        $("#tr3").attr("value",number_format(o,2,".",","));
        $("#tr4").attr("value",number_format(p,2,".",","));
        
        $("#kas").attr("value",number_format(q,2,'.',','));
        $("#selisih").attr("value",number_format(r,2,'.',','));
        $("#jumlah").attr("value",number_format(s,2,'.',','));
    }
    
    
    function unformat_angka(){
    
        var a = angka(document.getElementById('jan').value);
        var b = angka(document.getElementById('feb').value);
        var c = angka(document.getElementById('mar').value); 
        var d = angka(document.getElementById('apr').value);
        var e = angka(document.getElementById('mei').value);
        var f = angka(document.getElementById('jun').value);
        var g = angka(document.getElementById('jul').value);
        var h = angka(document.getElementById('ags').value);
        var i = angka(document.getElementById('sep').value); 
        var j = angka(document.getElementById('okt').value);
        var k = angka(document.getElementById('nov').value);
        var l = angka(document.getElementById('des').value);  
        var m = angka(document.getElementById('tr1').value);
        var n = angka(document.getElementById('tr2').value);
        var o = angka(document.getElementById('tr3').value);
        var p = angka(document.getElementById('tr4').value);
        
        var q = angka(document.getElementById('kas').value);
        var r = angka(document.getElementById('selisih').value);
        var s = angka(document.getElementById('jumlah').value);

        $("#jan").attr("value",a);
        $("#feb").attr("value",b);
        $("#mar").attr("value",c);
        $("#apr").attr("value",d);
        $("#mei").attr("value",e);
        $("#jun").attr("value",f);
        $("#jul").attr("value",g);
        $("#ags").attr("value",h);
        $("#sep").attr("value",i);
        $("#okt").attr("value",j);
        $("#nov").attr("value",k);
        $("#des").attr("value",l);
        
        $("#tr1").attr("value",m);
        $("#tr2").attr("value",n);
        $("#tr3").attr("value",o);
        $("#tr4").attr("value",p);
        
        $("#kas").attr("value",q);
        $("#selisih").attr("value",r);
        $("#jumlah").attr("value",s);
    }
    
    function section(){
            var reke  = kdrek;
            $(document).ready(function(){
				$('#section2').click();
            });
            load(reke);
    }
    
    
    function enter(ckey,_cid){
        if (ckey==13)
        	{    	       	       	    	   
        	   document.getElementById(_cid).focus();
        	}     
    }
    
    </script>

</head>
<body>



<div id="content"> 
   
<div id="accordion">
<h3><a href="#" id="section1">Anggaran Kas</a></h3>
   <div  style="height: 350px;">
   <p>
        <h3>S K P D&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="cc" name="skpd" style="width: 395px;" /> </h3>
        <h3>KEGIATAN&nbsp;&nbsp;&nbsp;<input id="ck" name="kegiatan" style="width: 400px;"  /> <input id="ck2" name="kegiatan2" style="width: 150px;" type="hidden" /></h3>
        <br /><input type="submit" name="submit" value="Input Anggaran Kas" onclick="javascript:section2();"/><br /><br />
        <table id="dg" title="Rekening Rencana Kegiatan Anggaran" style="width:870px;height:350px;" >  
        </table>  
   </p>
    </div>
    
<h3><a href="#" id="section2"></a></h3>
    <div>
    <p>
    <div class="result">
    
        <table align="center" border='2' style="border-spacing:20px;padding:20px;width:590px;">
            <tr style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-style:hidden;">
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-style:hidden;">
                <input type="text" id="rekening"   name="rekening"   style="text-align:left;width:100px;" readonly="true" />
                <input type="text" id="nmrekening" name="nmrekening" style="text-align:left;width:460px;" readonly="true" />
                </td>
            </tr>
        </table>     
    
    
        <table align="center" border='2' style="border-spacing:20px;padding:20px;">
            <tr style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;">
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><b>Total Anggaran</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;" width="30%"><input type="text" disabled="true" id="jumlah" name="jumlah" style="text-align: right;" /></td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"></td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;"></td>
            </tr>
            <tr style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;">
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;">&nbsp;</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"></td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"></td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;"></td>
            </tr>
            <tr style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;">
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><b>Januari</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><input type="text" id="jan" name="jan" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"  /></td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><b>April</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;"><input type="text" id="apr" name="apr" value="0"  onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
            </tr>
            <tr style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;">
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><b>Februari</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><input type="text" id="feb" name="feb" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><b>Mei</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;"><input type="text" id="mei" name="mei" value="0"  onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
            </tr>
            <tr style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;">
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><b>Maret</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><input type="text" id="mar" name="mar" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><b>Juni</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;"><input type="text" id="jun" name="jun" value="0"  onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
            </tr>
            <tr style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;">
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><b>Triwulan I</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><input type="text" disabled="true" align="right" id="tr1" name="tr1" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><b>Triwulan II</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;"><input type="text" disabled="true" align="right" id="tr2" name="tr2" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
            </tr>
            <tr style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;">
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;">&nbsp;</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"></td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"></td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;"></td>
            </tr>
            <tr style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;">
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><b>Juli</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><input type="text" id="jul" name="jul" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><b>Oktober</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;"><input type="text" id="okt" name="okt" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
            </tr>
            <tr style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;">
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><b>Agustus</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><input type="text" id="ags" name="ags" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><b>November</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;"><input type="text" id="nov" name="nov" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
            </tr>
            <tr style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;">
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><b>September</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><input type="text" id="sep" name="sep" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><b>Desember</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;"><input type="text" id="des" name="des" value="0" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
            </tr>
            <tr style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;">
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><b>Triwulan III</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><input type="text" disabled="true" id="tr3" name="tr3" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><b>Triwulan IV</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;"><input type="text" disabled="true" id="tr4" name="tr4" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
            </tr>
            <tr style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;">
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;">&nbsp;</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"></td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"></td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;"></td>
            </tr>
            <tr style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;">
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><b>Total Anggaran Kas</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><input type="text" disabled="true" id="kas" name="kas" style="text-align: right;"/></td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"><b>Selisih</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;"><input type="text" disabled="true" id="selisih" name="selisih" style="text-align: right;"/></td>
            </tr>

            <tr style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;">
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;">&nbsp;</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"></td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;"></td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;"></td>
            </tr>
            
            <tr style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;">
                <td colspan="4" align="center" style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-style:hidden;"><input type="submit" name="submit" value="Simpan" onclick="javascript:simpan();"/>
                    <input type="submit" name="submit" value="Kosongkan" onclick="javascript:kosongkan();"/>
                    <input type="submit" name="submit" value="Bagi Rata" onclick="javascript:bagi();"/>
                    <input type="submit" name="submit" value="Kembali" onclick="javascript:section1();"/></td>
            </tr>

            <tr style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-color:black;">
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-color:black;border-right-style:hidden;">&nbsp;</td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-color:black;border-right-style:hidden;"></td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-color:black;border-right-style:hidden;"></td>
                <td style="border-spacing:5px;padding:5px 5px 5px 5px;border-collapse:collapse;width:110px;border-bottom-color:black;"></td>
            </tr>
                         
        </table>
        </div>
    </p> 
    </div>   
</div>
</div>  	
</body>
</html>