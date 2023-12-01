<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css" />

    <STYLE TYPE="text/css">
        input.right {
            text-align: right;
        }
    </STYLE>

</head>

<body>

    <div id="content">


        <h3>CETAK PERBUP LAMP I.1</h3>
            <p align="center">
            <table id="sp2d" title="Cetak Perda Lamp. 1" width="100%" border="0">

                <tr>
                    <td width="20%" height="40%"><B>SKPD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</B></td>
                    <td><input id="sskpd" name="sskpd" style="width: 160px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 450px; border:0;" /></td>
                </tr>

                <tr>
					<td width="20%"> Jenis Anggaran</td>
					<td>
						<input id="anggaran" name="anggaran" style="width: 180px;" />
					</td>
				</tr>


                <tr>
                    <td width="20%"> <b>Periode</b></td>
                    <td width="70%"><select name="bulan" id="bulan">
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
                        </select></td>
                </tr>
                <tr hidden>
                    <td> <b>Jenis</b></td>
                    <td><select name="jenis" id="jenis">
                            <option value="4">Jenis</option>
                            <option value="6">Objek</option>
                            <option value="8">Rincian Objek</option>
                            <option value="12">Sub Rincian Objek</option>
                        </select></td>
                </tr>
                <tr hidden>
                    <td> <b>Anggaran</b></td>
                    <td><select name="anggaran" id="anggaran">
                            <option value="1">Penyusunan</option>
                            <option value="2">Penyempurnaan</option>
                            <option value="3">Perubahan</option>
                        </select></td>
                </tr>

                <tr>
                    <td> <b>Label Audited</b></td>
                    <td><select name="label" id="label">
                            <option value="1">Unaudited</option>
                            <option value="2">Audited</option>
                            <option value="0">Kosong</option>
                        </select></td>
                </tr>

                <tr>
                    <td width="15%"><b>Penandatangan&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
                    <td><input type="text" id="ttdx1" style="width: 200px;" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input id="qttd2" name="qttd2" type="checkbox" value="2" onclick="javascript:runEffect2();" /> Tanpa TTD
                        <input id="qttd" name="qttd" type="checkbox" value="1" onclick="javascript:runEffect();" /> Dengan TTD &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
                        <input type="text" id="tgl_ttd" style="width: 150px;" />
                    </td>
                </tr>

                <!--<tr>
	<td>Penandatanganan</td>
	<td><input type="text" id="ttd" style="width: 200px;" /> </td> 
	</tr>-->
                <tr>
                    <td width ="16%"><b>Baris</b></td>
                    <td>&nbsp;<input type="number" id="baris" name="baris" style="width: 40px;"value="3" /></td>
                </tr>
                <tr>
                    <td> &nbsp;&nbsp;</td>
                    <td> &nbsp;&nbsp;</td>
                </tr>
				 <tr hidden>
                    <td ><b> Cetak PERDA</b></td>
                    <td align="left">
                        <a class="button" iconCls="icon-print" plain="true" onclick="javascript:cetak64(0);"><i class="fa fa-print"></i> Layar</a>
                        <a class="button" iconCls="icon-pdf" plain="true" onclick="javascript:cetak64(1);"><i class="fa fa-pdf"></i> PDF</a>
                        <a class="button" iconCls="icon-excel" plain="true" onclick="javascript:cetak64(2);"><i class="fa fa-excel"></i> Excel</a>
                    </td>
                </tr>
                <tr hidden>
                    <td><b> Cetak PERDA RINGKAS</b></td>
                    <td align="left">
                        <a class="button" iconCls="icon-print" plain="true" onclick="javascript:cetak64_ringkas(0);"><i class="fa fa-print"></i> Layar</a>
                        <a class="button" iconCls="icon-pdf" plain="true" onclick="javascript:cetak64_ringkas(1);"><i class="fa fa-pdf"></i> PDF</a>
                        <a class="button" iconCls="icon-excel" plain="true" onclick="javascript:cetak64_ringkas(2);"><i class="fa fa-excel"></i> Excel</a>
                    </td>
                </tr>
                <tr>
                    <td><b> Cetak PERBUP</b></td>
                    <td align="left">
                        <a class="button" iconCls="icon-print" plain="true" onclick="javascript:cetakSE(0);"><i class="fa fa-print"></i> Layar</a>
                        <a class="button" iconCls="icon-pdf" plain="true" onclick="javascript:cetakSE(1);"><i class="fa fa-pdf"></i> PDF</a>
                        <a class="button" iconCls="icon-excel" plain="true" onclick="javascript:cetakSE(2);"><i class="fa fa-excel"></i> Excel</a>
                    </td>
                </tr>
                <tr>
                    <td><b> Cetak PERBUP SUB RO</b></td>
                    <td align="left">
                        <a class="button" iconCls="icon-print" plain="true" onclick="javascript:cetakSE_sub_ro(0);"><i class="fa fa-print"></i> Layar</a>
                        <a class="button" iconCls="icon-pdf" plain="true" onclick="javascript:cetakSE_sub_ro(1);"><i class="fa fa-pdf"></i> PDF</a>
                        <a class="button" iconCls="icon-excel" plain="true" onclick="javascript:cetakSE_sub_ro(2);"><i class="fa fa-excel"></i> Excel</a>
                    </td>
                </tr>
                <tr hidden>
                    <td> <b>Cetak PERBUP</b></td>
                    <td align="left">
                        <a class="button" iconCls="icon-print" plain="true" onclick="javascript:cetak64_perwa(0);"><i class="fa fa-print"></i> Layar</a>
                        <a class="button" iconCls="icon-pdf" plain="true" onclick="javascript:cetak64_perwa(1);"><i class="fa fa-pdf"></i> PDF</a>
                        <a class="button" iconCls="icon-excel" plain="true" onclick="javascript:cetak64_perwa(2);"><i class="fa fa-excel"></i> Excel</a>
                    </td>
                </tr>
                <tr hidden>
                    <td> <b>Cetak PERBUP PENJABARAN</b></td>
                    <td align="left">
                        <a class="button" iconCls="icon-print" plain="true" onclick="javascript:cetak64_penjabaran(0);"><i class="fa fa-print"></i> Layar</a>
                        <a class="button" iconCls="icon-pdf" plain="true" onclick="javascript:cetak64_penjabaran(1);"><i class="fa fa-pdf"></i> PDF</a>
                        <a class="button" iconCls="icon-excel" plain="true" onclick="javascript:cetak64_penjabaran(2);"><i class="fa fa-excel"></i> Excel</a>
                    </td>
                </tr>
                <!--<tr>
        <td> Cetak Rek. (Perwa)</td> 
        <td align="left"> 
        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak64_p(0);">Layar</a>
        <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak64_p(1);">PDF</a>
        <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak64_p(2);">excel</a>
        </td>
    </tr>-->
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
        var kode = '';
        var pilihttd = '';

        $(document).ready(function() {
            $("#accordion").accordion();
            $("#dialog-modal").dialog({
                height: 100,
                width: 922
            });
        });

        $(function() {
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
                            width: 150
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

            $('#anggaran').combogrid({
				panelWidth: 400,
				idField: 'kode',
				textField: 'nama',
				mode: 'remote',
				url: '<?php echo base_url(); ?>index.php/rka_ro/load_jang/',
				columns: [
					[{
						field: 'nama',
						title: 'Nama',
						width: 400
					}]
				],
				onSelect: function(rowIndex, rowData) {
					kd_jang = rowData.kode;
				}
			});
        });

        $(function() {
            $('#tgl_ttd').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });

            $(function() {
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
        });

        function opt(val) {
            ctk = val;
            if (ctk == '1') {
                $("#kode_skpd").hide();
            } else {
                $("#kode_skpd").show();
            }
        }

        function runEffect() {
            $('#qttd2')._propAttr('checked', false);
            pilihttd = "1";
        };

        function runEffect2() {
            $('#qttd')._propAttr('checked', false);
            pilihttd = "2";
        };

        function cetak(pilih) {
            var initx = pilihttd;
            var ttdperdax = $('#ttdx1').datebox('getValue');
            var ttdperda = ttdperdax.split(" ").join("n");
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            var label = document.getElementById("label").value;
            var jenis = document.getElementById("jenis").value;
            //var ttd1 = $('#ttd').combogrid('getValue');     
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            if (ctglttd == '') {
                ctglttd = "-";
            }
            /*if(ttd1==''){
            	ttd="-";
            }else{
            	ttd=ttd1.split(' ').join('abc');
            }*/
            var skpd = kdskpd;
            var url = "<?php echo site_url(); ?>/perda/cetak_perda_lampI_2";
            window.open(url + '/' + bulan + '/' + pilih + '/' + anggaran + '/' + skpd + '/' + jenis + '/' + ctglttd + '/' + initx + '/' + ttdperda + '/' + label + '/PMK_LAMP.II.A', '_blank');
            window.focus();
        }

        function cetak33(pilih) {
            var initx = pilihttd;
            var ttdperdax = $('#ttdx1').datebox('getValue');
            var ttdperda = ttdperdax.split(" ").join("n");
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            var label = document.getElementById("label").value;
            var jenis = document.getElementById("jenis").value;
            //var ttd1 = $('#ttd').combogrid('getValue');     
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            if (ctglttd == '') {
                ctglttd = "-";
            }

            var skpd = kdskpd;
            var url = "<?php echo site_url(); ?>/perda/cetak_perda_lampI_233";
            window.open(url + '/' + bulan + '/' + pilih + '/' + anggaran + '/' + skpd + '/' + jenis + '/' + ctglttd + '/' + initx + '/' + ttdperda + '/' + label + '/PMK_LAMP.II.A', '_blank');
            window.focus();
        }

        function cetak64(pilih) {
            var initx = pilihttd;
            var ttdperdax = $('#ttdx1').datebox('getValue');
            var ttdperda = ttdperdax.split(" ").join("n");
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            var label = document.getElementById("label").value;
            var jenis = document.getElementById("jenis").value;
            var baris = document.getElementById('baris').value;
            //var ttd1 = $('#ttd').combogrid('getValue');     
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            if (ctglttd == '') {
                ctglttd = "-";
            }
            /*if(ttd1==''){
            	ttd="-";
            }else{
            	ttd=ttd1.split(' ').join('abc');
            }*/
            var skpd = kdskpd;
            var url = "<?php echo site_url(); ?>perdase/lampiran_13";
            window.open(url + '/' + bulan + '/' + pilih + '/' + anggaran + '/' + skpd + '/' + jenis + '/' + ctglttd + '/' + initx + '/' + ttdperda + '/' + label + '/' + baris + '/PMK_LAMP.II.A', '_blank');
            window.focus();
        }

        function cetak64_ringkas(pilih) {
            var initx = pilihttd;
            var ttdperdax = $('#ttdx1').datebox('getValue');
            var ttdperda = ttdperdax.split(" ").join("n");
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            var label = document.getElementById("label").value;
            var jenis = document.getElementById("jenis").value;
            var baris = document.getElementById('baris').value;
            //var ttd1 = $('#ttd').combogrid('getValue');     
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            if (ctglttd == '') {
                ctglttd = "-";
            }
            /*if(ttd1==''){
            	ttd="-";
            }else{
            	ttd=ttd1.split(' ').join('abc');
            }*/
            var skpd = kdskpd;
            var url = "<?php echo site_url(); ?>perda/cetak_perda_lampI_2_akun64_perda_ringkas";
            window.open(url + '/' + bulan + '/' + pilih + '/' + anggaran + '/' + skpd + '/' + jenis + '/' + ctglttd + '/' + initx + '/' + ttdperda + '/' + label + '/' + baris + '/PMK_LAMP.II.A', '_blank');
            window.focus();
        }

        function cetakSE(pilih) {
            var initx = pilihttd;
            var ttdperdax = $('#ttdx1').datebox('getValue');
            var ttdperda = ttdperdax.split(" ").join("n");
            // alert(ttdperda);
            var bulan = document.getElementById("bulan").value;
            var anggaran = $('#anggaran').combogrid('getValue');
            // alert(anggaran);
            var label = document.getElementById("label").value;
            var jenis = document.getElementById("jenis").value;
            var baris = document.getElementById('baris').value;
            //var ttd1 = $('#ttd').combogrid('getValue');     
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            if (ctglttd == '') {
                ctglttd = "-";
            }
            /*if(ttd1==''){
            	ttd="-";
            }else{
            	ttd=ttd1.split(' ').join('abc');
            }*/
            var skpd = kdskpd;
            var url = "<?php echo site_url(); ?>perdase/cetak_perbup_lampI_2_akunSE";
            window.open(url + '/' + bulan + '/' + pilih + '/' + '3' + '/' + skpd + '/' + '4' + '/' + ctglttd + '/' + initx + '/' + ttdperda + '/' + label + '/' + baris + '/'+ anggaran + '/PMK_LAMP.III.A', '_blank');
            window.focus();
        }

        function cetakSE_sub_ro(pilih) {
            var initx = pilihttd;
            var ttdperdax = $('#ttdx1').datebox('getValue');
            var ttdperda = ttdperdax.split(" ").join("n");
            var bulan = document.getElementById("bulan").value;
            var anggaran = $('#anggaran').combogrid('getValue');
            var label = document.getElementById("label").value;
            var jenis = document.getElementById("jenis").value;
            var baris = document.getElementById('baris').value;
            //var ttd1 = $('#ttd').combogrid('getValue');     
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            if (ctglttd == '') {
                ctglttd = "-";
            }
            /*if(ttd1==''){
            	ttd="-";
            }else{
            	ttd=ttd1.split(' ').join('abc');
            }*/
            var skpd = kdskpd;
            var url = "<?php echo site_url(); ?>perdase/cetak_perbup_lampI_2_akunSE_sub_ro";
            window.open(url + '/' + bulan + '/' + pilih + '/' + '3' + '/' + skpd + '/' + '4' + '/' + ctglttd + '/' + initx + '/' + ttdperda + '/' + label + '/' + baris +'/'+ anggaran +'/PMK_LAMP.III.A', '_blank');
            window.focus();
        }

       function cetak64_perwa(pilih) {
            var initx = pilihttd;
            var ttdperdax = $('#ttdx1').datebox('getValue');
            var ttdperda = ttdperdax.split(" ").join("n");
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            var label = document.getElementById("label").value;
            var jenis = document.getElementById("jenis").value;
            var baris = document.getElementById('baris').value;
            //var ttd1 = $('#ttd').combogrid('getValue');     
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            if (ctglttd == '') {
                ctglttd = "-";
            }
            /*if(ttd1==''){
            	ttd="-";
            }else{
            	ttd=ttd1.split(' ').join('abc');
            }*/
            var skpd = kdskpd;
            var url = "<?php echo site_url(); ?>perda/cetak_perda_lampI_2_akun64_perwa";
            window.open(url + '/' + bulan + '/' + pilih + '/' + anggaran + '/' + skpd + '/' + jenis + '/' + ctglttd + '/' + initx + '/' + ttdperda + '/' + label + '/' + baris + '/PMK_LAMP.II.A', '_blank');
            window.focus();
        }

        function cetak64_penjabaran(pilih) {
            var initx = pilihttd;
            var ttdperdax = $('#ttdx1').datebox('getValue');
            var ttdperda = ttdperdax.split(" ").join("n");
            var bulan = document.getElementById("bulan").value;
            var anggaran = document.getElementById("anggaran").value;
            var label = document.getElementById("label").value;
            var jenis = document.getElementById("jenis").value;
            var baris = document.getElementById('baris').value;
            //var ttd1 = $('#ttd').combogrid('getValue');     
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            if (ctglttd == '') {
                ctglttd = "-";
            }
            /*if(ttd1==''){
            	ttd="-";
            }else{
            	ttd=ttd1.split(' ').join('abc');
            }*/
            var skpd = kdskpd;
            var url = "<?php echo site_url(); ?>perda/cetak_perda_lampI_2_akun64_penjabaran";
            window.open(url + '/' + bulan + '/' + pilih + '/' + anggaran + '/' + skpd + '/' + jenis + '/' + ctglttd + '/' + initx + '/' + ttdperda + '/' + label + '/' + baris + '/PMK_LAMP.II.A', '_blank');
            window.focus();
        }
    </script>

</body>

</html>