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
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>     
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>    
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
</head>
<body>
  <div id="content">
    <div id="accordion">
      <h3><a href="#" id="section1">SPD BELANJA</a></h3>
      <div>
        <p align="right">
          <button id="create_spd" type="submit"  value="+ Tambah"><i class="fa fa-plus"></i> Tambah</button>
          <a class="easyui-linkbutton" iconCls="icon-search" plain="true" id="search">Cari</a>
          <input type="text" value="" id="txtcari"/>
        </p>
        <p><input id="skpds" name="skpds" style="width: 830px;" /></p>
        <p><table id="dg" title="List SPD" style="width:870px;height:450px;"></table></p>
      </div>
      <h3>
        <a href="#" id="section2">
          <b id="p1" style="font-size:17px;color: red;"></b><br />
          <b id="p2" style="font-size:17px;color: red;"></b>
          <input type="hidden" readonly="true" style="border:hidden" id="txt_p2" name="txt_p2" value="0"/>
        </a>
      </h3>
      <div style="height: 350px;">
        <p>
          <table align="center" border='1' style="width:870px;">
            <tr style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">
              <td colspan="5" style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">&nbsp;</td>
            </tr>
            <tr style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">S K P D</td>
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;"><input id="skpd" name="skpd" style="width: 140px;"/></td>
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;"></td>
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">Nama SKPD :</td> 
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">&nbsp;&nbsp;<input type="text" id="nmskpd" style="border:0;width: 300px;"  readonly="true"/></td>                                
            </tr>
            <tr style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">No. S P D</td>
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">
                <input type="text" id="nomor" style="width: 250px;" />
                <input  type="hidden" id="nomor_hide" style="width: 20px;" onclick="javascript:select();" readonly="true"/>
              </td>
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;"></td>
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">Tanggal SPD</td>
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">&nbsp;&nbsp;<input type="text" id="tanggal" style="width: 140px;" /></td>     
            </tr>
            <tr style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;"></td>
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;font-weight: bold;color: red;">13.07/01.0/XXXXXX/KODE SKPD/M/1/2021</td>
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;"></td>
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;"></td>
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;"></td>
            </tr>
            <tr style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">Atas Beban</td>
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">
                <select name="jenis" id="jenis" disabled><option value="5">BELANJA</option></select>
              </td>                
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;"></td>
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">Kebutuhan Bulan</td>
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">
                <?php echo $this->rka_model->combo_bulan('bulan1', ''); ?> s/d <?php echo $this->rka_model->combo_bulan('bulan2',''); ?>
              </td>
            </tr>
            <tr style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">Ketentuan Lain</td>
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;"><textarea type="text" id="ketentuan" cols="30" rows="1" ></textarea></td>
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;"></td>
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;" ><!--Jenis Pengajuan--></td>
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">&nbsp;<input hidden=true type="text" id="pengajuan"/></td>
            </tr>
            <tr style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">
              <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">Kepala SKPD</td>
              <td colspan ="4" style="border-bottom-style:hidden;padding:5px;border-spacing:5px 5px 5px 5px;">
                <input type="text"  name="nip" id="bendahara" style="width:200px" /> &nbsp;
                <input type ="input" readonly="true" style="border:hidden" id="nama_bend" name="nama_bend" style="width:300px" />
                <input type="hidden" readonly="true" style="border:hidden" id="id_status" name="id_status" value="1"/>
              </td>
            </tr>
            <tr style="padding:3px;border-spacing:5px 5px 5px 5px;">
              <td style="padding:3px;border-spacing:5px 5px 5px 5px;border-bottom-style:hidden;" colspan="5" align="right">
                <a type="submit" class="easyui-linkbutton"  plain="true" id="toggle_status_spd" align="left"><font color='#4CAF50'><i class="fa fa-check-square"></i></font> <b id="id_aktif"></b></a>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a type="primary" id="create_spd_alt" class="easyui-linkbutton" type="submit"  plain="true"><font color='#4CAF50'><i class="fa fa-plus"></i></font> Tambah</a>
                <a type="submit"  id="save" class="easyui-linkbutton"  plain="true" disabled="disabled"><font color='#38a2ff'><i class="fa fa-save"></i></font> Simpan</a>
                <a type="primary"  id="del" class="easyui-linkbutton" plain="true"><font color='red'><i class="fa fa-window-close"></i></font> Hapus</a>
                <a type="primary" id="cetak"  class="easyui-linkbutton" plain="true"><font color='#38a2ff'><i class="fa fa-print"></i></font> Cetak</a>
                <a type="primary" class="easyui-linkbutton" plain="true" id="kembali"><font color='orange'><i class="fa fa-arrow-left"></i></font> Kembali</a>
              </td>
            </tr>
            <tr style=";padding:3px;border-spacing:5px 5px 5px 5px;">
              <td colspan="5" style="padding:3px;border-spacing:5px 5px 5px 5px;border-bottom-color:black;">&nbsp;</td>
            </tr>
        </table>
        <table id="dg1" title="Kegiatan S P D" style="width:870px;height:600px;"></table>  
        <table align="center" style="width:100%;">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td></td>
            <td align="right">Total : <input type="text" id="total" style="font-size: large;border:0;width: 200px;text-align: right;" readonly="true"/></td>
          </tr>
        </table>
      </p>
    </div>
  </div>
