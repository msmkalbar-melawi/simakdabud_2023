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
		var nip = '';
		var kdskpd = '';
		var kdrek5 = '';
		var ctk = 1;



		$(function() {

			$('#skpd').combogrid({
				panelWidth: 700,
				idField: 'kd_skpd',
				textField: 'kd_skpd',
				mode: 'remote',
				url: '<?php echo base_url(); ?>index.php/tukd/skpd_monitoring',
				columns: [
					[{
							field: 'kd_skpd',
							title: 'Kode SKPD',
							width: 150
						},
						{
							field: 'nm_skpd',
							title: 'Nama SKPD',
							width: 700
						}
					]
				],
				onSelect: function(rowIndex, rowData) {
					kode = rowData.kd_skpd;
					$("#nmskpd").attr("value", rowData.nm_skpd);
					load_ttd1(kode);
				}
			});


			$('#dcetak').datebox({
				required: true,
				formatter: function(date) {
					var y = date.getFullYear();
					var m = date.getMonth() + 1;
					var d = date.getDate();
					return y + '-' + m + '-' + d;
				}
			});

			$('#dcetak_ttd').datebox({
				required: true,
				formatter: function(date) {
					var y = date.getFullYear();
					var m = date.getMonth() + 1;
					var d = date.getDate();
					return y + '-' + m + '-' + d;
				}
			});

			$('#dcetak2').datebox({
				required: true,
				formatter: function(date) {
					var y = date.getFullYear();
					var m = date.getMonth() + 1;
					var d = date.getDate();
					return y + '-' + m + '-' + d;
				}
			});

			$('#bulan').combogrid({
				panelWidth: 120,
				panelHeight: 300,
				idField: 'bln',
				textField: 'nm_bulan',
				mode: 'remote',
				url: '<?php echo base_url(); ?>index.php/rka/bulan',
				columns: [
					[{
						field: 'nm_bulan',
						title: 'Nama Bulan',
						width: 700
					}]
				],
				onSelect: function(rowIndex, rowData) {
					bulan = rowData.nm_bulan;
					$("#bulan").attr("value", rowData.nm_bulan);
				}
			});



		});

		function load_ttd1(kode) {
			var kode = kode;

			$('#ttd').combogrid({
				panelWidth: 600,
				idField: 'nip',
				textField: 'nip',
				mode: 'remote',
				url: '<?php echo base_url(); ?>index.php/tukd/load_ttd_moni/PA/' + kode,
				columns: [
					[{
							field: 'nip',
							title: 'NIP',
							width: 200
						},
						{
							field: 'nama',
							title: 'Nama',
							width: 400
						}
					]
				],
				onSelect: function(rowIndex, rowData) {
					$("#nmttd").attr("value", rowData.nama);
				}

			});

			$('#ttd2').combogrid({
				panelWidth: 600,
				idField: 'nip',
				textField: 'nip',
				mode: 'remote',
				url: '<?php echo base_url(); ?>index.php/tukd/load_ttd_moni/PPK/' + kode,
				columns: [
					[{
							field: 'nip',
							title: 'NIP',
							width: 200
						},
						{
							field: 'nama',
							title: 'Nama',
							width: 400
						}
					]
				],
				onSelect: function(rowIndex, rowData) {
					$("#nmttd2").attr("value", rowData.nama);
				}

			});
		}

		$(document).ready(function() {
			//get_skpd();                                                            
			cdate = '<?php echo date("Y-m-d"); ?>';
			$("#dcetak").datebox("setValue", cdate);
			$("#dcetak2").datebox("setValue", cdate);

		});

		function opt(val) {
			ctk = val;
			if (ctk == '2') {
				//$("#div_skpd").hide();
				options = {
					percent: 0
				};
				selectedEffect = "clip";
				$("#div_skpd").hide(selectedEffect, options, 1000);
			} else if (ctk == '1') {
				//            $("#div_skpd").show();
				$("#div_skpd").show(selectedEffect, options, 1000);
			} else {
				exit();
			}
		}

		function pilih(pilih) {
			pilihan = pilih;
		}

		function callback() {
			setTimeout(function() {
				$("#div_skpd").removeAttr("style").hide().fadeIn();
			}, 1000);
		};


		function Ju_memorial(cetak) {
			var ttdx = $("#ttd").combogrid('getValue');
			var ttdz = $("#ttd2").combogrid('getValue');
			var ttd1 = ttdx.split(" ").join("a");
			var ttd2 = ttdz.split(" ").join("b");
			var cetak = cetak;
			var cbulan = $('#bulan').combogrid('getValue');
			var skpd = kode;
			var url = "<?php echo site_url(); ?>tukd/cetak_ju_memorial";
			window.open(url + '/' + ttd1 + '/' + skpd + '/' + cbulan + '/' + cetak + '/' + ttd2 + '/1');
			window.focus();
		}


		function sisa_kas_memorial(cetak) {
			var ttdx = $("#ttd").combogrid('getValue');
			var ttdz = $("#ttd2").combogrid('getValue');
			var ttd1 = ttdx.split(" ").join("a");
			var ttd2 = ttdz.split(" ").join("b");
			var cetak = cetak;
			var cbulan = $('#bulan').combogrid('getValue');
			var skpd = kode;
			var url = "<?php echo site_url(); ?>tukd/cetak_sisa_kas_memorial";
			window.open(url + '/' + ttd1 + '/' + skpd + '/' + cbulan + '/' + cetak + '/' + ttd2 + '/1');
			window.focus();
		}
	</script>

	<STYLE TYPE="text/css">
		input.right {
			text-align: right;
		}
	</STYLE>

</head>

<body>

	<div id="content">

		<div id="accordion">

			<h3>MONITORING SKPD</h3>
			<div>
				<p align="right">
				<table id="sp2d" title="Cetak KARTU KENDALI" style="width:870px;height:300px;">
					<tr>
						<td colspan="2">
							<div id="div_skpd">
								<table style="width:100%;" border="0">
									<tr>
										<td width="20%" height="40"><B>SKPD</B></td>
										<td width="80%"><input id="skpd" name="skpd" style="width: 155px;border: 0;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" readonly="true" style="width: 500px; border:0;" /></td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td width="20%" height="40"><B>BULAN</B></td>
						<td width="80%"><input type="text" id="bulan" style="width: 100px;" /></td>
					</tr>
					<tr>
						<td width="20%" height="40"><B> PA </B></td>
						<td width="80%"><input id="ttd" name="ttd" type="text" style="width:230px" /></td>
					</tr>
					<tr>
						<td width="20%" height="40"><B> PPK </B></td>
						<td width="80%"><input id="ttd2" name="ttd2" type="text" style="width:230px" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>Tanggal TTD <input id="dcetak_ttd" name="dcetak_ttd" type="text" style="width:155px" /></td>
					</tr>
					<tr>
						<td width="20%" height="40"><B>Cetakan Jurnal Memorial</B></td>
						<td width="80%"> <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:Ju_memorial(2);">Cetak Layar</a>
							<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:Ju_memorial(1);">Cetak Pdf</a>
						</td>
					</tr>
					<tr>
						<td width="20%" height="40"><B>Cetakan Sisa Kas</B></td>
						<td width="80%"> <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:sisa_kas_memorial(2);">Cetak Layar</a>
							<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:sisa_kas_memorial(1);">Cetak Pdf</a>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				</p>
			</div>
		</div>
	</div>


</body>

</html>