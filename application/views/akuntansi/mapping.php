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

	<link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>

	<script type="text/javascript">
		function mapping() {
			document.getElementById('load').style.visibility = 'visible';

			$(function() {
				$.ajax({
					type: 'POST',
					data: ({
						nomor: '1'
					}),
					dataType: "json",
					url: "<?php echo base_url(); ?>index.php/akuntansi/proses_mapping",
					success: function(data) {
						if (data = 1) {
							alert('POSTING JURNAL PPKD SELESAI');
							document.getElementById('load').style.visibility = 'hidden';
						}
					}
				});
			});
		}

		function mapping_all_bos() {
			document.getElementById('load').style.visibility = 'visible';

			$(function() {
				$.ajax({
					type: 'POST',
					data: ({
						nomor: '1'
					}),
					dataType: "json",
					url: "<?php echo base_url(); ?>index.php/akuntansi/proses_mapping_all_bos",
					success: function(data) {
						if (data = 1) {
							alert('POSTING JURNAL DANA BOS');
							document.getElementById('load').style.visibility = 'hidden';
						}
					}
				});
			});
		}

		function mapping_all_bos_tos() {
			document.getElementById('load').style.visibility = 'visible';

			$(function() {
				$.ajax({
					type: 'POST',
					data: ({
						nomor: '1'
					}),
					dataType: "json",
					url: "<?php echo base_url(); ?>index.php/akuntansi/proses_mapping_all_bos_tos",
					success: function(data) {
						if (data = 1) {
							alert('TRANSFER JURNAL BOS KE SIMAKDA');
							document.getElementById('load').style.visibility = 'hidden';
						}
					}
				});
			});
		}

		function mapping_all() {
			document.getElementById('load').style.visibility = 'visible';

			$(function() {
				document.getElementById('semua').disabled = true;
				$.ajax({
					type: 'POST',
					data: ({
						nomor: '1'
					}),
					dataType: "json",
					url: "<?php echo base_url(); ?>index.php/akuntansi/proses_mapping_all",
					success: function(data) {
						if (data = 1) {
							alert('POSTING JURNAL SELURUH SKPD DAN UNIT SELESAI');
							document.getElementById('load').style.visibility = 'hidden';
							document.getElementById('semua').disabled = false;
						}
					}

				});
			});
		}

		function mapping_skpd() {
			document.getElementById('load').style.visibility = 'visible';

			$(function() {
				$.ajax({
					type: 'POST',
					data: ({
						nomor: '1'
					}),
					dataType: "json",
					url: "<?php echo base_url(); ?>index.php/akuntansi/proses_mapping_skpd",
					success: function(data) {
						if (data = 1) {
							alert('POSTING JURNAL SELURUH SKPD DAN UNIT SELESAI');
							document.getElementById('load').style.visibility = 'hidden';
						}
					}
				});
			});
		}

		function mapping_simple() {
			document.getElementById('load').style.visibility = 'visible';

			$(function() {
				$.ajax({
					type: 'POST',
					data: ({
						nomor: '1'
					}),
					dataType: "json",
					url: "<?php echo base_url(); ?>index.php/akuntansi/proses_mapping_skpd",
					success: function(data) {
						if (data = 1) {
							alert('POSTING JURNAL SELURUH SKPD DAN UNIT SELESAI');
							document.getElementById('load').style.visibility = 'hidden';
						}
					}
				});
			});
		}

		function mapping_rekap() {
			document.getElementById('load').style.visibility = 'visible';

			$(function() {
				document.getElementById('permen90').disabled = true;
				$.ajax({
					type: 'POST',
					data: ({
						nomor: '1'
					}),
					dataType: "json",
					url: "<?php echo base_url(); ?>index.php/akuntansi/proses_mapping_rekap",
					success: function(data) {
						if (data = 1) {
							alert('POSTING JURNAL REKAP');
							document.getElementById('permen90').disabled = false;
							document.getElementById('load').style.visibility = 'hidden';
						}
					}
				});
			});
		}

		function mapping_rekap_rinci() {
			document.getElementById('load').style.visibility = 'visible';

			$(function() {
				$.ajax({
					type: 'POST',
					data: ({
						nomor: '1'
					}),
					dataType: "json",
					url: "<?php echo base_url(); ?>index.php/akuntansi/proses_mapping_simple",
					success: function(data) {
						if (data = 1) {
							alert('POSTING JURNAL REKAP RINCI');
							document.getElementById('load').style.visibility = 'hidden';
						}
					}
				});
			});
		}

		function mapping_blud_pend() {
			document.getElementById('load').style.visibility = 'visible';

			$(function() {
				$.ajax({
					type: 'POST',
					data: ({
						nomor: '1'
					}),
					dataType: "json",
					url: "<?php echo base_url(); ?>index.php/akuntansi/proses_mapping_bludpend",
					success: function(data) {
						if (data = 1) {
							alert('POSTING JURNAL PENDAPATAN BLUD');
							document.getElementById('load').style.visibility = 'hidden';
						}
					}
				});
			});
		}

		function mapping_blud_bel() {
			document.getElementById('load').style.visibility = 'visible';

			$(function() {
				$.ajax({
					type: 'POST',
					data: ({
						nomor: '1'
					}),
					dataType: "json",
					url: "<?php echo base_url(); ?>index.php/akuntansi/proses_mapping_bludbel",
					success: function(data) {
						if (data = 1) {
							alert('POSTING JURNAL BELANJA BLUD');
							document.getElementById('load').style.visibility = 'hidden';
						}
					}
				});
			});
		}

		function mapping_blud_all() {
			document.getElementById('load').style.visibility = 'visible';

			$(function() {
				$.ajax({
					type: 'POST',
					data: ({
						nomor: '1'
					}),
					dataType: "json",
					url: "<?php echo base_url(); ?>index.php/akuntansi/proses_mapping_bludbel",
					success: function(data) {
						if (data = 1) {
							alert('POSTING JURNAL BELANJA BLUD');
							document.getElementById('load').style.visibility = 'hidden';
						}
					}
				});
			});
		}

		function update_konsol() {
			document.getElementById('load').style.visibility = 'visible';

			$(function() {
				$.ajax({
					type: 'POST',
					data: ({
						nomor: '1'
					}),
					dataType: "json",
					url: "<?php echo base_url(); ?>index.php/akuntansi/proses_konsol",
					success: function(data) {
						if (data = 1) {
							alert('UPDATE KONSOLIDASI SELESAI');
							document.getElementById('load').style.visibility = 'hidden';
						}
					}
				});
			});
		}
	</script>