</div>

<div id="dialog-cetak" title="Cetak SPD" > 
  <fieldset >
    <table >
      <tr>
        <td>Nomor SPD</td>
        <td>:</td>
        <td><input type="text" id="nomor1" style="border: 0;" name="nomor1" readonly="true" /></td>
      </tr>
      <tr><td colspan="3"><input type="radio" name="cetak" value="2"/>Lampiran SPD (Layar)</td></tr>
      <tr><td colspan="3"><input type="radio" name="cetak" value="4"/>Otorisasi SPD (Layar)</td></tr>
      <tr>
        <td valign="top" style="horizontal-align: center;">Bendahara PPKD</td>
        &nbsp;&nbsp;
        <td colspan="3" style="vertical-align: center;"><input type="text" name="nip_ppkd" id="bendahara_ppkd" style="width:200px;" />
          <br>&nbsp;&nbsp;
          <input type ="input" readonly="true" style="border:hidden" id="nama_ppkd" name="nama_ppkd" style="width:300px;text-indent: 50px;" />
          <input type="hidden" readonly="true" style="border:hidden" id="jabatan_ppkd" name="jabatan_ppkd"/>
          <input type="hidden" readonly="true" style="border:hidden" id="pangkat_ppkd" name="pangkat_ppkd"/>
        </td>
      </tr>
      <tr>
        <td>Cetak Tanpa Nomor SPD</td>
        <td>:</td>
        <td><input type="checkbox" id="chk_spd" style="border: 0;" name="chk_spd" value="1"/></td>
      </tr>
      <tr>
        <td>Tambahan</td>
        <td>:</td>
        <td><input type="checkbox" id="chk_tambah" style="border: 0;" name="chk_tambah" value="1"/></td>
      </tr>
      <tr>
        <td>Ukuran Cetakkan</td>  
        <td>:</td> 
        <td>&nbsp;<input type="number" id="cell" name="cell" style="width: 50px; border:1" value="1" /> &nbsp;&nbsp;</td>
      </tr>  
      <tr>
        <td colspan="3" align="center">
          <select id="kop" name="kop">
            <option value="5">DENGAN KOP</option>
            <option value="30">TANPA KOP</option>
          </select>
        </td>
        <td colspan="3" align="center">
          <select id="water" name="water">
            <option value="0">TANPA WATERMARK</option>
            <option value="1">DENGAN WATERMARK</option>
          </select>
        </td>
      </tr>  
    </table>
  </fieldset>
  <fieldset>
    <table align="center">
      <tr>
        <td>
          <button type="primary"  value="Print" class="cetak-spd" data-jenis="0"><i class="fa fa-television"></i> Layar</button>
          <button type="primary"  value="Print" class="cetak-spd" data-jenis="1"><i class="fa fa-print"></i> PDF</button>
          <button type="edit"  value="keluar" id="close-cetak"><i class="fa fa-arrow-left"></i> Keluar</button>
        </td>
      </tr>
    </table>
  </fieldset>
