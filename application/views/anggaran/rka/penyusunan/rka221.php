

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
                 <td bgcolor="#38b869" width="20%" align="center"><font color="white">KODE SKPD</font></td>
                <td bgcolor="#38b869" align="center"><font color="white">NAMA SKPD</font></td>
                <td bgcolor="#38b869" width="10%"align="center"><font color="white">AKSI</font></td>
            </tr>
            <?php foreach($list->result() as $skpd) : ?>
            <tr>
                <td><?php echo $skpd->kd_skpd; ?></td>            	
                <td><?php echo $skpd->nm_skpd; ?></td>  
                <td>                     
                    <a type="primary" href="<?php echo site_url(); ?>daftar_kegiatan_penyusunan/<?php echo $skpd->kd_skpd; ?>">Pilih</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?> <span class="totalitem">Total Item <?php echo $total_rows ;  ?></span>
        <div class="clear"></div>
	</div>