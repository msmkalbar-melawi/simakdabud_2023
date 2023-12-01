<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
	<link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css" />

	<style>
		#tagih {
			position: relative;
			width: 922px;
			height: 100px;
			padding: 0.4em;
		}

		input.right {
			text-align: right;
		}
	</style>
</head>

<body>

	<div id="content">
		<h3>CETAK LAPORAN PERUBAHAN SALDO ANGGARAN LEBIH </h3>
		
			<p align="right">
			<table id="sp2d" title="Cetak" style="width:100%;height:200px;">
					<!-- <tr>
						<td colspan="2" align="center">
							<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:hit(1);">Perhitungan Kembali LPSAL</a>
						</td>
					</tr> -->
				<tr>
								<td width="30%" style="border-bottom:hidden;">
								Periode&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<select name="bulan" id="bulan">
									<option value="1">Januari</option>
									<option value="2">Februari</option>
									<option value="3">Maret</option>
									<option value="4">April </option>
									<option value="5">Mei </option>
									<option value="6">Juni </option>
									<option value="7">Juli </option>
									<option value="8">Agustus </option>
									<option value="9">September </option>
									<option value="10">Oktober </option>
									<option value="11">Nopember </option>
									<option value="12">Desember </option>
								</select>
				</td>
				</tr>

				<tr>
					<td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">Label Audited&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
					<select name="label" id="label">
							<option value="1">Unaudited</option>
							<option value="2">Audited</option>
						</select></td>
				</tr>
				 <tr>
                    <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" width="15%">TTD&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
                    &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
                        &nbsp;<input type="text" id="ttdx1" style="width: 200px;" />
                    </td>
                </tr>

				<tr height="70%">
					<td> Tanggal Tanda Tangan&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
						<!--<input id="qttd2" name="qttd2" type="checkbox" value="2" onclick="javascript:runEffect2();"/> Tanpa TTD-->
						<!-- <input id="qttd" name="qttd" type="checkbox" value="1" onclick="javascript:runEffect();" /> Dengan TTD &nbsp;&nbsp; -->
						<input type="text" id="tgl_ttd1" style="width: 100px;" />
					</td>
				</tr>
				<tr height="70%">
					<td colspan="2" align="center"><br /></td>
				</tr>
				<!-- <tr>
					<td colspan="2">
						<a id="cetak0" class="easyui-linkbutton" iconCls="icon-print" disabled plain="true" onclick="javascript:cetak1(0);">Cetak Layar (CALK)</a>
						<a id="cetak0" class="easyui-linkbutton" iconCls="icon-pdf" disabled plain="true" onclick="javascript:cetak1(1);">Cetak PDF (CALK)</a>
						<a id="cetak1" class="easyui-linkbutton" iconCls="icon-excel" disabled plain="true" onclick="javascript:cetak1(2);">Cetak excel (CALK)</a>
						<a id="cetak2" class="easyui-linkbutton" iconCls="icon-word" disabled plain="true" onclick="javascript:cetak1(3);">Cetak word (CALK)</a>
					</td>
				</tr> -->
				<tr>
					<td colspan="2" align="center">
						<a id="cetak0" class="button"  iconCls="icon-print" plain="true" onclick="javascript:cetak(0);"><i class="fa fa-print"></i> Cetak Layar</a>
						<a id="cetak0" class="button"  iconCls="icon-pdf" plain="true" onclick="javascript:cetak(1);"><i class="fa fa-pdf"></i> Cetak PDF</a>
						<!--<a id="cetak1" class="button"  iconCls="icon-excel" disabled plain="true" onclick="javascript:cetak(2);">Cetak excel</a>
						<a id="cetak2" class="button"  iconCls="icon-word" disabled plain="true" onclick="javascript:cetak(3);">Cetak word</a>-->
					</td>
				</tr>
				<tr height="70%">
					<td colspan="2" align="center"><br /></td>
				</tr>
				<tr height="70%" >
					<td colspan="2" align="center" style="visibility:hidden">
						<DIV id="load"> <b>Sedang Proses Perhitungan</b><IMG SRC="<?php echo base_url(); ?>assets/images/loading.gif" WIDTH="300" HEIGHT="50" BORDER="0" ALT=""></DIV>
					</td>
				</tr>
			</table>
			</p>
	</div>

	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
	<script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>

	<script type="text/javascript">
		var nip = '';
		var kdskpd = '';
		var kdrek5 = '';
		var pilihttd = '';

		$(document).ready(function() {
			$("#accordion").accordion();
			$("#dialog-modal").dialog({
				height: 100,
				width: 922
			});

			$('#tgl_ttd1').datebox({
				required: true,
				formatter: function(date) {
					var y = date.getFullYear();
					var m = date.getMonth() + 1;
					var d = date.getDate();
					return y + '-' + m + '-' + d;
				}
			});

			$('#qttd')._propAttr('checked', false);
		});

		function hit($hit) {
			var bulan = document.getElementById("bulan").value;
			var ht = $hit;
			if (ht == "1") {
				document.getElementById('load').style.visibility = 'visible';
				$(function() {
					$.ajax({
						type: 'POST',
						dataType: "json",
						url: "<?php echo base_url(); ?>index.php/c_lpsal/hitung_lpsal/" + bulan,
						success: function(data) {
							if (data = 1) {
								document.getElementById('load').style.visibility = 'hidden';
								alert('Penghitungan Selesai');
								$('#cetak1').linkbutton('enable');
								$('#cetak2').linkbutton('enable');
							}
						}

					});
				});
			}
		}

		// function cet($hit) {
		// 	var ht = $hit;
		// 	if (ht == "1") {
		// 		$('#cetak0').linkbutton('enable');
		// 		$('#cetak1').linkbutton('enable');
		// 		$('#cetak2').linkbutton('enable');
		// 	}
		// }

		// function runEffect() {
		// //	$('#qttd')._propAttr('checked', false);
		// 	document.getElementById("qttd").checked = true;
		// 	pilihttd = "1";
		// };

		// function runEffect2() {
		// 	$('#qttd')._propAttr('checked', false);
		// 	pilihttd = "2";
		// };
		$('#ttdx1').combogrid({
                panelWidth: 180,
                url: '<?php echo base_url(); ?>/index.php/lamp_perda/load_ttd_perda',
                idField: 'nip',
                textField: 'nama',
                mode: 'remote',
                fitColumns: true,
                columns: [
                    [{
                        field: 'nama',
                        title: 'NAMA',
                        align: 'left',
                        width: 170
                    }]
                ],
                onSelect: function(rowIndex, rowData) {
                    nip = rowData.nip;
                }
            });
		function cetak($ctk) {

			// var txt;
			// var r = confirm("Pilih [OKE] untuk Cetak menggunakan Tanda Tangan");
			// if (r == true) {
			// 	txt = "1";
			// } else {
			// 	txt = "0";
			// }

			
			var ctglttd = $('#tgl_ttd1').datebox('getValue');
			var bulan = document.getElementById("bulan").value;
			var label = document.getElementById("label").value;
			var cttd=$('#ttdx1').combogrid('getValue');

			//alert(bulan);
			var cetak = $ctk;
			var url = "<?php echo site_url(); ?>c_lpsal/ctk_lpsal";
			window.open(url + '/' + cetak + '/' + ctglttd + '/' + bulan + '/' + label +'/' +cttd, '_blank');
			window.focus();
		}

		// function cetak1($ctk) {

		// 	// var txt;
		// 	// var r = confirm("Pilih [OKE] untuk Cetak menggunakan Tanda Tangan");
		// 	// if (r == true) {
		// 	// 	txt = "1";
		// 	// } else {
		// 	// 	txt = "0";
		// 	// }

		// 	var initx = pilihttd;
		// 	var ctglttd = $('#tgl_ttd1').datebox('getValue');
		// 	var bulan = document.getElementById("bulan").value;
		// 	var label = document.getElementById("label").value;

		// 	var cetak = $ctk;
		// 	var url = "<?php echo site_url(); ?>c_lpsal/ctk_lpsal_calk";
		// 	window.open(url + '/' + cetak + '/' + initx + '/' + ctglttd + '/' + bulan + '/' + label, '_blank');
		// 	window.focus();
		// }
	</script>

</body>

</html>