</div>
</body>
<script>
  $(document).ready(function() {
    var tahun_anggaran;
    var isNew;
    $("#accordion").accordion()

    $("#dialog-cetak").dialog({
      height: 450,
      width: 600,
      modal: true,
      autoOpen: false
    })

    $.ajax({
      url: '<?php echo base_url(); ?>index.php/spd/config_tahun',
      type: "POST",
      dataType:"json",
      success:function(data) {
        tahun_anggaran = data
      }
    });

    $('#skpd').combogrid({
      panelWidth: 700,
      idField: 'kd_skpd',
      textField: 'kd_skpd',
      mode: 'remote',
      url: '<?php echo base_url(); ?>index.php/spd/skpduser',
      columns: [[
        { field:'kd_skpd', title:'Kode SKPD', width:100 },
        { field:'nm_skpd', title:'Nama SKPD', width:700},
      ]],
      onSelect: function (rowIndex, rowData) {
        $("#nmskpd").val(rowData.nm_skpd)
        if (isNew) {
          $("#nomor").val(`13.07/01.0//${rowData.kd_skpd}/M/1/${tahun_anggaran}`)
          $('#bendahara').combogrid({
            url: `<?php echo base_url(); ?>index.php/spd/load_kadis/${rowData.kd_skpd}`,
            queryParams: { kode: rowData.kd_skpd },
          })
          load_angkas_belanja()
        }
        cek_anggaran_kas()
      },
    })

    function cek_anggaran_kas() {
      var kd_skpd = $('#skpd').combogrid('getValue')
      $.ajax({
        url: '<?php echo base_url(); ?>index.php/spd/cek_anggaran_kas_spd/',
        type: "POST",
        dataType:"json",
        data: { skpd: kd_skpd },
        success: function(data) {
          jmlh = data
          if (jmlh > 0){
            $('#p2').html(`ADA ${jmlh} KEGIATAN ANGGARAN KAS MASIH SELISIH DI SKPD INI`)
            $("#txt_p2").val('1')
          } else {
            $('#p2').html('')
            $("#txt_p2").val('0')
          }
        }
      })
    }

    $('#bulan1, #bulan2').change(function() {
      load_angkas_belanja()
    })

    $('#kembali').click(function() {
      section1()
    })

    $('#dg').edatagrid({
      url: '<?php echo base_url(); ?>/index.php/spd/load_spd_bl',
      rowStyler:function(index, row){
        if (row.status == 1) return 'background-color:#4bbe68;color:white'
      },
      idField: 'id',
      rownumbers: "true", 
      fitColumns: "true",
      autoRowHeight: "false",
      pagination: "true",
      nowrap: "true",
      columns:[[
        { field:'no_spd', title:'Nomor SPD', width:70 },
        { field:'tgl_spd', title:'Tanggal', width:30 },
        { field:'nm_skpd', title:'Nama SKPD', width:100, align:"left" },
        { field:'nm_beban', title:'Jenis', width:50, align:"center" },
      ]],
      onDblClickRow:function(rowIndex, rowData) {
        isNew = false
        $("#nomor").val(rowData.no_spd)
        $("#tanggal").datebox("setValue", rowData.tgl_spd)
        $("#skpd").combogrid("setValue", rowData.kd_skpd)
        $('#bendahara').combogrid({
          url: `<?php echo base_url(); ?>index.php/spd/load_kadis/${rowData.kd_skpd}`,
          queryParams: { kode: rowData.kd_skpd },
          onLoadSuccess: function () {
            $("#bendahara").combogrid("setValue", rowData.nip)
          }
        })
        $("#nmskpd").val(rowData.nm_skpd)
        $("#ketentuan").val(rowData.ketentuan)
        $("#bulan1").val(rowData.bulan_awal)
        $("#bulan2").val(rowData.bulan_akhir)
        $("#jenis").val(rowData.jns_beban)
        
        $('#skpd').combogrid('disable')
        $('#bulan1, #bulan2').prop("disabled", true)
        $("#nomor").prop("disabled", true)
        $('#toggle_status_spd').linkbutton('enable')

        if (rowData.status == '1') {
          $('#p1').html("SPD AKTIF")
          $("#id_aktif").html("NON Aktifkan SPD")
          $('#save, #del').linkbutton('disable')
        }
        else {
          $('#p1').html("SPD NON AKTIF")
          $("#id_aktif").html("Aktifkan SPD")
          $('#save, #del').linkbutton('enable')
        }

        cek_anggaran_kas()

        $('#dg1').edatagrid({
          url: '<?php echo base_url(); ?>/index.php/spd/load_detail_spd',
          method: 'get',
          queryParams: { no_spd: rowData.no_spd },
          onLoadSuccess: function(response) {
            $("#total").val(number_format(response.nilai_total, 2, '.', ','))
          },
        })
        $('#dg1').edatagrid('reload')

        section2()
      }
    })

    $('#tanggal').datebox({  
      required:true,
      formatter: function(date) {
        var y = date.getFullYear()
        var m = date.getMonth() + 1
        var d = date.getDate()
        return `${y}-${m}-${d}`
      }
    })

    $('#skpds').combogrid({
      panelWidth: 830,
      url: '<?php echo base_url(); ?>/index.php/spd/skpduser',
      idField: 'kd_skpd',
      textField: 'nm_skpd',
      mode: 'remote',
      fitColumns: true,
      columns:[[
        { field: 'kd_skpd',title: 'SKPD',width:70 },
        { field: 'nm_skpd',title: 'NAMA',align:'left',width:600 }
      ]],
      onSelect: function(rowIndex, rowData) {
        $('#dg').edatagrid({
          url: '<?php echo base_url(); ?>/index.php/spd/load_spd_bl',
          queryParams: { cari: rowData.kd_skpd }
        })
      }
    })

    $('#bendahara_ppkd').combogrid({
      panelWidth: 700,
      idField: 'nip_ppkd',
      textField: 'nip_ppkd',
      mode: 'remote',
      url: '<?php echo base_url(); ?>index.php/spd/load_bendahara_ppkd',
      columns:[[
        { field: 'nip_ppkd', title: 'NIP', width: 150 },
        { field: 'nama_ppkd', title: 'Nama', width: 500 }
      ]],
      onSelect: function(rowIndex, rowData) {
        $("#nama_ppkd").val(rowData.nama_ppkd)
        $("#jabatan_ppkd").val(rowData.jabatan_ppkd)
        $("#pangkat_ppkd").val(rowData.pangkat_ppkd)
      }
    })

    $('#bendahara').combogrid({
      panelWidth: 700,
      idField: 'nip',
      textField: 'nip',
      mode: 'remote',
      columns: [[
        { field: 'nip', title: 'NIP', width: 150 },
        { field: 'nama', title: 'Nama', width: 500 }
      ]],
      onSelect: function(rowIndex, rowData) {
        $("#nama_bend").val(rowData.nama)
      }
    })

    $('#dg1').edatagrid({
      idField: 'id',
      toolbar: '#toolbar',
      rownumbers: "true",
      fitColumns: "true",
      singleSelect: "true",
      autoRowHeight: "false",
      pagination: "true",
      nowrap: "true",
      pageList: [10,20,30,40,50,100,300],
      columns: [[
        { field: 'id', title: 'id', width:10, hidden: "true" },
        { field: 'kd_sub_kegiatan', title: 'Kode Sub Kegiatan', width:160 },
        { field: 'nm_sub_kegiatan', title: 'Nama Sub Kegiatan', width:280 },
        { field: 'kd_rek6', title: 'Kode Rekening', width:160 },
        { field: 'nm_rek6', title: 'Nama Rekening', width:280 },
        { field: 'nilai', title: 'Nilai Rupiah', width:130, align:"right" },
        { field: 'nilai_lalu', title: 'Telah Di SPD kan', width:130, align:"right" },
        { field: 'nilai_anggaran', title: 'anggaran', width:130, align:"right" },
      ]],
    });

    function section1() {
      $(document).ready(function(){
        $('#section1').click()
        $('#dg').edatagrid('reload')
      })
    }

    function section2() {
      $(document).ready(function() {
        $('#section2').click()
        document.getElementById("nomor").focus()
      })
    }

    $('#search').click(function() {
      $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/spd/load_spd_bl',
        queryParams: { cari: $('#txtcari').val() },
      })
    })

    $('#create_spd, #create_spd_alt').click(function() {
      isNew = true
      $('#skpd').combogrid('setValue', '')
      $("#nmskpd").val('')
      $("#nomor").val('13.07/01.0///M/1/'+tahun_anggaran)
      $("#nomor_hide").val('')
      $("#tanggal").datebox("setValue", '<?php echo date("Y-m-d"); ?>')
      $("#bendahara").combogrid("setValue",'')
      $("#bulan1, #bulan2").val(0)
      $("#jenis").val('5')
      $("#ketentuan").val('')
      $("#bendahara").combogrid("setValue",'')
      $('#total').val('0')
      
      $('#save').linkbutton('enable')
      $('#del').linkbutton('disable')
      $("#bulan1, #bulan2").removeAttr("disabled")
      $("#nomor").removeAttr("disabled")
      $('#skpd').combogrid('enable')

      $("#toggle_status_spd").linkbutton("disable")
      $("#id_aktif").html("Aktifkan SPD")
      $("#p1").html("SPD NON AKTIF")

      $('#dg1').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/spd/load_dspd',
        queryParams: { no: '' }
      })
      document.getElementById("nomor").focus()
    })

    $('#create_spd').click(function() {
      section2()
    })

    $('#save').click(function() {
      var no_spd = $('#nomor').val()
      var cek_angkas = document.getElementById('txt_p2').value;
      var tgl = $('#tanggal').datebox('getValue');
      var kd_skpd = $('#skpd').combogrid('getValue');
      var bend =  $('#bendahara').combogrid('getValue');
      var bulan_awal = $('#bulan1 :selected').val();
      var bulan_akhir = $('#bulan2 :selected').val();
      var ketentuan = $('#ketentuan').val()
      var jenis = $('#jenis').val()
      var total = angka(document.getElementById('total').value);
      if (no_spd.length != 49) {
        alert('Silahkan Inputkan SPD sesuai digit yang sudah ditentukan!');
        return;
      }
      if (no_spd == '') {
        alert('Nomor SPD Tidak Boleh Kosong');
        return
      } 
      if (tgl == '') {
        alert('Tanggal SPD Tidak Boleh Kosong');
        return
      }
      if (kd_skpd == '') {
        alert('Kode SKPD Tidak Boleh Kosong');
        return
      }
      
      if (bend == '') {
        alert('Bendahara Tidak Boleh Kosong');
        return
      }

      if(cek_angkas == '1'){
        var cfm   = confirm("Anggaran Kas msh selisih! Anda Yakin Simpan?") ;    
        if ( cfm == false ){
          return;
        }
      }

      if (isNew) {
        $.ajax({
          type: "POST",
          dataType:'json',
          data: { no_spd: no_spd, tgl: tgl, kd_skpd: kd_skpd, bend: bend, bulan_awal: bulan_awal, bulan_akhir: bulan_akhir, ketentuan: ketentuan },
          url: '<?php echo base_url(); ?>/index.php/spd/insert_spd',
          success: function(response) {
            alert(response.message)
            section1()
          },
          error: function(xhr) {
            response = JSON.parse(xhr.responseText)
            alert(response.message)
          }
        });
      } else {
        $.ajax({
          type: "POST",    
          dataType:'json',                            
          data: { no_spd: no_spd, tgl: tgl, kd_skpd: kd_skpd, bend: bend, ketentuan: ketentuan },
          url: '<?php echo base_url(); ?>/index.php/spd/update_spd',
          success:function(response) {
            alert(response.message)
            section1()
          },
          error: function(response) {
            response = JSON.parse(xhr.responseText)
            alert(response.message)
          }
        });
      }
    })

    $('#del').click(function() {
      var no_spd = $('#nomor').val()
      var kd_skpd = $('#skpd').combogrid('getValue')
      var cnf = confirm('Yakin Ingin Menghapus Data, Nomor SPD : ' + no_spd);
      if (cnf == true) {
        $.ajax({
          url: '<?php echo base_url(); ?>index.php/spd/delete_spd',
          dataType: 'json',
          type: "POST",
          data: { no_spd: no_spd, kd_skpd: kd_skpd },
          success: function(response) {
            alert(response.message)
            status = data.pesan;
            $('#dg').edatagrid('reload');
            section1()
          },
          error: function(xhr) {
            var response = JSON.parse(xhr.responseText)
            alert(response.message)
          }
        })
      }
    })

    function load_angkas_belanja() {
      var kd_skpd = $("#skpd").combogrid("getValue")
      var bulan_awal = $('#bulan1 :selected').val()
      var bulan_akhir = $('#bulan2 :selected').val()
      if (kd_skpd == '' || bulan_awal != bulan_akhir) {
        $('#dg1').edatagrid('loadData', [])
      } else {
        $('#dg1').edatagrid({
          url: '<?php echo base_url(); ?>/index.php/spd/load_angkas_belanja/',
          queryParams: { kd_skpd: kd_skpd, bulan_awal: bulan_awal, bulan_akhir: bulan_akhir },
          method: 'get',
          onLoadSuccess: function(response) {
            $("#total").val(number_format(response.nilai_total, 2, '.', ','))
          }
        })
        $('#dg1').edatagrid('reload')
      }
    }

    $('#toggle_status_spd').click(function () {
      if (isNew) return
      $.ajax({
        type: "POST",
        dataType:'json',
        data: { no_spd: $('#nomor').val(), kd_skpd: $('#skpd').combogrid('getValue') },
        url: '<?php echo base_url(); ?>/index.php/spd/toggle_status_spd',
        success:function(response) {
          $('#dg').edatagrid('reload');
          if (response.is_aktif) {
            $("#id_aktif").html("NON Aktifkan SPD")
            $("#p1").html("SPD AKTIF")
          } else {
            $("#id_aktif").html("Aktifkan SPD")
            $("#p1").html("SPD NON AKTIF")
          }
        },
        error: function(response) {
          alert(response)
        }
      })
    })

    $('.cetak-spd').click(function() {
      var ctk = $(this).data('jenis');
      var cell = document.getElementById('cell').value
      var no_spd = document.getElementById('nomor1').value
      var nipppkd = $('#bendahara_ppkd').combogrid('getValue')
      var nmppkd = document.getElementById('nama_ppkd').value
      var nipppkds = nipppkd.split(" ").join("123456789")

      var nospd = no_spd.split("/").join("spd")
      var tnp_no = 0
      var tambah = 0
      var cell = document.getElementById('cell').value

      if ($('#chk_spd').is(":checked")) {
        tnp_no = 1
      }

      if ($('#chk_tambah').is(":checked")) {
        tambah='Tambahan'
      }
      
      if ($('input[name="cetak"]:checked').val() == '2') {
        var base_url = "<?php echo base_url(); ?>spd/cetak_lampiran_spd"
        // window.open(url+'/'+ctk+'/'+tnp_no+'/'+tambah+'/'+cell+'/'+nospd+'/'+nipppkds, '_blank')
      } else {
        var base_url = "<?php echo base_url(); ?>cetak_otor_spd"
        // window.open(url+'/'+ctk+'/'+tnp_no+'/'+tambah+'/'+cell+'/'+nospd+'/'+nipppkds, '_blank')
      }
      var url = new URL(base_url)
      var params = url.searchParams
      params.append('no_spd', $('#nomor1').val())
      params.append('kop', $('#kop :selected').val())
      params.append('nip_ppkd', $('#bendahara_ppkd').combogrid('getValue'))
      params.append('nama_ppkd', $('#nama_ppkd').val())
      params.append('jabatan_ppkd', $('#jabatan_ppkd').val())
      params.append('pangkat_ppkd', $('#pangkat_ppkd').val())
      params.append('jenis', $(this).data('jenis'))
      params.append('tanpa_nomor', $('#chk_spd').is(":checked") ? 1 : 0)
      params.append('cell', $('#cell').val())
      params.append('watermark', $('#water :selected').val())
      window.open(url.toString(), '_blank')
    })

    $('#cetak').click(function () {
      var nomor = document.getElementById('nomor').value;

      $("#dialog-cetak").dialog('open');
      $('#nomor1').attr('value',nomor);
      $('#chk_spd').attr('checked', false);
      $('#chk_tambah').attr('checked', false);
      $('#cetak').attr('checked', false);
    })
  
    $('#close-cetak').click(function() {
      $("#dialog-cetak").dialog('close');
    })
  })
</script>
</html>