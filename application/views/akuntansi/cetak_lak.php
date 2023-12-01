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
	<style>
		#tagih {
			position: relative;
			width: 922px;
			height: 100px;
			padding: 0.4em;
		}
	</style>

	<script type="text/javascript">
		var nip = '';
		var kdskpd = '';
		var kdrek5 = '';
		var bulan = '';
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

			$('#dcetak').datebox({
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

			$("#kode_skpd").hide();
		});

		function submit() {
			if (ctk == '') {
				alert('Pilih Jenis Cetakan');
				exit();
			}
			document.getElementById("frm_ctk").submit();
		}

		function runEffectx() {
			$('#qttd2')._propAttr('checked', false);
			pilihttd = "1";
		};

		function runEffect2x() {
			$('#qttd')._propAttr('checked', false);
			pilihttd = "2";
		};

		function opt(val) {
			ctk = val;
			if (ctk == '1') {
				// urll ='<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_lo';
			} else if (ctk == '2') {
				$("#kode_skpd").show();
				// urll ='<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_lo_unit/'+kdskpd+'/'+ctk;
			} else {
				exit();
			}
			// $('#frm_ctk').attr('action',urll);                        
		}


		function cetak3($pilih) {
			var ctk = "1";

			var pilih = $pilih;
			cbulan = $('#bulan').combogrid('getValue');
			if (ctk == 1) {

				var initx = pilihttd;
				var ctglttd = $('#tgl_ttd1').datebox('getValue');

				urll = '<?php echo base_url(); ?>index.php/akuntansi/rpt_lak_kota_apbd/' + cbulan;
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			} else {
				urll = '<?php echo base_url(); ?>index.php/akuntansi/rpt_lak_unit/' + cbulan + '/' + kdskpd;
				if (kdskpd == '') {
					alert("Pilih Unit dulu");
					exit();
				}
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			}

			//var url    = "<?php echo site_url(); ?>/akuntansi/cetak_lra_lo";	  
			window.open(urll + '/' + pilih + '/' + initx + '/' + ctglttd, '_blank');
			window.focus();

		}

		function cetak4($pilih) {
			var ctk = "1";

			var pilih = $pilih;
			cbulan = $('#bulan').combogrid('getValue');
			if (ctk == 1) {

				var initx = pilihttd;
				var ctglttd = $('#tgl_ttd1').datebox('getValue');

				urll = '<?php echo base_url(); ?>index.php/akuntansi/rpt_lak_kota_apbd_new/' + cbulan;
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			} else {
				urll = '<?php echo base_url(); ?>index.php/akuntansi/rpt_lak_unit/' + cbulan + '/' + kdskpd;
				if (kdskpd == '') {
					alert("Pilih Unit dulu");
					exit();
				}
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			}

			//var url    = "<?php echo site_url(); ?>/akuntansi/cetak_lra_lo";	  
			window.open(urll + '/' + pilih + '/' + initx + '/' + ctglttd, '_blank');
			window.focus();

		}

		function cetak9($pilih) {
			var ctk = "1";
			var jns = "2";
			var pilih = $pilih;
			cbulan = $('#bulan').combogrid('getValue');
			cttd = $('#ttdx1').combogrid('getValue');
			var ttd1 = cttd.split(" ").join("n");
			var ctglttd = $('#tgl_ttd1').datebox('getValue');
			if (ctk == 1) {

				var initx = pilihttd;
				urll = '<?php echo base_url(); ?>clak/rpt_lak_kota_apbd_rinci/'+cbulan+'/'+cttd+'/'+ctglttd;
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			} else {
				urll = '<?php echo base_url(); ?>index.php/akuntansi/rpt_lak_unit/' + cbulan + '/' + kdskpd;
				if (kdskpd == '') {
					alert("Pilih Unit dulu");
					exit();
				}
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			}

			//var url    = "<?php echo site_url(); ?>/akuntansi/cetak_lra_lo";	  
			window.open(urll + '/' + pilih + '/' + jns, '_blank');
			window.focus();

		}

		function cetak10($pilih) {
			var ctk = "1";
			var jns = "1";
			var pilih = $pilih;
			cbulan = $('#bulan').combogrid('getValue');
			if (ctk == 1) {

				var initx = pilihttd;
				var ctglttd = $('#tgl_ttd1').datebox('getValue');

				urll = '<?php echo base_url(); ?>clak/rpt_lak_kota_apbd_rinci/' + cbulan;
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			} else {
				urll = '<?php echo base_url(); ?>index.php/akuntansi/rpt_lak_unit/' + cbulan + '/' + kdskpd;
				if (kdskpd == '') {
					alert("Pilih Unit dulu");
					exit();
				}
				if (bulan == '') {
					alert("Pilih Bulan dulu");
					exit();
				}
			}

			//var url    = "<?php echo site_url(); ?>/akuntansi/cetak_lra_lo";	  
			window.open(urll + '/' + pilih + '/' + initx + '/' + ctglttd + '/' + jns, '_blank');
			window.focus();

		}

		$(function() {
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
		});

		function runEffect() {
			var selectedEffect = 'blind';
			var options = {};
			$("#tagih").toggle(selectedEffect, options, 500);
		};

		function pilih() {
			op = '1';
		};
	</script>

	<STYLE TYPE="text/css">
		input.right {
			text-align: right;
		}
	</STYLE>

