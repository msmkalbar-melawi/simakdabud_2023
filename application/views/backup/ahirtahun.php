
<script type="text/javascript">

	function proses(){
		var cnama=document.getElementById('namatabel').value;
		if (cnama=='')
		{
			alert('Nama Database Tidak Boleh Kosong');
			exit;
		}
		$(document).ready(function(){
			$.ajax({
				type: "POST",
				url: '<?php echo base_url(); ?>/index.php/utilitas/proses_ahirtahun',
				data: ({nama:cnama}),
				dataType:"json",
				success:function(data){
					alert(data.hasil);
				}
			});
		});           

	}






</script>



<div id="content">
<table  width="100%">
<tr>
	<td align="center">
		<h1><?echo $status_upload ?></h1>
	</td>
</tr>
</table>
<table  width="100%">
<tr>	
	<td align="left">
		NAMA DATABASE BARU
	</td>
	<td align="left">
		<input type="text" name="namatabel" id="namatabel">	
	</td>
</tr>
<tr>
	<td align="left">
		
	</td>
	<td align="left">
		<input type="button"  name="backup" value="PROSES" onclick="proses()">
	</td>
</tr>

</table>
</div>
