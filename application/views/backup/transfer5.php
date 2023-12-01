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

	function rekal(){
		document.getElementById('load').style.visibility='visible';
		$(function(){      
		 $.ajax({
			type: 'POST',
			data: ({nomor:'1',sumber:'5'}),
			dataType:"json",
			url:"<?php echo base_url(); ?>index.php/rka/proses_transfer_sumber",
			success:function(data){
				if (data == '1'){
					alert('Transfer Berhasil dari Data SUMBER 5 SELANJUTNYA CEK DATA SIMAKDA DAN SIMAKDA SKPD (RKA 22 & 0)');
				}else{
					alert('CLIENT LAIN SEDANG MELAKUKAN REKAL');					
				}
				document.getElementById('load').style.visibility='hidden';
			}
		 });
		});
	}
		


var currentFolder="";
function GetDriveList(){
  var fso, obj, n, e, item, arr=[];
try { 
  fso = new ActiveXObject("Scripting.FileSystemObject"); 
} 
catch(er) {
  alert('Could not load Drives. The ActiveX control could not be started.');
  cancelFolder();
} 

  e = new Enumerator(fso.Drives); 
  for(;!e.atEnd();e.moveNext()){ 
    item = e.item();
    obj = {letter:"",description:""};
    obj.letter = item.DriveLetter;
    if (item.DriveType == 3) obj.description = item.ShareName;
    else if (item.IsReady) obj.description = item.VolumeName;
    else obj.description = "[Drive not ready]";
    arr[arr.length]=obj;
  } 
  return(arr);
}
function GetSubFolderList(fld){
  var e, arr=[];
  var fso = new ActiveXObject("Scripting.FileSystemObject");
  var f = fso.GetFolder(fld.toString());
  var e = new Enumerator(f.SubFolders);
  for(;!e.atEnd();e.moveNext()){ 
    arr[arr.length]=e.item().Name;
  }
  return(arr);
}
function loadDrives(){
  var drives=GetDriveList(),list="";
  for(var i=0;i<drives.length;i++){
    list+="<div onclick=\"loadList('"+drives[i].letter+':\\\\\')" class="folders" onmouseover="highlight(this)" onmouseout="unhighlight(this)">'+drives[i].letter+':\\ - '+ drives[i].description+'</div>';
  }
  document.getElementById("path").innerHTML='<a href="" onclick="loadDrives();return false" title="My Computer">My Computer</a>\\';
  document.getElementById("list").innerHTML=list;
  currentFolder="";
}
function loadList(fld){
  var path="",list="",paths=fld.split("\\");
  var divPath=document.getElementById("path");
  var divList=document.getElementById("list");
  for(var i=0;i<paths.length-1;i++){
    if(i==paths.length-2){
      path+=paths[i]+' \\';
    }else{
      path+="<a href=\"\" onclick=\"loadList('";
      for(var j=0;j<i+1;j++){
        path+=paths[j]+"\\\\";
      }
      path+='\');return false">'+paths[i]+'</a> \\ ';
    }
  }
  divPath.innerHTML='<a href="" onclick="loadDrives();return false">My Computer</a> \\ '+path;
  divPath.title="My Computer\\"+paths.toString().replace(/,/g,"\\");
  currentFolder=paths.toString().replace(/,/g,"\\");

  var subfolders=GetSubFolderList(fld);
  for(var j=0;j<subfolders.length;j++){
    list+="<div onclick=\"loadList('"+(fld+subfolders[j]).replace(/\\/g,"\\\\")+'\\\\\')" onmouseover="highlight(this)" onmouseout="unhighlight(this)" title="'+subfolders[j]+'" class="folders">'+subfolders[j]+"</div>";
  }
  divList.innerHTML=list;
  resizeList();
  divPath.scrollIntoView();
}
function resizeList(){
  var divList=document.getElementById("list");
  var divPath=document.getElementById("path");
  if(document.body.clientHeight>0 && divPath.offsetHeight>0){
    divList.style.height=document.body.clientHeight-divPath.scrollHeight;
  }
}
function highlight(div){
  div.className="folderButton";
}
function unhighlight(div){
  div.className="folders";
}
function selectFolder(){
  window.returnValue=currentFolder;
  window.close();
}
function cancelFolder(){
  window.returnValue="";
  window.close();
}

    </script>

</head>
<body>

<div id="content">

<div id="accordion">

<h3 >TRANSFER DATA SKPD DARI <b style="font-size:70px;color:#FF66CC;">DATA SOURCE 5</b></h3>
    <div>
    <p >         
        <table id="sp2d" title="Rekal Transaksi" style="width:870px;height:300px;" > 
	<tr>
                <td><b> &nbsp;&nbsp;&nbsp; Database SKPD : </b> &nbsp;&nbsp;&nbsp;&nbsp; <input type="text" id="siadinda" name="siadinda" style="width:250px;" value="sumber_5" readonly /></td>				
           </tr>
		<tr >
			<td width="100%" align="center"> <INPUT TYPE="button" VALUE="PROSES" style="height:40px;width:100px" onclick="rekal()" >
			</td>
		</tr>
		<tr height="70%" >
			<td align="center" style="visibility:hidden" >	<DIV id="load" > <IMG SRC="<?php echo base_url(); ?>assets/images/loading.gif" WIDTH="270" HEIGHT="40" BORDER="0" ALT=""></DIV></td>
		</tr>
        </table>                      
    </p> 
    </div>
</div>
</div>

 	
</body>

</html>