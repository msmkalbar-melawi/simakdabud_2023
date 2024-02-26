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
    <h3>CETAK LAPORAN OPERASIONAL KONSOLIDASI</h3>
      <p align="right">
      <table id="sp2d" title="Cetak" style="width:922px;height:200px;">
        <tr>
          <td width="922px" colspan="2"><input type="radio" name="cetak" value="1" onclick="opt(this.value)" /><b>Keseluruhan</b></td>
        </tr>
        <tr>
          <td width="922px" colspan="2"><input type="radio" name="cetak" value="3" id="status" onclick="opt(this.value)" /><b>Per SKPD</b>
            <div id="kode_org">
              <table style="width:100%;" border="0">
                <tr>
                  <td width="210px" height="40%"><B>SKPD&nbsp;&nbsp;</B></td>
                  <td width="900px"><input id="org" name="org" style="width: 160px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nm_org" name="nm_org" style="width: 300px; border:0;" /></td>
                </tr>
              </table>
            </div>
          </td>
        </tr>
        <tr hidden>
          <td width="210px" colspan="2"><input type="radio" name="cetak" value="2" id="status" onclick="opt(this.value)" /><b>Per Unit</b>
            <div id="kode_skpd">
              <table style="width:100%;" border="0">
                <tr>
                  <td width="210px" height="40%"><B>Unit&nbsp;&nbsp;</B></td>
                  <td width="900px"><input id="sskpd" name="sskpd" style="width: 160px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 250px; border:0;" /></td>
                </tr>
              </table>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <div id="div_periode">
              <table style="width:100%;" border="0">
                <td width="200px" height="40%"><B>Bulan</B></td>
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
                <td width="160px" height="40%"><B>Jenis</B></td>
                <td>
                  <select name="jenis" id="jenis">
                    <option value="1">LO</option>
                    <!-- <option value="2">LO RINCI</option> -->
                  </select>
                </td>
              </table>
            </div>
          </td>
        </tr>
        <tr>
					<td colspan="2">
						<div id="div_periode">
							<table style="width:100%;" border="0">
								<td width="187" height="40%"><B>Penandatangan</B></td>
								<td width="900px"><input type="text" id="ttd" style="width: 100px;" />
								</td>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div id="div_periode">
							<table style="width:100%;" border="0">
								<td width="200" height="40%"><B>Tanggal TTD</B></td>
								<td width="900px"><input type="text" id="tgl_ttd" style="width: 100px;" />
								</td>
							</table>
						</div>
					</td>
				</tr>
        <tr>
          <td colspan="2"><b>Cetak Per Jenis</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a class="button" iconCls="icon-print" plain="true" onclick="javascript:cetak(1);"><i class="fa fa-print"></i> Cetak Layar</a>
            <a class="button" iconCls="icon-pdf" plain="true" onclick="javascript:cetak(0);"><i class="fa fa-pdf"></i> Cetak PDF</a>
            <!-- <a class="button" iconCls="icon-excel" plain="true" onclick="javascript:cetak(2);"><i class="fa fa-excel"></i> Cetak Excel</a>
            <a class="button" iconCls="icon-word" plain="true" onclick="javascript:cetak(3);"><i class="fa fa-word"></i> Cetak Word</a> -->
          </td>
        </tr>
        <tr>
          <td colspan="2"><b>Cetak Per Objek </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a class="button" iconCls="icon-print" plain="true" onclick="javascript:cetak_objek(1);"><i class="fa fa-print"></i> Cetak Layar</a>
            <a class="button" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_objek(0);"><i class="fa fa-pdf"></i> Cetak PDF</a>
            <!-- <a class="button" iconCls="icon-excel" plain="true" onclick="javascript:cetak(2);"><i class="fa fa-excel"></i> Cetak Excel</a>
            <a class="button" iconCls="icon-word" plain="true" onclick="javascript:cetak(3);"><i class="fa fa-word"></i> Cetak Word</a> -->
          </td>
        </tr> <tr>
          <td colspan="2"><b>Cetak Per Rincian </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a class="button" iconCls="icon-print" plain="true" onclick="javascript:cetak_rincian(1);"><i class="fa fa-print"></i> Cetak Layar</a>
            <a class="button" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_rincian(0);"><i class="fa fa-pdf"></i> Cetak PDF</a>
            <!-- <a class="button" iconCls="icon-excel" plain="true" onclick="javascript:cetak(2);"><i class="fa fa-excel"></i> Cetak Excel</a>
            <a class="button" iconCls="icon-word" plain="true" onclick="javascript:cetak(3);"><i class="fa fa-word"></i> Cetak Word</a> -->
          </td>
        </tr>
        <tr>
          <td colspan="2"><b>Cetak Per Sub Rincian </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a class="button" iconCls="icon-print" plain="true" onclick="javascript:cetak_subrincian(1);"><i class="fa fa-print"></i> Cetak Layar</a>
            <a class="button" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_subrincian(0);"><i class="fa fa-pdf"></i> Cetak PDF</a>
            <!-- <a class="button" iconCls="icon-excel" plain="true" onclick="javascript:cetak(2);"><i class="fa fa-excel"></i> Cetak Excel</a>
            <a class="button" iconCls="icon-word" plain="true" onclick="javascript:cetak(3);"><i class="fa fa-word"></i> Cetak Word</a> -->
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
    var bulan = '';

    $(document).ready(function() {
      $("#accordion").accordion();

      $("#dialog-modal").dialog({
        height: 100,
        width: 922
      });

      $('#sskpd').combogrid({
        panelWidth: 630,
        idField: 'kd_skpd',
        textField: 'kd_skpd',
        mode: 'remote',
        url: '<?php echo base_url(); ?>index.php/rka/skpd',
        columns: [
          [{
              field: 'kd_skpd',
              title: 'Kode SKPD',
              width: 160
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
        }
      });

      $('#tgl_ttd').datebox({
				required: true,
				formatter: function(date) {
					var y = date.getFullYear();
					var m = date.getMonth() + 1;
					var d = date.getDate();
					return y + '-' + m + '-' + d;
				}
			});

			$('#ttd').combogrid({
				panelWidth: 200,
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

      $('#org').combogrid({
        panelWidth: 630,
        idField: 'kd_skpd',
        textField: 'kd_skpd',
        mode: 'remote',
        url: '<?php echo base_url(); ?>index.php/akuntansi/list_skpd',
        columns: [
          [{
              field: 'kd_skpd',
              title: 'Kode SKPD',
              width: 160
            },
            {
              field: 'nm_skpd',
              title: 'Nama SKPD',
              width: 500
            }
          ]
        ],
        onSelect: function(rowIndex, rowData) {
          kd_skpd = rowData.kd_skpd;
          $("#nm_skpd").attr("value", rowData.nm_skpd);
          $("#skpd").attr("value", rowData.kd_skpd);

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

      $("#kode_skpd").hide();
      $("#kode_org").hide();

    });

    function submit() {
      if (ctk == '') {
        alert('Pilih Jenis Cetakan');
        exit();
      }
      document.getElementById("frm_ctk").submit();
    }

    function opt(val) {
      ctk = val;
      if (ctk == '1') {
        $("#kode_skpd").hide();
        $("#kode_org").hide();
        // urll ='<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_lo';
      } else if (ctk == '2') {
        $("#kode_skpd").show();
        $("#kode_org").hide();
        // urll ='<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_lo_unit/'+kdskpd+'/'+ctk;
      } else {
        $("#kode_skpd").hide();
        $("#kode_org").show();
      }
      // $('#frm_ctk').attr('action',urll);                        
    }

    function cetak($pilih) {
      var jns = document.getElementById('jenis').value;
      var pilih = $pilih;
      cbulan = $('#bulan').combogrid('getValue');
      var tandatgn = $('#ttd').combogrid('getValue');
			var ttd1 = tandatgn.split(" ").join("n");
			var ctglttd = $('#tgl_ttd').datebox('getValue');
      const base_url = `<?= base_url() ?>`
      if (ctk == 1) {
        if (jns == 1) {
          //alert('Jenis sama dengan 1');
          //exit();
          if (bulan == '') {
            alert("Pilih Bulan dulu");
            return;
          }
          urll = `${base_url}konsolidasi-jenis?bulan=${cbulan}&ttd=${ttd1}&tanggal=${ctglttd}`;
          window.open(urll, '_blank');
          window.focus();
        }

      } else if (ctk == 3) {
        if (jns == 1) {
          /*alert('jenis 1 org');
          exit();*/
          urll = '<?php echo base_url(); ?>index.php/akuntansi_rekon/ctk_lra_lo_pemda_org/' + cbulan + '/' + kd_skpd;
          if (kd_skpd == '') {
            alert("Pilih SKPD dulu");
            exit();
          }
          if (bulan == '') {
            alert("Pilih Bulan dulu");
            exit();
          }

        } 
      }

      //var url    = "<?php echo site_url(); ?>/akuntansi/cetak_lra_lo";    
      window.open(urll + '/' + pilih + '/' + ttd1 + '/' + ctglttd, '_blank');
      window.focus();

    }

    function cetak_objek($pilih) {
      var jns = document.getElementById('jenis').value;
      var pilih = $pilih;
      cbulan = $('#bulan').combogrid('getValue');
      var tandatgn = $('#ttd').combogrid('getValue');
			var ttd1 = tandatgn.split(" ").join("n");
			var ctglttd = $('#tgl_ttd').datebox('getValue');
      if (ctk == 1) {
        if (jns == 1) {
          //alert('Jenis sama dengan 1');
          //exit();
          urll = '<?php echo base_url(); ?>index.php/akuntansi/ctk_lra_lo_pemda_objek/' + cbulan;
          if (bulan == '') {
            alert("Pilih Bulan dulu");
            exit();
          }
        } 

      } else if (ctk == 3) {
        if (jns == 1) {
          /*alert('jenis 1 org');
          exit();*/
          urll = '<?php echo base_url(); ?>index.php/akuntansi_rekon/ctk_lra_lo_pemda_org_objek/' + cbulan + '/' + kd_skpd;
          if (kd_skpd == '') {
            alert("Pilih SKPD dulu");
            exit();
          }
          if (bulan == '') {
            alert("Pilih Bulan dulu");
            exit();
          }

        } 
      }

      //var url    = "<?php echo site_url(); ?>/akuntansi/cetak_lra_lo";    
      window.open(urll + '/' + pilih + '/' + ttd1 + '/' + ctglttd+'/LO OBJEK', '_blank');
      window.focus();

    }
    function cetak_rincian($pilih) {
      var jns = document.getElementById('jenis').value;
      var pilih = $pilih;
      cbulan = $('#bulan').combogrid('getValue');
      var tandatgn = $('#ttd').combogrid('getValue');
			var ttd1 = tandatgn.split(" ").join("n");
			var ctglttd = $('#tgl_ttd').datebox('getValue');
      if (ctk == 1) {
        if (jns == 1) {
          //alert('Jenis sama dengan 1');
          //exit();
          urll = '<?php echo base_url(); ?>index.php/akuntansi/ctk_lra_lo_pemda_rincian/' + cbulan;
          if (bulan == '') {
            alert("Pilih Bulan dulu");
            exit();
          }
        } 

      } else if (ctk == 3) {
        if (jns == 1) {
          /*alert('jenis 1 org');
          exit();*/
          urll = '<?php echo base_url(); ?>index.php/akuntansi_rekon/ctk_lra_lo_pemda_org_rincian/' + cbulan + '/' + kd_skpd;
          if (kd_skpd == '') {
            alert("Pilih SKPD dulu");
            exit();
          }
          if (bulan == '') {
            alert("Pilih Bulan dulu");
            exit();
          }

        } 
      }

      //var url    = "<?php echo site_url(); ?>/akuntansi/cetak_lra_lo";    
      window.open(urll + '/' + pilih + '/' + ttd1 + '/' + ctglttd+'/LO Rincian', '_blank');
      window.focus();

    }

    function cetak_subrincian($pilih) {
      var jns = document.getElementById('jenis').value;
      var pilih = $pilih;
      cbulan = $('#bulan').combogrid('getValue');
      var tandatgn = $('#ttd').combogrid('getValue');
			var ttd1 = tandatgn.split(" ").join("n");
			var ctglttd = $('#tgl_ttd').datebox('getValue');
      const base_url = `<?= base_url() ?>konsolidasi-sub-rinci`
      if (ctk == 1) {
        if (jns == 1) {
          //alert('Jenis sama dengan 1');
          //exit();
          if (bulan == '') {
            alert("Pilih Bulan dulu");
            exit();
          }
          urll = `${base_url}?bulan=${cbulan}&ttd=${ttd1}&tanggal=${ctglttd}&pilih=${pilih}`;
          window.open(urll, '_blank');
          window.focus();
        } 

      } else if (ctk == 3) {
        if (jns == 1) {
          /*alert('jenis 1 org');
          exit();*/
          urll = '<?php echo base_url(); ?>index.php/akuntansi_rekon/ctk_lra_lo_pemda_org_subrincian/' + cbulan + '/' + kd_skpd;
          if (kd_skpd == '') {
            alert("Pilih SKPD dulu");
            exit();
          }
          if (bulan == '') {
            alert("Pilih Bulan dulu");
            exit();
          }

        } 
      }

      //var url    = "<?php echo site_url(); ?>/akuntansi/cetak_lra_lo";    
      window.open(urll + '/' + pilih + '/' + ttd1 + '/' + ctglttd+'/LO Rincian', '_blank');
      window.focus();

    }

    function runEffect() {
      var selectedEffect = 'blind';
      var options = {};
      $("#tagih").toggle(selectedEffect, options, 500);
    };

    function pilih() {
      op = '1';
    };
  </script>
</body>

</html>