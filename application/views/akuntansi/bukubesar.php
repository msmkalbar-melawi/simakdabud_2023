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
		var kdrek6 = '';
		var ctk = '';
		$(function() {
			$("#skpd").hide();


			$('#sskpd').combogrid({
				panelWidth: 630,
				idField: 'kd_skpd',
				textField: 'kd_skpd',
				mode: 'remote',
				url: '<?php echo base_url(); ?>index.php/akuntansi/skpd',
				columns: [
					[{
							field: 'kd_skpd',
							title: 'Kode SKPD',
							width: 100
						},
						{
							field: 'nm_skpd',
							title: 'Nama SKPD',
							width: 500
						}
					]
				],
				onSelect: function(rowIndex, rowData) {
					kdskpd = rowData.kd_skpd;
					$("#nmskpd").attr("value", rowData.nm_skpd);
					$("#skpd").attr("value", rowData.kd_skpd);
					$("#kdrek6").combogrid({
						url: '<?php echo base_url(); ?>index.php/akuntansi/rekening',
						queryParams: ({
							kd: kdskpd
						})
					});
					//validate_giat();
				}
			});
		});


		$(function() {
			$('#kdrek6').combogrid({
				panelWidth: 630,
				idField: 'kd_rek6',
				textField: 'kd_rek6',
				mode: 'remote',
				url: '<?php echo base_url(); ?>index.php/akuntansi/rekening',
				columns: [
					[{
							field: 'kd_rek6',
							title: 'Kode Rekening',
							width: 100
						},
						{
							field: 'nm_rek6',
							title: 'Nama Rekening',
							width: 500
						}
					]
				],
				onSelect: function(rowIndex, rowData) {
					rekening = rowData.kd_rek6;
					$("#kdrek6").attr("value", rowData.kd_rek6);
					$("#nmrek6").attr("value", rowData.nm_rek6);
				}
			});
		});


		$(function() {
			$('#dcetak').datebox({
				required: true,
				formatter: function(date) {
					var y = date.getFullYear();
					var m = date.getMonth() + 1;
					var d = date.getDate();
					return y + '-' + m + '-' + d;
				}
			});
		});

		$(function() {
			$('#dcetak2').datebox({
				required: true,
				formatter: function(date) {
					var y = date.getFullYear();
					var m = date.getMonth() + 1;
					var d = date.getDate();
					return y + '-' + m + '-' + d;
				}
			});
		});


		$(function() {
			$('#ttd').combogrid({
				panelWidth: 500,
				url: '<?php echo base_url(); ?>index.php/tukd/list_ttd',
				idField: 'nip',
				textField: 'nama',
				mode: 'remote',
				fitColumns: true,
				columns: [
					[{
							field: 'nip',
							title: 'NIP',
							width: 60
						},
						{
							field: 'nama',
							title: 'NAMA',
							align: 'left',
							width: 100
						}
					]
				],
				onSelect: function(rowIndex, rowData) {
					nip = rowData.nip;

				}
			});
		});

		function opt(val) {
			ctk = val;
			if (ctk == '1') {
				$("#skpd").hide();
			} else if (ctk == '2') {
				$("#skpd").show();
			} else {
				exit();
			}
		}


		function cetakbb($cetak_bb) {
			var cetak_bb = $cetak_bb;
			var dcetak = $('#dcetak').datebox('getValue');
			var dcetak2 = $('#dcetak2').datebox('getValue');
			//var ttd    = nip;                           
			//var ttd1 =ttd.split(" ").join("a"); 
			var skpd = kdskpd;

			var rek6 = rekening;
			var cetak = ctk;
			if (cetak == '1') {
				var skpd = '-';
				// alert(skpd);
				// return;
				var url = "<?php echo site_url(); ?>akuntansi/cetakbb";
				window.open(url + '/' + dcetak + '/' + skpd + '/' + rek6 + '/' + dcetak2 + '/' + cetak_bb, '_blank');
			} else if (cetak == '2') {
				var url = "<?php echo site_url(); ?>akuntansi/cetakbb";
				window.open(url + '/' + dcetak + '/' + skpd + '/' + rek6 + '/' + dcetak2 + '/' + cetak_bb, '_blank');
			}
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

			<h3>CETAK BUKU BESAR</h3>
			<div>
				<p align="right">
				<table id="sp2d" title="Cetak Buku Besar" style="width:870px;height:300px;">
					<tr>
						<td><input type="radio" name="cetak" value="1" onclick="opt(this.value)" />Keseluruhan
						</td>
						<td><input type="radio" name="cetak" value="2" id="status" onclick="opt(this.value)" />SKPD</td>
					</tr>
					<tr id="skpd">
						<td width="20%" height="40"><B>SKPD</B></td>
						<td width="80%"><input id="sskpd" name="sskpd" style="width: 150px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 500px; border:0;" /></td>
					</tr>
					<tr>
						<td width="20%" height="40"><B>REKENING</B></td>
						<td width="80%"><input id="kdrek6" name="kdrek6" style="width: 150px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmrek6" name="nmrek6" style="width: 500px; border:0;" /></td>
					</tr>

					<tr>
						<td width="20%" height="40"><B>PERIODE</B></td>
						<td width="80%"><input id="dcetak" name="dcetak" type="text" style="width:155px" />&nbsp;&nbsp;s/d&nbsp;&nbsp;<input id="dcetak2" name="dcetak2" type="text" style="width:155px" /></td>
					</tr>
					<!-- <tr >
			<td width="20%" height="40" ><B>PENANDA TANGAN</B></td>
			<td width="80%"><input id="ttd" name="ttd" type="text"  style="width:230px" /></td>
		</tr> -->
					<tr>
						<td width="20%" height="40">&nbsp</td>
						<td width="40%"> <input type="button" value="LAYAR" onclick="javascript:cetakbb(0);" style="height:40px;width:100px">
						<input type="button" value="PDF" onclick="javascript:cetakbb(1);" style="height:40px;width:100px">
						<input type="button" value="EXCEL" onclick="javascript:cetakbb(2);" style="height:40px;width:100px">
						</td>
					</tr>
					<tr>
						<td>&nbsp</td>
						<td>&nbsp</td>
					</tr>
				</table>
				</p>
			</div>
		</div>
	</div>


</body>


</html>