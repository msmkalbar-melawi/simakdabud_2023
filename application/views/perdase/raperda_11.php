<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $judul ?></title>

	<style>
		.bold {
			font-weight: bold;
		}

		.kp{
			font-weight: bold; text-align: center;
		}
	</style>
</head>

<body>
	
	<table style="margin-bottom:10px; border-collapse:collapse;font-size:12px;font-family:Bookman Old Style" width="100%" border="0" cellspacing="0" cellpadding="0" align="center">	
		<tr>
			<td width="50%"></td>
			<td width="10%"></td>
			<td width="40%"><p style="font-size: 12px">	<?php echo $isijudul ?></p></td>
		</tr>
	
		<tr>
			<td align="center" colspan="4" width="100%"><br><b>PEMERINTAH KABUPATEN MELAWI</td>
			
		</tr>
		<tr>
			<td align="center" colspan="4" width="100%"><b><?php echo $namalampiran ?></td>

		</tr>
		<tr>
			<td align="center" colspan="4" width="100%"><b><?php echo $tahunanggaran ?></td>
		</tr>
	</table>	
	<table style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="1" cellspacing="0" cellpadding="5" align="center">
		<thead>
			<tr class="kp">
				<td colspan="3" align="center" bgcolor="#CCCCCC" rowspan="2"><b>Kode Rekening</td>
				<td align="center" bgcolor="#CCCCCC" rowspan="2"><b>Urusan Pemerintah Daerah</td>
				<td colspan="2" align="center" bgcolor="#CCCCCC"><b>Jumlah (Rp)</td>
				<td colspan="2" align="center" bgcolor="#CCCCCC"><b>Bertambah/Berkurang</td>
			</tr>
			<tr class="kp">
				<td align="center" width="12%" bgcolor="#CCCCCC"><b>Anggaran Setelah Perubahan</td>
				<td align="center" width="12%" bgcolor="#CCCCCC"><b>Realisasi</td>
				<td align="center" width="12%" bgcolor="#CCCCCC"><b>Rp</td>
				<td align="center" width="5%" bgcolor="#CCCCCC"><b>%</td>
			</tr>
		</thead>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td><b>Pendapatan</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		<?php $belanja=0; $pendapatan=0; $belanjar=0; $pendapatanr=0; $tot=0; $tot1=0 ;$totsur=0?>	
		<?php foreach($rek4->result() as $oke) :
			$urutan=array(4,5); 
			if(in_array($oke->urut, $urutan)==true){
				$kd1='';$kd2='';$kd_skpd='';
			}else{
				$kd1=$oke->kd1;
				$kd2=$oke->kd2;
				$kd_skpd=$oke->kd_skpd;	
			}
			if($oke->urut==1){
				$pendapatan=$pendapatan+$oke->nilai;
				$pendapatanr=$pendapatanr+$oke->realisasi;
			}
			if ($oke->nilai != 0){
				$tot1= $oke->realisasi/$oke->nilai*100;
			}else {
				$tot1 = 0;
			}
			?>
			<tr >
				<td align="center" width="3%"><?php echo $kd1 ?></td>
				<td align="center" width="4%"><?php echo $kd2 ?></td>
				<td align="center" ><?php echo $kd_skpd ?></td>
				<td align="left" ><?php echo $oke->bidurusan ?></td>
				<td align="right" ><?php echo $this->support->rp_minus($oke->nilai )?></td>
				<td align="right" ><?php echo $this->support->rp_minus($oke->realisasi )?></td>
				<td align="right" ><?php echo $this->support->rp_minus($oke->nilai-$oke->realisasi)?></td>
				<td align="center"><?php echo $this->support->rp_minus($tot1)?></td>
			</tr>
		<?php endforeach; ?>
				<tr>
				<td></td>
				<td></td>
				<td></td>
				<td><b>&nbsp;</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td><b>Belanja</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		<?php foreach($rek5->result() as $oke) :
			$urutan=array(4,5); 
			if(in_array($oke->urut, $urutan)==true){
				$kd1='';$kd2='';$kd_skpd='';
			}else{
				$kd1=$oke->kd1;
				$kd2=$oke->kd2;
				$kd_skpd=$oke->kd_skpd;	
			}

			
			if($oke->urut==1){
				$belanja=$belanja+$oke->nilai;
				$belanjar=$belanjar+$oke->realisasi;
            }
			if ($oke->nilai != 0){
				$tot1= $oke->realisasi/$oke->nilai*100;
			}else {
				$tot1 = 0;
			}
          
            ?>
             
			<tr >
				<td align="center" ><?php echo $kd1 ?></td>
				<td align="center" ><?php echo $kd2 ?></td>
				<td align="center" ><?php echo $kd_skpd ?></td>
				<td align="left" > <?php echo $oke->bidurusan ?></td>
				<td align="right" ><?php echo $this->support->rp_minus($oke->nilai )?></td>
				<td align="right" ><?php echo $this->support->rp_minus($oke->realisasi )?></td>
				<td align="right" ><?php echo $this->support->rp_minus($oke->nilai - $oke->realisasi)?></td>
				<td align="center"><?php echo $this->support->rp_minus($tot1)?></td>
			</tr>
		<?php endforeach; ?>
		<?php $suplus_anggaran= $pendapatan-$belanja ;
			  $suplus_realisasi= $pendapatanr-$belanjar ;
			  
			  if ($suplus_anggaran != 0){
				  $totsur= $suplus_realisasi/$suplus_anggaran*100;;
			  }else {
				  $totsur = 0;
  
			  }
			 
			 ?>
			<tr >
				<td align="center" colspan="4">SURPLUS/ DEFISIT</td>
				<td align="right" ><?php echo $this->support->rp_minus($suplus_anggaran)?></td>
				<td align="right" ><?php echo $this->support->rp_minus($suplus_realisasi)?></td>
				<td align="right" ><?php echo $this->support->rp_minus($suplus_anggaran-$suplus_realisasi)?></td>
				<td align="center"><?php echo $this->support->rp_minus($totsur)?></td>
			</tr>
		</table>
		<table style="margin-top:10px; border-collapse:collapse;font-size:13px;font-family:Bookman Old Style" width="100%" border="0" cellspacing="0" cellpadding="0" align="center">	
			<tr>
				<td align="center" width="50%"></td>
				<td align="center" width="50%"><br>Melawi, <?php echo $tglttd; ?>
					<br>
					<b><?= $jabatan ?></b><br><br><br><br><br><br><b><u><?php echo $ttdnama ?></b></u><br>
					<?php 
					if($nip == "00000000 000000 0 000"){
						echo "-";

					} else {
						echo $nip;
					}
					?>
				</td>
			</tr>
		</table>
</body>
</html>