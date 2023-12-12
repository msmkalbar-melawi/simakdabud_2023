<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
<link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.css" />
<div id="content">

    <LEGEND><?= $page_title ?></LEGEND>
    <p align="right">
    <table id="sp2d" title="Cetak" style="width:100%;height:200px;">
        <tr>
            <td width="20%">SKPD</td>
            <td><input hidden value="<?= $skpd->kd_skpd ?>" type="text" id="skpd" style="width: 170px;" readonly />&nbsp;&nbsp;
                <span id="skpdName"><?= $skpd->nm_skpd ?></span>
            </td>
        </tr>
        <tr>
            <td width="20%">Tipe</td>
            <td>
                <input value="bulan" name="type" type="radio" id="type-1" />
                <label for="type-1">Bulan</label>
                <input value="periode" name="type" type="radio" id="type-2" />
                <label for="type-2">Periode</label>
            </td>
        </tr>
        <tr id="rowType">
            <td width="20%">Pilih <span id="textType"></span> </td>
            <td>
                <div id="bulanForm">
                    <select name="bulan" id="bulan">
                        <option value="">-- Pilih Salah Satu ---</option>
                        <?php
                        foreach ($months as $month) {
                        ?>
                            <option value="<?= $month['id'] ?>"><?= $month['nama'] ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div id="periodeForm">
                    <label for="periode1">Awal</label>
                    <input type="text" id="periode1" style="width: 170px;" />&nbsp;&nbsp;
                    <label for="periode1">Akhir</label>
                    <input type="text" id="periode2" style="width: 170px;" />
                </div>
            </td>
        </tr>
        <tr>
            <td width="20%">Jenis Anggaran</td>
            <td>
                <select name="jenisAnggaran" id="jenisAnggaran">
                    <option value=""> -- Pilih Salah Satu -- </option>
                    <?php
                    foreach ($statusAnggaran as $jenis) {
                    ?>
                        <option value="<?= $jenis->kode ?>"><?= $jenis->nama ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><b>LRA</b></td>
            <td>
                <button class="button-kuning" plain="true" onclick="javascript:submit1(0);"><i class="fa fa-print"></i> Cetak Layar</a></button>
                <button class="button-hitam" plain="true" onclick="javascript:submit1(1);"><i class="fa fa-pdf"></i> Cetak PDF</a></button>
                <button class="button-biru" plain="true" onclick="javascript:submit1(2);"><i class="fa fa-excel"></i> Cetak Excel</a></button>
            </td>
        </tr>
        <tr>
            <td><b>Rincian BLUD</b></td>
            <td>
                <button class="button-kuning" plain="true" onclick="javascript:submit(0);"><i class="fa fa-print"></i> Cetak Layar</a></button>
                <button class="button-hitam" plain="true" onclick="javascript:submit(1);"><i class="fa fa-pdf"></i> Cetak PDF</a></button>
                <button class="button-biru" plain="true" onclick="javascript:submit(2);"><i class="fa fa-excel"></i> Cetak Excel</a></button>
            </td>
        </tr>
    </table>
    </p>
</div>


<script>
    $(document).ready(function() {
        const rowType = $('#rowType');
        const skpdName = $('#skpdName');
        const bulanForm = $('#bulanForm');
        const periodeForm = $('#periodeForm');

        const text = $('#textType')

        rowType.hide();
        bulanForm.hide();
        periodeForm.hide();

        $("input[name=type]").change(function() {
            rowType.show();
            const value = $(this).val();

            if (value === 'bulan') {
                text.text('Bulan')
                bulanForm.show()
                periodeForm.hide();
            } else {
                text.text('Periode')
                periodeForm.show();
                bulanForm.hide()
            }
        })

        $('#periode1').datebox({
            required: true,
            formatter: function(date) {
                var y = date.getFullYear();
                var m = date.getMonth() + 1;
                var d = date.getDate();
                return y + '-' + m + '-' + d;
            }
        });
        $('#periode2').datebox({
            required: true,
            formatter: function(date) {
                var y = date.getFullYear();
                var m = date.getMonth() + 1;
                var d = date.getDate();
                return y + '-' + m + '-' + d;
            }
        });
    });

    /** 
     * Fungsi untuk mementukan jenis cetakan yang akan di pilih
     * @param number type
     * @return tring type
     * @author Emon Krismon
     * @link https://github.com/krismonsemanas
     */
    function printType(type) {
        let print = '';
        switch (type) {
            case 0:
                print = 'preview'
                break;
            case 1:
                print = 'pdf'
                break;
            case 2:
                print = 'excel'
                break;

            default:
                print = 'preview'
                break;
        }

        return print;
    }

    /** 
     * Jalankan fungsi type
     * @param number printType
     * @author Emon Krismon
     * @link https://github.com/krismonsemanas
     */
    function submit(type) {
        const print = printType(type);

        const jenis = $("input[type=radio]:checked").val()
        const bulan = $('#bulan').val()
        var periode1 = $('#periode1').datebox('getValue');
        var periode2 = $('#periode2').datebox('getValue');
        const jenisAnggaran = $('#jenisAnggaran').val()

        if (jenis === undefined) {
            return alert('Pilih tipe cetak terlebih dahulu')
        } else if (jenis === 'bulan' && bulan === '') {
            return alert('Pilih bulan terlebih dahulu')
        } else if (jenis === 'periode' && (periode1 === '' && periode2 === '')) {
            return alert('Periode tidak boleh kosong')
        } else if (jenis === 'periode' && periode1 > periode2) {
            return alert('Pastikan tanggal awal lebih kecil dari tanggal akhir');
        } else if (jenisAnggaran === '') {
            return alert('Jenis anggaran tidak boleh kosong')
        }

        const baseUrl = '<?= site_url('rincian/blud/cetak') ?>'

        const url = `${baseUrl}?jenis=${jenis}&tipeCetak=${print}&${jenis === 'bulan' ? `bulan=${bulan}` : `tanggalAwal=${periode1}&tanggalAkhir=${periode2}`}&jenisAnggaran=${jenisAnggaran}`

        window.open(url, '_blank');
        window.focus();

    }

    // andika
    function submit1(type) {
        const print = printType(type);

        const jenis = $("input[type=radio]:checked").val()
        const bulan = $('#bulan').val()
        var periode1 = $('#periode1').datebox('getValue');
        var periode2 = $('#periode2').datebox('getValue');
        const jenisAnggaran = $('#jenisAnggaran').val()

        if (jenis === undefined) {
            return alert('Pilih tipe cetak terlebih dahulu')
        } else if (jenis === 'bulan' && bulan === '') {
            return alert('Pilih bulan terlebih dahulu')
        } else if (jenis === 'periode' && (periode1 === '' && periode2 === '')) {
            return alert('Periode tidak boleh kosong')
        } else if (jenis === 'periode' && periode1 > periode2) {
            return alert('Pastikan tanggal awal lebih kecil dari tanggal akhir');
        } else if (jenisAnggaran === '') {
            return alert('Jenis anggaran tidak boleh kosong')
        }

        const baseUrl = '<?= site_url('rincian/blud/cetak2') ?>'

        const url = `${baseUrl}?jenis=${jenis}&tipeCetak=${print}&${jenis === 'bulan' ? `bulan=${bulan}` : `tanggalAwal=${periode1}&tanggalAkhir=${periode2}`}&jenisAnggaran=${jenisAnggaran}`

        window.open(url, '_blank');
        window.focus();

    }

</script>

<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
<script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>