<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.css" />

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

        fieldset {
            width: 100% !important;
        }
    </style>
</head>

<body>

    <div id="content">

        <LEGEND>CETAK LAPORAN REALISASI ANGGARAN SKPD</LEGEND>
        <p align="right">
        <table id="sp2d" title="Cetak" style="width:100%;height:200px;">
            <tr>
                <td width="20%">SKPD</td>
                <td><input id="sskpd" name="sskpd" style="width: 170px;" readonly="true" />&nbsp;<input id="nmskpd" name="nmskpd" style="width: 500px; " readonly="true" /></td>
            </tr>
            <tr>
                <td width="20%">Bulan / Periode</td>
                <td>
                    <input type="radio" value="bulan" name="range" id="range-1" />
                    <label for="range-1">Bulan</label>
                    <input value="periode" type="radio" name="range" id="range-2" />
                    <label for="range-1">Periode</label>
                </td>
            </tr>
            <tr id="rangeContainer" style="display: none;">
                <td width="20%">
                    Pilih <span id="rangeText" />
                </td>
                <td>
                    <div id="bulanContainer" style="display: none;">
                        <input type="text" id="bulan" style="width: 170px;" />
                    </div>
                    <div id="periodeContainer" style="display: none;">
                        <label for="tanggalAwal">Tanggal Awal</label>
                        <input type="text" id="tanggalAwal" style="margin-right: 20px;" />
                        <label for="tanggalAkhir">Tanggal Akhir</label>
                        <input type="text" id="tanggalAkhir" />
                    </div>
                </td>
            </tr>
            <tr>
                <td width="20%" height="40">JENIS ANGGARAN</td>
                <td width="80%"> <input id="jenisAnggaran" name="jenisAnggaran" class="form-control" style="width: 170px;" />
                </td>
            </tr>
            <tr>
                <td>Tanggal TTD</td>
                <td><input type="text" id="tgl_ttd" style="width: 170px;" /></td>
            </tr>
            <tr>
                <td>PA</td>
                <td><input id="namaTTD" name="namaTTD" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmttd2" name="nmttd" style="width: 170px;border:0" /> </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><b>Cetak LRA</b></td>
                <td>
                    <button class="button-kuning" plain="true" onclick="javascript:cetaklra(1, null);"><i class="fa fa-print"></i> Cetak Layar</a></button>
                    <button class="button-hitam" plain="true" onclick="javascript:cetaklra(0,null);"><i class="fa fa-pdf"></i> Cetak PDF</a></button>
                    <button class="button-biru" plain="true" onclick="javascript:cetaklra(2, null);"><i class="fa fa-excel"></i> Cetak Excel</a></button>
                </td>

            </tr>
            <tr>
                <td>Cetak LRA Permen 77</td>
                <td>
                    <button class="button-kuning" plain="true" onclick="javascript:cetaklra(1,'77');"><i class="fa fa-print"></i> Cetak Layar</a></button>
                    <button class="button-hitam" plain="true" onclick="javascript:cetaklra(0,'77');"><i class="fa fa-pdf"></i> Cetak PDF</a></button>
                    <button class="button-biru" plain="true" onclick="javascript:cetaklra(2,'77');"><i class="fa fa-excel"></i> Cetak Excel</a></button>
                </td>
            </tr>
            <tr>
                <td>Cetak LRA Permen 90</td>
                <td>
                    <button class="button-kuning" plain="true" onclick="javascript:cetaklra(1, '90');"><i class="fa fa-print"></i> Cetak Layar</a></button>
                    <button class="button-hitam" plain="true" onclick="javascript:cetaklra(0,'90');"><i class="fa fa-pdf"></i> Cetak PDF</a></button>
                    <button class="button-biru" plain="true" onclick="javascript:cetaklra(2,'90');"><i class="fa fa-excel"></i> Cetak Excel</a></button>
                </td>
            </tr>
            <tr>
                <td>Cetak LRA Permen Sub RO 90</td>
                <td>
                    <button class="button-kuning" plain="true" onclick="javascript:cetaklra(1, 'sub_ro');"><i class="fa fa-print"></i> Cetak Layar</a></button>
                    <button class="button-hitam" plain="true" onclick="javascript:cetaklra(0, 'sub_ro');"><i class="fa fa-pdf"></i> Cetak PDF</a></button>
                    <button class="button-biru" plain="true" onclick="javascript:cetaklra(2, 'sub_ro');"><i class="fa fa-excel"></i> Cetak Excel</a></button>
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
        var ctk = 1;

        const rangeContainer = $('#rangeContainer')
        const bulanContainer = $('#bulanContainer')
        const periodeContainer = $('#periodeContainer')
        const pilihRange = $('input[name=range]')
        const rangeText = $('#rangeText');

        $(document).ready(function() {
            get_skpd();
            $("#accordion").accordion();

            $("#dialog-modal").dialog({
                height: 100,
                width: 922
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
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/akuntansi_perskpd/load_ttd/PA',
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

            $('#namaTTD').combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/akuntansi_perskpd/load_ttd/PA',
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

            $('#jenisAnggaran').combogrid({
                idField: 'kode',
                textField: 'nama',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/akuntansi_perskpd/anggaran',
                columns: [
                    [{
                        field: 'nama',
                        title: 'Nama',
                        width: 210
                    }]
                ],
                onSelect: function(rowIndex, rowData) {
                    rak = rowData.kode;

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

            $('#tanggalAwal').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });
            $('#tanggalAkhir').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });

            pilihRange.change(() => {
                const value = $("input[name=range]:checked").val();
                if (value === 'bulan') {
                    bulanContainer.show()
                    periodeContainer.hide()
                    rangeText.text('Bulan')
                } else {
                    periodeContainer.show()
                    bulanContainer.hide()
                    rangeText.text('Periode')
                }
                rangeContainer.show()
            })
        });

        function submit() {
            if (ctk == '') {
                alert('Pilih Jenis Cetakan');
                exit();
            }
            document.getElementById("frm_ctk").submit();
        }





        function cetak($pilih) {
            var pilih = $pilih;
            //var jns_ang = document.getElementById('jenis_ang').value;
            //alert(jns_ang);
            cbulan = $('#bulan').combogrid('getValue');
            // var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            // var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var tgl_ttd = $("#tgl_ttd").combogrid('getValue');
            var jns_ang = $("#rak").combogrid('getValue');
            var skpd = $("#sskpd").combogrid('getValue');

            urll = '<?php echo base_url(); ?>index.php/akuntansi_perskpd/cetak_lra/' + cbulan;
            if (bulan == '') {
                swal("Error", "Pilih Bulan dulu", "warning");
                exit();
            }
            if (tgl_ttd == '') {
                swal("Error", "Pilih Tanggal TTD", "warning");
                exit();
            }
            if (ttd2 == '') {
                swal("Error", "Pilih Penandatangan dulu", "warning");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + 'ttd' + '/' + ttd2 + '/' + tgl_ttd + '/' + 3 + '/' + jns_ang + '/' + skpd, '_blank');
            window.focus();

        }


        function cetak77($pilih) {
            var pilih = $pilih;
            //var jns_ang = document.getElementById('jenis_ang').value;
            //alert(jns_ang);
            cbulan = $('#bulan').combogrid('getValue');
            // var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            // var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var tgl_ttd = $("#tgl_ttd").combogrid('getValue');
            var jns_ang = $("#rak").combogrid('getValue');
            var skpd = $("#sskpd").combogrid('getValue');



            urll = '<?php echo base_url(); ?>index.php/akuntansi_perskpd/cetak_lra_77/' + cbulan;
            if (bulan == '') {
                swal("Error", "Pilih Bulan dulu", "warning");
                exit();
            }
            if (tgl_ttd == '') {
                swal("Error", "Pilih Tanggal TTD", "warning");
                exit();
            }
            if (ttd2 == '') {
                swal("Error", "Pilih Penandatangan dulu", "warning");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + 'ttd' + '/' + ttd2 + '/' + tgl_ttd + '/' + 3 + '/' + jns_ang + '/' + skpd, '_blank');
            window.focus();

        }

        function cetaklra($pilih, tipeLra = '') {
            const pilih = $pilih;
            const skpd = $("#sskpd").combogrid('getValue') ?? '-';
            const bulan = $('#bulan').combogrid('getValue');
            const jenisAnggaran = $("#jenisAnggaran").combogrid('getValue');
            const namaTTD = $('#namaTTD').combogrid('getValue')
            const tanggalTTD = $("#tgl_ttd").combogrid('getValue');
            const rangeData = $('input[name=range]:checked').val()
            const tanggalAwal = $('#tanggalAwal').combogrid('getValue')
            const tanggalAkhir = $('#tanggalAkhir').combogrid('getValue')

            if (rangeData === undefined) {
                return swal('Error', 'Pilih bulan/periode terlebih dahaulu', 'warning')
            } else if (rangeData === 'bulan' && bulan === '') {
                return swal('Error', 'Pilih bulan terlebih dahulu', 'warning');
            } else if (rangeData === 'periode' && (tanggalAwal === '' || tanggalAkhir === '')) {
                return swal('Error', 'Pilih range tanggal terlebih dahulu', 'warning');
            } else if (rangeData === 'periode' && (tanggalAwal > tanggalAkhir)) {
                return swal('Error', 'Tanggal awal tidak boleh lewat dari tanggal', 'warning');
            } else if (tanggalTTD === '') {
                return swal("Error", 'Pilih tanggal tanda tangan');
            }

            let url = '';

            if (tipeLra === '77') {
                url += `<?php echo base_url(); ?>index.php/akuntansi_perskpd/cetak_lra_77?rangeData=${rangeData}&tipeCetak=${pilih}&tanggalTTD=${tanggalTTD}&namaTTD=${namaTTD}&jenisAnggaran=${jenisAnggaran}&skpd=${skpd}`;
            } else if (tipeLra == '90') {
                url += `<?php echo base_url(); ?>index.php/akuntansi_perskpd/cetak_lra?rangeData=${rangeData}&tipeCetak=${pilih}&tanggalTTD=${tanggalTTD}&namaTTD=${namaTTD}&jenisAnggaran=${jenisAnggaran}&skpd=${skpd}`;
            } else if (tipeLra == 'sub_ro') {
                url += `<?php echo base_url(); ?>index.php/akuntansi_perskpd/cetak_lra_sub_ro?rangeData=${rangeData}&tipeCetak=${pilih}&tanggalTTD=${tanggalTTD}&namaTTD=${namaTTD}&jenisAnggaran=${jenisAnggaran}&skpd=${skpd}`;
            } else {
                url += `<?php echo base_url(); ?>index.php/akuntansi_perskpd/cetak_lra_baru?rangeData=${rangeData}&tipeCetak=${pilih}&tanggalTTD=${tanggalTTD}&namaTTD=${namaTTD}&jenisAnggaran=${jenisAnggaran}&skpd=${skpd}`;
            }

            if (rangeData === 'bulan') {
                url += `&bulan=${bulan}`
            } else {
                url += `&tanggalAwal=${tanggalAwal}&tanggalAkhir=${tanggalAkhir}`
            }
            window.open(url, '_blank');
            window.focus();
            // window.open(urll + '/' + pilih + '/' + 'ttd' + '/' + ttd2 + '/' + tgl_ttd + '/' + 3 + '/' + jns_ang + '/' + skpd, '_blank');
            // window.focus();

        }

        function cetak_sub_ro($pilih) {
            var pilih = $pilih;
            //var jns_ang = document.getElementById('jenis_ang').value;
            cbulan = $('#bulan').combogrid('getValue');
            // var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            // var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var tgl_ttd = $("#tgl_ttd").combogrid('getValue');
            var jns_ang = $("#rak").combogrid('getValue');
            var skpd = $("#sskpd").combogrid('getValue');

            if (ctk == 1) {
                urll = '<?php echo base_url(); ?>index.php/akuntansi_perskpd/cetak_lra_sub_ro/' + cbulan;
                if (bulan == '') {
                    swal("Error", "Pilih Bulan dulu", "warning");
                    exit();
                }
                if (tgl_ttd == '') {
                    swal("Error", "Pilih Tanggal TTD", "warning");
                    exit();
                }
                if (ttd2 == '') {
                    swal("Error", "Pilih Penandatangan dulu", "warning");
                    exit();
                }
            } else {
                // urll = '<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_unit/' + cbulan + '/' + kdskpd;
                if (kdskpd == '') {
                    alert("Pilih Unit dulu");
                    exit();
                }
                if (bulan == '') {
                    alert("Pilih Bulan dulu");
                    exit();
                }
            }
            window.open(urll + '/' + pilih + '/' + 'ttd' + '/' + ttd2 + '/' + tgl_ttd + '/' + 3 + '/' + jns_ang + '/' + skpd, '_blank');
            window.focus();

        }

        function cetak2($pilih) {
            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var jns_ang = document.getElementById('jenis_ang').value;
            //var tgl_ttd   = $("#tgl_ttd").combogrid('getValue');
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_jenis/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak6($pilih) {
            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var jns_ang = document.getElementById('jenis_ang').value;
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_jenis_ang/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak134(jenis, $pilih) {

            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var jns_ang = document.getElementById('jenis_ang').value;
            var pilih = $pilih;
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            cbulan = $('#bulan').combogrid('getValue');

            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_jenis_ang13/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + jns_ang + '/' + jenis, '_blank');
            window.focus();

        }

        function cetak3($pilih) {
            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var jns_ang = document.getElementById('jenis_ang').value;
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_rincian/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak7($pilih) {
            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var jns_ang = document.getElementById('jenis_ang').value;
            var pilih = $pilih;
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            cbulan = $('#bulan').combogrid('getValue');

            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_rincian_ang/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak4($pilih) {
            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var jns_ang = document.getElementById('jenis_ang').value;
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_penjabaran/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak4_sumber($pilih) {
            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var jns_ang = document.getElementById('jenis_ang').value;
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_penjabaran_sumber/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak8($pilih) {

            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var jns_ang = document.getElementById('jenis_ang').value;
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_penjabaran_ang/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak8sumber($pilih) {

            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var jns_ang = document.getElementById('jenis_ang').value;
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_penjabaran_ang_sumber/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak8_12($pilih) {

            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var jns_ang = document.getElementById('jenis_ang').value;
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_penjabaran_ang_12/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak8_sumber($pilih) {

            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var jns_ang = document.getElementById('jenis_ang').value;
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_penjabaran_sumber12/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + jns_ang, '_blank');
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

        function get_skpd() {

            $('#sskpd').combogrid({
                panelWidth: 700,
                idField: 'kd_skpd',
                textField: 'kd_skpd',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/rka/skpd',
                columns: [
                    [{
                            field: 'kd_skpd',
                            title: 'Kode SKPD',
                            width: 100
                        },
                        {
                            field: 'nm_skpd',
                            title: 'Nama SKPD',
                            width: 700
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    kdskpd = rowData.kd_skpd;
                    // $("#skpd").attr("value", data.kd_skpd);       
                    $("#nmskpd").attr("value", rowData.nm_skpd);
                }
            });

        }

        function cetak9($pilih) {
            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_penjabaran_ang_sdana/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2, '_blank');
            window.focus();

        }
    </script>
</body>
</fieldset>

</html>