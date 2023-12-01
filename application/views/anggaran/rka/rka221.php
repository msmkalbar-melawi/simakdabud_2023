

	<div id="content">        
    	<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp; 
       </h1>
        <?php echo form_open('rka/cari221', array('class' => 'basic')); ?>
		Karakter yang di cari :&nbsp;&nbsp;&nbsp;<input type="text" name="nm_skpd" id="nm_skpd" value="<?php echo set_value('text'); ?>" />
        <input type='submit' name='cari' value='cari' class='btn' />
        <?php echo form_close(); ?>   
		
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
 	            <th>Kode SKPD </th>            	
                <th>Nama SKPD</th>                
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $skpd) : ?>
            <tr>
                <td><?php echo $skpd->kd_skpd; ?></td>            	
                <td><?php echo $skpd->nm_skpd; ?></td>  
                <td>                     
                    <a href="<?php echo site_url(); ?>/rka/daftar_kegiatan/<?php echo $skpd->kd_skpd; ?>">Pilih</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?> <span class="totalitem">Total Item <?php echo $total_rows ;  ?></span>
        <div class="clear"></div>
	</div>