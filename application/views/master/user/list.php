<div id="content">
    	<h1><?php echo $page_title; ?> 
        
        </h1>

		<?php echo form_open('master/cari_user', array('class' => 'basic')); ?>
		Karakter yang di cari :&nbsp;&nbsp;&nbsp;<input type="text" name="pencarian" id="pencarian" value="<?php echo set_value('text'); ?>" />
        <button type='primary' name='cari' class='btn' ><i class="fa fa-search"></i></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
        
        <a type='submit'  href="<?php echo site_url(); ?>master/tambah_user"><i class="fa fa-plus"></i> Tambah User</a>
        <?php echo form_close(); ?>
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
         <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
        <table class="narrow">
        	<tr>
            	<th>ID </th>
                <th>NAMA</th>
                <th>KODE SKPD</th>
				
				<th>AKSI</th>
            </tr>
            <?php foreach($list->result() as $user) : ?>
            <tr>
            	<td><?php echo $user->id_user; ?></td>
                <td><?php echo $user->nama; ?></td>
				<td><?php echo $user->kd_skpd; ?></td>
				
                <td>
                
            <a type='edit' href="<?php echo site_url(); ?>master/edit_user/<?php echo $user->id_user; ?>" title="Edit"><i class="fa fa-pencil"></i></a>
            <a type='delete2' href="<?php echo site_url(); ?>master/hapus_user/<?php echo $user->id_user; ?>" title="Hapus"><i class="fa fa-trash"></i></a>
                 
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->pagination->create_links(); ?> <span class="totalitem">Total Item <?php echo $total_rows ; ?></span>
        <div class="clear"></div>
	</div>