</head>

<body>

	<div id="content">



		<h3>CETAK LAPORAN ARUS KAS KONSOLIDASI</h3>
		

			<p align="right">
			<table id="sp2d" title="Cetak" style="width:922px;height:200px;">
				<!--
        <tr><td width="922px" colspan="2"><input type="radio" name="cetak" value="1" onclick="opt(this.value)" /><b>Keseluruhan</b></td></tr>
        <tr><td width="922px" colspan="2"><input type="radio" name="cetak" value="2" id="status" onclick="opt(this.value)" /><b>Per Unit</b>
                    <div id="kode_skpd">
                        <table style="width:100%;" border="0">
                            <tr >
                    			<td width="22px" height="40%" ><B>Unit&nbsp;&nbsp;</B></td>
                    			<td width="900px"><input id="sskpd" name="sskpd" style="width: 100px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 670px; border:0;" /></td>
                    		</tr>
                        </table> 
                    </div>
        </td>
        </tr> -->
				<tr>
					<td colspan="2">
						<div id="div_periode">
							<table style="width:100%;" border="0">
								<td width="150px" height="40%"><B>Bulan</B></td>
								<td width="900px"><input type="text" id="bulan" style="width: 100px;" />
								</td>
							</table>
						</div>
					</td>
				</tr>
				<tr>
				<td colspan="2">
						<div id="div_periode">
							<table style="width:100%;" border="0">
							<td width="15%"><b>TTD
							&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
								&nbsp;</b><input type="text" id="ttdx1" style="width: 200px;" />
							</td>
							</table>
						</div>
					</td>
                </tr>

				<tr>
					<td colspan="2">
						<div id="div_periode">
							<table style="width:100%;" border="0">
								<td width="15%"><b> Tanggal TTD &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;</b>
									<!--<input id="qttd2" name="qttd2" type="checkbox" value="2" onclick="javascript:runEffect2();"/> Tanpa TTD-->
									<!-- <input id="qttd" name="qttd" type="checkbox" value="1" onclick="javascript:runEffect();" /> Dengan TTD &nbsp;&nbsp; -->
									<input type="text" id="tgl_ttd1" style="width: 100px;" />
									</td>
							</table>
						</div>
					</td>
				</tr>
				<!-- <tr>
					<td colspan="2">
						<input id="qttd2" name="qttd2" type="checkbox" value="2" onclick="javascript:runEffect2x();" /> Tanpa TTD
						<input id="qttd" name="qttd" type="checkbox" value="1" onclick="javascript:runEffectx();" /> Dengan TTD &nbsp;&nbsp;
						<input type="text" id="tgl_ttd1" style="width: 100px;" />
					</td>
				</tr> -->

				<!--<tr >
			<td colspan="2"><a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak(1);">Cetak</a>
            <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak(2);">Cetak excel</a>
            <a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:cetak(3);">Cetak word</a></td>
		</tr>-->

				<!-- <tr >
			<td colspan="2"><a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak3(0);">CETAK LAK (APBD)</a>
            <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak3(1);">CETAK LAK (APBD) PDF</a>
            <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak3(2);">CETAK LAK (APBD) Excel</a></td>
		</tr>-->

				<tr>
					<td colspan="2" align = "center">
						<a class="button"  iconCls="icon-print" plain="true" onclick="javascript:cetak9(0);"><i class="fa fa-print"></i> Cetak Layar</a>
						<a class="button"  iconCls="icon-pdf" plain="true" onclick="javascript:cetak9(1);"><i class="fa fa-pdf"></i> Cetak PDF</a>
						<a class="button"  iconCls="icon-excel" plain="true" onclick="javascript:cetak9(2);"><i class="fa fa-excel"></i> Cetak Excel</a>
					</td>
				</tr>
				<!--
        <tr >
			<td colspan="2"><a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak4(0);">CETAK LAK TUKD (APBD)</a>
            <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak4(1);">CETAK LAK TUKD (APBD) PDF</a>
            <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak4(2);">CETAK LAK TUKD (APBD) Excel</a></td>
		</tr>
        -->
				<tr hidden>
					<td colspan="2"><a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak10(0);">CETAK LAK RINCI</a>
						<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak10(1);">CETAK LAK RINCI (PDF)</a>
						<a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak10(2);">CETAK LAK RINCI (EXCEL)</a>
					</td>
				</tr>

			</table>
			</p>


		</div>



</body>

</html>