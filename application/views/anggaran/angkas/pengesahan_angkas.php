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
           url:'<?php echo base_url(); ?>index.php/rka_penetapan/skpd',  
           columns:[[  
               {field:'kd_skpd',title:'Kode SKPD',width:100},  
               {field:'nm_skpd',title:'Nama SKPD',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               kd = rowData.kd_skpd;               
               $("#nmskpd").attr("value",rowData.nm_skpd.toUpperCase());                
           }  
       });
       

     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/rka_ro/load_pengesahan_angkas',
         idField:'id',            
          rownumbers:"true", 
          fitColumns:"true",
          singleSelect:"true",
          autoRowHeight:"false",
          pagination:"true",
          nowrap:"true",
        loadMsg:"Tunggu Sebentar....!!",                      
        columns:[[
            {field:'kd_skpd',
            title:'Kode SKPD',
            width:200,
            align:"center"},
            {field:'nm_skpd',
            title:'Nama SKPD',
            width:500,
            align:"left"}
           
        ]],
        
        onSelect:function(rowIndex,rowData){
            ckd_skpd              = rowData.kd_skpd;
            cnm_skpd              = rowData.nm_skpd;
            cmurni                = rowData.murni;
            cmurni_geser1         = rowData.murni_geser1; 
            cmurni_geser2         = rowData.cmurni_geser2;
            cmurni_geser3         = rowData.murni_geser3; 
            cmurni_geser4         = rowData.murni_geser4; 
            cmurni_geser5         = rowData.murni_geser5; 
            csempurna1            = rowData.sempurna1;
            csempurna1_geser1     = rowData.sempurna1_geser1; 
            csempurna1_geser2     = rowData.sempurna1_geser2; 
            csempurna1_geser3     = rowData.sempurna1_geser3; 
            csempurna1_geser4     = rowData.sempurna1_geser4; 
            csempurna1_geser5     = rowData.sempurna1_geser5; 
            csempurna2            = rowData.sempurna2;
            csempurna2_geser1     = rowData.sempurna2_geser1; 
            csempurna2_geser2     = rowData.sempurna2_geser2; 
            csempurna2_geser3     = rowData.sempurna2_geser3; 
            csempurna2_geser4     = rowData.sempurna2_geser4; 
            csempurna2_geser5     = rowData.sempurna2_geser5; 
            csempurna3            = rowData.sempurna3;
            csempurna3_geser1     = rowData.sempurna3_geser1; 
            csempurna3_geser2     = rowData.sempurna3_geser2; 
            csempurna3_geser3     = rowData.sempurna3_geser3; 
            csempurna3_geser4     = rowData.sempurna3_geser4; 
            csempurna3_geser5     = rowData.sempurna3_geser5; 
            csempurna4            = rowData.sempurna4;
            csempurna4_geser1     = rowData.sempurna4_geser1; 
            csempurna4_geser2     = rowData.sempurna4_geser2; 
            csempurna4_geser3     = rowData.sempurna4_geser3; 
            csempurna4_geser4     = rowData.sempurna4_geser4; 
            csempurna4_geser5     = rowData.sempurna4_geser5; 
            csempurna5            = rowData.sempurna5;
            csempurna5_geser1     = rowData.sempurna5_geser1; 
            csempurna5_geser2     = rowData.sempurna5_geser2; 
            csempurna5_geser3     = rowData.sempurna5_geser3; 
            csempurna5_geser4     = rowData.sempurna5_geser4; 
            csempurna5_geser5     = rowData.sempurna5_geser5; 
            cubah                 = rowData.ubah; 
            cubah1                = rowData.ubah1;
            cubah2                = rowData.ubah2;
            cubah3                = rowData.ubah3;
            cubah4                = rowData.ubah4;
            cubah5                = rowData.ubah5;

          get(ckd_skpd,cnm_skpd,cmurni,cmurni_geser1,cmurni_geser2,cmurni_geser3,cmurni_geser4,cmurni_geser5,csempurna1,csempurna1_geser1,csempurna1_geser2,csempurna1_geser3,csempurna1_geser4,csempurna1_geser5,csempurna2,csempurna2_geser1,csempurna2_geser2,csempurna2_geser3,csempurna2_geser4,csempurna2_geser5,csempurna3,csempurna3_geser1,csempurna3_geser2,csempurna3_geser3,csempurna3_geser4,csempurna3_geser5,csempurna4,csempurna4_geser1,csempurna4_geser2,csempurna4_geser3,csempurna4_geser4,csempurna4_geser5,csempurna5,csempurna5_geser1,csempurna5_geser2,csempurna5_geser3,csempurna5_geser4,csempurna5_geser5,cubah,cubah1,cubah2,cubah3,cubah4,cubah5); 
          lcidx = rowIndex;                           
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Pengesahan Angkas'; 
           edit_data();   
        }
        });     
        
    });        

