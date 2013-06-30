<div class="pictures form">
<?php echo $form->create('Picture');?>
	<fieldset>
 		<legend><?php __('Edit Picture');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('path');
		echo $form->input('user_id');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('Picture.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Picture.id'))); ?></li>
		<li><?php echo $html->link(__('List Pictures', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Users', true), array('controller'=> 'users', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New User', true), array('controller'=> 'users', 'action'=>'add')); ?> </li>
	</ul>
</div>