</head>

<body>

	<div id="content">

		<div id="accordion">

			<h3>MAPPING REALISASI</h3>
			<div>
				<p>
				<table id="sp2d" title="Mapping Realisasi" style="width:100%;height:300px;">
					<tr>
						<td width="100%" align="center"><b>SIMAKDA</b><br /><input class="button" id="semua" TYPE="button" VALUE="1. POSTING JURNAL SEMUA SKPD" style="cursor: pointer;height:40px;width:250px" onclick="mapping_all()">
						</td>
					</tr>
					<!-- <tr>
						<td width="100%" align="center"> <input class="button" TYPE="button" VALUE="2. POSTING JURNAL PPKD" style="cursor: pointer;height:40px;width:250px" disabled onclick="mapping()">
						</td>
					</tr> -->
					<tr hidden>
						<td width="100%" align="center"><b>SIMBOSSKOST</b><br /><input class="button" TYPE="button" VALUE="1. POSTING SEMUA DANA BOS" style="cursor: pointer;height:40px;width:250px" onclick="mapping_all_bos()">
						</td>
					</tr>
					<tr hidden>
						<td width="100%" align="center"> <input class="button" TYPE="button" VALUE="2. TRASFER JURNAL BOS KE SIMAKDA" style="cursor: pointer;height:40px;width:250px" onclick="mapping_all_bos_tos()">
						</td>
					</tr>

					<tr height="70%">
						<td align="center" style="visibility:hidden">
							<DIV id="load"> <IMG SRC="<?php echo base_url(); ?>assets/images/loading.gif" WIDTH="300" HEIGHT="40" BORDER="0" ALT=""></DIV>
						</td>
					</tr>
					<tr>
						<td width="100%" align="center"><b>SIMBLUD</b><br /><iframe height="330" width="900" src="http://36.67.122.89:81/simakdablud_2020/"></iframe>
						</td>
					</tr>

					<tr hidden>
						<td width="100%" align="center"> <input class="button" TYPE="button" VALUE="1. POSTING JURNAL BLUD" style="cursor: pointer;height:40px;width:250px" onclick="mapping_blud_all()">
						</td>
					</tr>
					<tr>
						<td width="100%" align="center"><b>LRA</b><br /> <input class="button" TYPE="button" id="permen90" VALUE="1. POSTING JURNAL REKAP PERMEN 90" style="cursor: pointer;height:40px;width:250px" onclick="mapping_rekap()">
						</td>
					</tr>
					<tr hidden>
						<td width="100%" align="center"><input class="button" TYPE="button" VALUE="2. POSTING JURNAL REKAP 13" style="cursor: pointer;height:40px;width:250px" onclick="mapping_rekap_rinci()">
						</td>
					</tr>
				</table>
				</p>
			</div>
		</div>
	</div>


</body>

</html>