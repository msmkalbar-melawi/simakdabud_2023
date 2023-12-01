

	<div id="content">      
    	<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp; 
       </h1>
 
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
 	            <th>Pilihan </th>            	
                <th>Aksi</th>
            </tr>
            <tr>                
            <tr>                
                <td><?php echo 'ALOKASI HIBAH'; ?></td>            	
                <td>
                    <a href="<?php echo site_url(); ?>/rka/preview_perkadaIa_penetapan/<?php echo 'HIBAH'; ?>/<?php echo '0' ?>">
                    <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="preview"/></a>
                    <a href="<?php echo site_url(); ?>/rka/preview_perkadaIa_penetapan/<?php echo 'HIBAH'; ?>/<?php echo '1';?>"target='_blank'>
                    <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
                </td>
            </tr>
            <tr>                
                <td><?php echo 'ALOKASI BANTUAN'; ?></td>            	
                <td>
                    <a href="<?php echo site_url(); ?>/rka/preview_perkadaIa_penetapan/<?php echo 'BANTU'; ?>/<?php echo '0' ?>">
                    <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="preview"/></a>
                    <a href="<?php echo site_url(); ?>/rka/preview_perkadaIa_penetapan/<?php echo 'BANTU'; ?>/<?php echo '1';?>"target='_blank'>
                    <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
                </td>
            </tr>
        </table>
 
        <div class="clear"></div>
	</div>