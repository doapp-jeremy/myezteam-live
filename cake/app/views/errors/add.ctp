<div class="errors form">
<?php echo $form->create('Error');?>
	<fieldset>
 		<legend><?php __('Add Error');?></legend>
	<?php
		echo $form->input('function');
		echo $form->input('message');
		echo $form->input('user_Id');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List Errors', true), array('action'=>'index'));?></li>
	</ul>
</div>