function get(ckd_skpd,cnm_skpd,cmurni,cmurni_geser1,cmurni_geser2,cmurni_geser3,cmurni_geser4,cmurni_geser5,csempurna1,csempurna1_geser1,csempurna1_geser2,csempurna1_geser3,csempurna1_geser4,csempurna1_geser5,csempurna2,csempurna2_geser1,csempurna2_geser2,csempurna2_geser3,csempurna2_geser4,csempurna2_geser5,csempurna3,csempurna3_geser1,csempurna3_geser2,csempurna3_geser3,csempurna3_geser4,csempurna3_geser5,csempurna4,csempurna4_geser1,csempurna4_geser2,csempurna4_geser3,csempurna4_geser4,csempurna4_geser5,csempurna5,csempurna5_geser1,csempurna5_geser2,csempurna5_geser3,csempurna5_geser4,csempurna5_geser5,cubah,cubah1,cubah2,cubah3,cubah4,cubah5){
        
$("#kode").combogrid("setValue",ckd_skpd);
if (cmurni==1){
    $("#a_murni").attr("checked",true);
}else{
    $("#a_murni").attr("checked",false);
}
if (cmurni_geser1==1){
    $("#a_murni1").attr("checked",true);
}else{
    $("#a_murni1").attr("checked",false);
}
if (cmurni_geser2==1){
    $("#a_murni2").attr("checked",true);
}else{
    $("#a_murni2").attr("checked",false);
}
if (cmurni_geser3==1){
    $("#a_murni3").attr("checked",true);
}else{
    $("#a_murni3").attr("checked",false);
}
if (cmurni_geser4==1){
    $("#a_murni4").attr("checked",true);
}else{
    $("#a_murni4").attr("checked",false);
}
if (cmurni_geser5==1){
    $("#a_murni5").attr("checked",true);
}else{
    $("#a_murni5").attr("checked",false);
}
if (csempurna1==1){
    $("#sempurna").attr("checked",true);
}else{
    $("#sempurna").attr("checked",false);
}
if (csempurna1_geser1==1){
    $("#sempurna11").attr("checked",true);
}else{
    $("#sempurna11").attr("checked",false);
}
if (csempurna1_geser2==1){
    $("#sempurna12").attr("checked",true);
}else{
    $("#sempurna12").attr("checked",false);
}
if (csempurna1_geser3==1){
    $("#sempurna13").attr("checked",true);
}else{
    $("#sempurna13").attr("checked",false);
}
if (csempurna1_geser4==1){
    $("#sempurna14").attr("checked",true);
}else{
    $("#sempurna14").attr("checked",false);
}
if (csempurna1_geser5==1){
    $("#sempurna15").attr("checked",true);
}else{
    $("#sempurna15").attr("checked",false);
}
if (csempurna2==1){
    $("#sempurna2").attr("checked",true);
}else{
    $("#sempurna2").attr("checked",false);
}
if (csempurna2_geser1==1){
    $("#sempurna21").attr("checked",true);
}else{
    $("#sempurna21").attr("checked",false);
}
if (csempurna2_geser2==1){
    $("#sempurna22").attr("checked",true);
}else{
    $("#sempurna22").attr("checked",false);
}
if (csempurna2_geser3==1){
    $("#sempurna23").attr("checked",true);
}else{
    $("#sempurna23").attr("checked",false);
}
if (csempurna2_geser4==1){
    $("#sempurna24").attr("checked",true);
}else{
    $("#sempurna24").attr("checked",false);
}
if (csempurna2_geser5==1){
    $("#sempurna25").attr("checked",true);
}else{
    $("#sempurna25").attr("checked",false);
}
if (csempurna3==1){
    $("#sempurna3").attr("checked",true);
}else{
    $("#sempurna3").attr("checked",false);
}
if (csempurna3_geser1==1){
    $("#sempurna31").attr("checked",true);
}else{
    $("#sempurna31").attr("checked",false);
}
if (csempurna3_geser2==1){
    $("#sempurna32").attr("checked",true);
}else{
    $("#sempurna32").attr("checked",false);
}
if (csempurna3_geser3==1){
    $("#sempurna33").attr("checked",true);
}else{
    $("#sempurna33").attr("checked",false);
}
if (csempurna3_geser4==1){
    $("#sempurna34").attr("checked",true);
}else{
    $("#sempurna34").attr("checked",false);
}
if (csempurna3_geser5==1){
    $("#sempurna35").attr("checked",true);
}else{
    $("#sempurna35").attr("checked",false);
}
if (csempurna4==1){
    $("#sempurna4").attr("checked",true);
}else{
    $("#sempurna4").attr("checked",false);
}
if (csempurna4_geser1==1){
    $("#sempurna41").attr("checked",true);
}else{
    $("#sempurna41").attr("checked",false);
}
if (csempurna4_geser2==1){
    $("#sempurna42").attr("checked",true);
}else{
    $("#sempurna42").attr("checked",false);
}
if (csempurna4_geser3==1){
    $("#sempurna43").attr("checked",true);
}else{
    $("#sempurna43").attr("checked",false);
}
if (csempurna4_geser4==1){
    $("#sempurna44").attr("checked",true);
}else{
    $("#sempurna44").attr("checked",false);
}
if (csempurna4_geser5==1){
    $("#sempurna45").attr("checked",true);
}else{
    $("#sempurna45").attr("checked",false);
}
if (csempurna5==1){
    $("#sempurna5").attr("checked",true);
}else{
    $("#sempurna5").attr("checked",false);
}
if (csempurna5_geser1==1){
    $("#sempurna51").attr("checked",true);
}else{
    $("#sempurna51").attr("checked",false);
}
if (csempurna5_geser2==1){
    $("#sempurna52").attr("checked",true);
}else{
    $("#sempurna52").attr("checked",false);
}
if (csempurna5_geser3==1){
    $("#sempurna53").attr("checked",true);
}else{
    $("#sempurna53").attr("checked",false);
}
if (csempurna5_geser4==1){
    $("#sempurna54").attr("checked",true);
}else{
    $("#sempurna54").attr("checked",false);
}
if (csempurna5_geser5==1){
    $("#sempurna55").attr("checked",true);
}else{
    $("#sempurna55").attr("checked",false);
}
if (cubah==1){
    $("#ubah").attr("checked",true);
}else{
    $("#ubah").attr("checked",false);
}
if (cubah1==1){
    $("#ubah1").attr("checked",true);
}else{
    $("#ubah1").attr("checked",false);
}
if (cubah2==1){
    $("#ubah2").attr("checked",true);
}else{
    $("#ubah2").attr("checked",false);
}
if (cubah3==1){
    $("#ubah3").attr("checked",true);
}else{
    $("#ubah3").attr("checked",false);
}
if (cubah4==1){
    $("#ubah4").attr("checked",true);
}else{
    $("#ubah4").attr("checked",false);
}
if (cubah5==1){
    $("#ubah5").attr("checked",true);
}else{
    $("#ubah5").attr("checked",false);
}           
    }
  
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/rka_ro/load_pengesahan_angkas',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
function simpan_pengesahan(){
  var ckd = $('#kode').combogrid('getValue');
var s_murni        = document.getElementById('a_murni').checked;
var s_murni_geser1 = document.getElementById('a_murni1').checked;
var s_murni_geser2 = document.getElementById('a_murni2').checked;
var s_murni_geser3 = document.getElementById('a_murni3').checked;
var s_murni_geser4 = document.getElementById('a_murni4').checked;
var s_murni_geser5 = document.getElementById('a_murni5').checked;
var s_sempurna1 = document.getElementById('sempurna').checked;
var s_sempurna1_geser1 = document.getElementById('sempurna11').checked;
var s_sempurna1_geser2 = document.getElementById('sempurna12').checked;
var s_sempurna1_geser3 = document.getElementById('sempurna13').checked;
var s_sempurna1_geser4 = document.getElementById('sempurna14').checked;
var s_sempurna1_geser5 = document.getElementById('sempurna15').checked;
var s_sempurna2 = document.getElementById('sempurna2').checked;
var s_sempurna2_geser1 = document.getElementById('sempurna21').checked;
var s_sempurna2_geser2 = document.getElementById('sempurna22').checked;
var s_sempurna2_geser3 = document.getElementById('sempurna23').checked;
var s_sempurna2_geser4 = document.getElementById('sempurna24').checked;
var s_sempurna2_geser5 = document.getElementById('sempurna25').checked;
var s_sempurna3 = document.getElementById('sempurna3').checked;
var s_sempurna3_geser1 = document.getElementById('sempurna31').checked;
var s_sempurna3_geser2 = document.getElementById('sempurna32').checked;
var s_sempurna3_geser3 = document.getElementById('sempurna33').checked;
var s_sempurna3_geser4 = document.getElementById('sempurna34').checked;
var s_sempurna3_geser5 = document.getElementById('sempurna35').checked;
var s_sempurna4 = document.getElementById('sempurna4').checked;
var s_sempurna4_geser1 = document.getElementById('sempurna41').checked;
var s_sempurna4_geser2 = document.getElementById('sempurna42').checked;
var s_sempurna4_geser3 = document.getElementById('sempurna43').checked;
var s_sempurna4_geser4 = document.getElementById('sempurna44').checked;
var s_sempurna4_geser5 = document.getElementById('sempurna45').checked;
var s_sempurna5 = document.getElementById('sempurna5').checked;
var s_sempurna5_geser1 = document.getElementById('sempurna51').checked;
var s_sempurna5_geser2 = document.getElementById('sempurna52').checked;
var s_sempurna5_geser3 = document.getElementById('sempurna53').checked;
var s_sempurna5_geser4 = document.getElementById('sempurna54').checked;
var s_sempurna5_geser5 = document.getElementById('sempurna55').checked;
var s_ubah = document.getElementById('ubah').checked;
var s_ubah1 = document.getElementById('ubah1').checked;
var s_ubah2 = document.getElementById('ubah2').checked;
var s_ubah3 = document.getElementById('ubah3').checked;
var s_ubah4 = document.getElementById('ubah4').checked;
var s_ubah5 = document.getElementById('ubah5').checked;

if (s_murni==false){
  s_murni=0;
}else{
  s_murni=1;
}

if (s_murni_geser1==false){
  s_murni_geser1=0;
}else{
  s_murni_geser1=1;
}
if (s_murni_geser2==false){
  s_murni_geser2=0;
}else{
  s_murni_geser2=1;
}
if (s_murni_geser3==false){
  s_murni_geser3=0;
}else{
  s_murni_geser3=1;
}
if (s_murni_geser4==false){
  s_murni_geser4=0;
}else{
  s_murni_geser4=1;
}
if (s_murni_geser5==false){
  s_murni_geser5=0;
}else{
  s_murni_geser5=1;
}
if (s_sempurna1==false){
  s_sempurna1=0;
}else{
  s_sempurna1=1;
}
if (s_sempurna1_geser1==false){
  s_sempurna1_geser1=0;
}else{
  s_sempurna1_geser1=1;
}
if (s_sempurna1_geser2==false){
  s_sempurna1_geser2=0;
}else{
  s_sempurna1_geser2=1;
}
if (s_sempurna1_geser3==false){
  s_sempurna1_geser3=0;
}else{
  s_sempurna1_geser3=1;
}
if (s_sempurna1_geser4==false){
  s_sempurna1_geser4=0;
}else{
  s_sempurna1_geser4=1;
}
if (s_sempurna1_geser5==false){
  s_sempurna1_geser5=0;
}else{
  s_sempurna1_geser5=1;
}
if (s_sempurna2==false){
  s_sempurna2=0;
}else{
  s_sempurna2=1;
}
if (s_sempurna2_geser1==false){
  s_sempurna2_geser1=0;
}else{
  s_sempurna2_geser1=1;
}
if (s_sempurna2_geser2==false){
  s_sempurna2_geser2=0;
}else{
  s_sempurna2_geser2=1;
}
if (s_sempurna2_geser3==false){
  s_sempurna2_geser3=0;
}else{
  s_sempurna2_geser3=1;
}
if (s_sempurna2_geser4==false){
  s_sempurna2_geser4=0;
}else{
  s_sempurna2_geser4=1;
}
if (s_sempurna2_geser5==false){
  s_sempurna2_geser5=0;
}else{
  s_sempurna2_geser5=1;
}
if (s_sempurna3==false){
  s_sempurna3=0;
}else{
  s_sempurna3=1;
}
if (s_sempurna3_geser1==false){
  s_sempurna3_geser1=0;
}else{
  s_sempurna3_geser1=1;
}
if (s_sempurna3_geser2==false){
  s_sempurna3_geser2=0;
}else{
  s_sempurna3_geser2=1;
}
if (s_sempurna3_geser3==false){
  s_sempurna3_geser3=0;
}else{
  s_sempurna3_geser3=1;
}
if (s_sempurna3_geser4==false){
  s_sempurna3_geser4=0;
}else{
  s_sempurna3_geser4=1;
}
if (s_sempurna3_geser5==false){
  s_sempurna3_geser5=0;
}else{
  s_sempurna3_geser5=1;
}
if (s_sempurna4==false){
  s_sempurna4=0;
}else{
  s_sempurna4=1;
}
if (s_sempurna4_geser1==false){
  s_sempurna4_geser1=0;
}else{
  s_sempurna4_geser1=1;
}
if (s_sempurna4_geser2==false){
  s_sempurna4_geser2=0;
}else{
  s_sempurna4_geser2=1;
}
if (s_sempurna4_geser3==false){
  s_sempurna4_geser3=0;
}else{
  s_sempurna4_geser3=1;
}
if (s_sempurna4_geser4==false){
  s_sempurna4_geser4=0;
}else{
  s_sempurna4_geser4=1;
}
if (s_sempurna4_geser5==false){
  s_sempurna4_geser5=0;
}else{
  s_sempurna4_geser5=1;
}
if (s_sempurna5==false){
  s_sempurna5=0;
}else{
  s_sempurna5=1;
}
if (s_sempurna5_geser1==false){
  s_sempurna5_geser1=0;
}else{
  s_sempurna5_geser1=1;
}
if (s_sempurna5_geser2==false){
  s_sempurna5_geser2=0;
}else{
  s_sempurna5_geser2=1;
}
if (s_sempurna5_geser3==false){
  s_sempurna5_geser3=0;
}else{
  s_sempurna5_geser3=1;
}
if (s_sempurna5_geser4==false){
  s_sempurna5_geser4=0;
}else{
  s_sempurna5_geser4=1;
}
if (s_sempurna5_geser5==false){
  s_sempurna5_geser5=0;
}else{
  s_sempurna5_geser5=1;
}
if (s_ubah==false){
  s_ubah=0;
}else{
  s_ubah=1;
}
if (s_ubah1==false){
  s_ubah1=0;
}else{
  s_ubah1=1;
}
if (s_ubah2==false){
  s_ubah2=0;
}else{
  s_ubah2=1;
}
if (s_ubah3==false){
  s_ubah3=0;
}else{
  s_ubah3=1;
}
if (s_ubah4==false){
  s_ubah4=0;
}else{
  s_ubah4=1;
}
if (s_ubah5==false){
  s_ubah5=0;
}else{
  s_ubah5=1;
}


if (ckd==''){
  alert('Maaf!!! SKPD Belum dipilih.');
  exit();
}
        
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/rka_ro/simpan_pengesahan',
                    data: ({tabel:'status_angkas',s_murni:s_murni,s_murni_geser1:s_murni_geser1,s_murni_geser2:s_murni_geser2,s_murni_geser3:s_murni_geser3,s_murni_geser4:s_murni_geser4,s_murni_geser5:s_murni_geser5,s_sempurna1:s_sempurna1,s_sempurna1_geser1:s_sempurna1_geser1,s_sempurna1_geser2:s_sempurna1_geser2,s_sempurna1_geser3:s_sempurna1_geser3,s_sempurna1_geser4:s_sempurna1_geser4,s_sempurna1_geser5:s_sempurna1_geser5,s_sempurna2:s_sempurna2,s_sempurna2_geser1:s_sempurna2_geser1,s_sempurna2_geser2:s_sempurna2_geser2,s_sempurna2_geser3:s_sempurna2_geser3,s_sempurna2_geser4:s_sempurna2_geser4,s_sempurna2_geser5:s_sempurna2_geser5,s_sempurna3:s_sempurna3,s_sempurna3_geser1:s_sempurna3_geser1,s_sempurna3_geser2:s_sempurna3_geser2,s_sempurna3_geser3:s_sempurna3_geser3,s_sempurna3_geser4:s_sempurna3_geser4,s_sempurna3_geser5:s_sempurna3_geser5,s_sempurna4:s_sempurna4,s_sempurna4_geser1:s_sempurna4_geser1,s_sempurna4_geser2:s_sempurna4_geser2,s_sempurna4_geser3:s_sempurna4_geser3,s_sempurna4_geser4:s_sempurna4_geser4,s_sempurna4_geser5:s_sempurna4_geser5,s_sempurna5:s_sempurna5,s_sempurna5_geser1:s_sempurna5_geser1,s_sempurna5_geser2:s_sempurna5_geser2,s_sempurna5_geser3:s_sempurna5_geser3,s_sempurna5_geser4:s_sempurna5_geser4,s_sempurna5_geser5:s_sempurna5_geser5,s_ubah:s_ubah,s_ubah1:s_ubah1,s_ubah2:s_ubah2,s_ubah3:s_ubah3,s_ubah4:s_ubah4,s_ubah5:s_ubah5,s_skpd:ckd}),
                    dataType:"json",
                    success:function(data){
                      status = data;

                      if (status==1){
                        alert("Data Berhasil disimpan");
                      }else{
                        alert("Data Gagal disimpan");
                      }

                    }
                });
            });

            $("#dialog-modal").dialog('close');
        $('#dg').edatagrid('reload');
        
        
    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Pengesahan Anggaran Kas';
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
<h3 align="center"><u><b>PENGESAHAN ANGGARAN KAS</b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:400px;" border="0">
        <tr>
            
        <td width="5%">
            
        </td>
        <td align="right"><input type="text" value="" id="txtcari" style="width:300px;"/><button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:cari();"><i class="fa fa-search"></i> </button></td>

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
                <td><input id="kode" style="width:150px;border: none; " disabled/></td>
                <td colspan="3" width="79%"><input  type="text" style="width:99%;border: none;" id="nmskpd"  style="border:0;" disabled /></td>
            </tr> 
            <td ><font color="red">Angkas Murni</font> </td>
            <td >:</td>
            <td></td><!--|<input type="text" id="tgldpasempurna" style="width:100px;"/></td>-->
            <td ></td>
            <td></td>
            <td></td><!--|<input type="text" id="tgldpasempurna4" style="width:100px;"/></td>-->
            </tr>

            <td >Murni</td>
            <td >:</td>
            <td><input type="checkbox" id="a_murni"  onclick="javascript:runEffect();"/></td>
            <td >Murni Geser I</td>
            <td>:</td>
            <td><input type="checkbox" id="a_murni1"  onclick="javascript:runEffect();"/></td>
            </tr>
            <td >Murni Geser II</td>
            <td >:</td>
            <td><input type="checkbox" id="a_murni2"  onclick="javascript:runEffect();"/></td>
            <td >Murni Geser III</td>
            <td>:</td>
            <td><input type="checkbox" id="a_murni3"  onclick="javascript:runEffect();"/></td>
            </tr>
            <td >Murni Geser IV</td>
            <td >:</td>
            <td><input type="checkbox" id="a_murni4"  onclick="javascript:runEffect();"/></td>
            <td >Murni Geser V</td>
            <td>:</td>
            <td><input type="checkbox" id="a_murni5"  onclick="javascript:runEffect();"/></td>
            </tr>

            <td ><font color="red">Angkas Penyempurnaan I</font> </td>
            <td >:</td>
            <td></td><!--|<input type="text" id="tgldpasempurna" style="width:100px;"/></td>-->
            <td ></td>
            <td></td>
            <td></td><!--|<input type="text" id="tgldpasempurna4" style="width:100px;"/></td>-->
            </tr>
            <tr>
            <td >Sempurna 1</td>
            <td >:</td>
            <td><input type="checkbox" id="sempurna"  onclick="javascript:runEffect();"/></td>
            <td >Sempurna 1 Geser I</td>
            <td>:</td>
            <td><input type="checkbox" id="sempurna11"  onclick="javascript:runEffect();"/></td>
            </tr>
            <td >Sempurna 1 Geser II</td>
            <td >:</td>
            <td><input type="checkbox" id="sempurna12"  onclick="javascript:runEffect();"/></td>
            <td >Sempurna 1 Geser III</td>
            <td>:</td>
            <td><input type="checkbox" id="sempurna13"  onclick="javascript:runEffect();"/></td>
            </tr>
            <td >Sempurna 1 Geser IV</td>
            <td >:</td>
            <td><input type="checkbox" id="sempurna14"  onclick="javascript:runEffect();"/></td>
            <td >Sempurna 1 Geser V</td>
            <td>:</td>
            <td><input type="checkbox" id="sempurna15"  onclick="javascript:runEffect();"/></td>
            </tr>

            <td ><font color="red">Angkas Penyempurnaan II</font> </td>
            <td >:</td>
            <td></td><!--|<input type="text" id="tgldpasempurna" style="width:100px;"/></td>-->
            <td ></td>
            <td></td>
            <td></td><!--|<input type="text" id="tgldpasempurna4" style="width:100px;"/></td>-->
            </tr>
            <tr>
            <td >Sempurna 2</td>
            <td >:</td>
            <td><input type="checkbox" id="sempurna2"  onclick="javascript:runEffect();"/></td>
            <td >Sempurna 2 Geser I</td>
            <td>:</td>
            <td><input type="checkbox" id="sempurna21"  onclick="javascript:runEffect();"/></td>
            </tr>
            <td >Sempurna 2 Geser II</td>
            <td >:</td>
            <td><input type="checkbox" id="sempurna22"  onclick="javascript:runEffect();"/></td>
            <td >Sempurna 2 Geser III</td>
            <td>:</td>
            <td><input type="checkbox" id="sempurna23"  onclick="javascript:runEffect();"/></td>
            </tr>
            <td >Sempurna 2 Geser IV</td>
            <td >:</td>
            <td><input type="checkbox" id="sempurna24"  onclick="javascript:runEffect();"/></td>
            <td >Sempurna 2 Geser V</td>
            <td>:</td>
            <td><input type="checkbox" id="sempurna25"  onclick="javascript:runEffect();"/></td>
            </tr>

            <td ><font color="red">Angkas Penyempurnaan III</font> </td>
            <td >:</td>
            <td></td><!--|<input type="text" id="tgldpasempurna" style="width:100px;"/></td>-->
            <td ></td>
            <td></td>
            <td></td><!--|<input type="text" id="tgldpasempurna4" style="width:100px;"/></td>-->
            </tr>
            <tr>
            <td >Sempurna 3</td>
            <td >:</td>
            <td><input type="checkbox" id="sempurna3"  onclick="javascript:runEffect();"/></td>
            <td >Sempurna 3 Geser I</td>
            <td>:</td>
            <td><input type="checkbox" id="sempurna31"  onclick="javascript:runEffect();"/></td>
            </tr>
            <td >Sempurna 3 Geser II</td>
            <td >:</td>
            <td><input type="checkbox" id="sempurna32"  onclick="javascript:runEffect();"/></td>
            <td >Sempurna 3 Geser III</td>
            <td>:</td>
            <td><input type="checkbox" id="sempurna33"  onclick="javascript:runEffect();"/></td>
            </tr>
            <td >Sempurna 3 Geser IV</td>
            <td >:</td>
            <td><input type="checkbox" id="sempurna34"  onclick="javascript:runEffect();"/></td>
            <td >Sempurna 3 Geser V</td>
            <td>:</td>
            <td><input type="checkbox" id="sempurna35"  onclick="javascript:runEffect();"/></td>
            </tr>

            <td ><font color="red">Angkas Penyempurnaan IV</font> </td>
            <td >:</td>
            <td></td><!--|<input type="text" id="tgldpasempurna" style="width:100px;"/></td>-->
            <td ></td>
            <td></td>
            <td></td><!--|<input type="text" id="tgldpasempurna4" style="width:100px;"/></td>-->
            </tr>
            <tr>
            <td >Sempurna 4</td>
            <td >:</td>
            <td><input type="checkbox" id="sempurna4"  onclick="javascript:runEffect();"/></td>
            <td >Sempurna 4 Geser I</td>
            <td>:</td>
            <td><input type="checkbox" id="sempurna41"  onclick="javascript:runEffect();"/></td>
            </tr>
            <td >Sempurna 4 Geser II</td>
            <td >:</td>
            <td><input type="checkbox" id="sempurna42"  onclick="javascript:runEffect();"/></td>
            <td >Sempurna 4 Geser III</td>
            <td>:</td>
            <td><input type="checkbox" id="sempurna43"  onclick="javascript:runEffect();"/></td>
            </tr>
            <td >Sempurna 4 Geser IV</td>
            <td >:</td>
            <td><input type="checkbox" id="sempurna44"  onclick="javascript:runEffect();"/></td>
            <td >Sempurna 4 Geser V</td>
            <td>:</td>
            <td><input type="checkbox" id="sempurna45"  onclick="javascript:runEffect();"/></td>
            </tr>

            <td ><font color="red">Angkas Penyempurnaan V</font> </td>
            <td >:</td>
            <td></td><!--|<input type="text" id="tgldpasempurna" style="width:100px;"/></td>-->
            <td ></td>
            <td></td>
            <td></td><!--|<input type="text" id="tgldpasempurna4" style="width:100px;"/></td>-->
            </tr>
            <tr>
            <td >Sempurna 5</td>
            <td >:</td>
            <td><input type="checkbox" id="sempurna5"  onclick="javascript:runEffect();"/></td>
            <td >Sempurna 5 Geser I</td>
            <td>:</td>
            <td><input type="checkbox" id="sempurna51"  onclick="javascript:runEffect();"/></td>
            </tr>
            <td >Sempurna 5 Geser II</td>
            <td >:</td>
            <td><input type="checkbox" id="sempurna52"  onclick="javascript:runEffect();"/></td>
            <td >Sempurna 5 Geser III</td>
            <td>:</td>
            <td><input type="checkbox" id="sempurna53"  onclick="javascript:runEffect();"/></td>
            </tr>
            <td >Sempurna 5 Geser IV</td>
            <td >:</td>
            <td><input type="checkbox" id="sempurna54"  onclick="javascript:runEffect();"/></td>
            <td >Sempurna 5 Geser V</td>
            <td>:</td>
            <td><input type="checkbox" id="sempurna55"  onclick="javascript:runEffect();"/></td>
            </tr>


            <td ><font color="red">Angkas Perubahan</font> </td>
            <td >:</td>
            <td></td><!--|<input type="text" id="tgldpasempurna" style="width:100px;"/></td>-->
            <td ></td>
            <td></td>
            <td></td><!--|<input type="text" id="tgldpasempurna4" style="width:100px;"/></td>-->
            </tr>
            <tr>
            <td >Perubahan</td>
            <td >:</td>
            <td><input type="checkbox" id="ubah"  onclick="javascript:runEffect();"/></td>
            <td >Perubahan Geser I</td>
            <td>:</td>
            <td><input type="checkbox" id="ubah1"  onclick="javascript:runEffect();"/></td>
            </tr>
            <td >Perubahan Geser II</td>
            <td >:</td>
            <td><input type="checkbox" id="ubah2"  onclick="javascript:runEffect();"/></td>
            <td >Perubahan Geser III</td>
            <td>:</td>
            <td><input type="checkbox" id="ubah3"  onclick="javascript:runEffect();"/></td>
            </tr>
            <td >Perubahan Geser IV</td>
            <td >:</td>
            <td><input type="checkbox" id="ubah4"  onclick="javascript:runEffect();"/></td>
            <td >Perubahan Geser V</td>
            <td>:</td>
            <td><input type="checkbox" id="ubah5"  onclick="javascript:runEffect();"/></td>
            </tr>
             
            <tr>
            <td colspan="6">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="6" align="center">
                  <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_pengesahan();">Simpan</a>
                  <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>              
            </tr>
        </table>       
    </fieldset>
</div>

</body>

</html>