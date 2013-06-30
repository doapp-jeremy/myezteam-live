<div class="responses form">
<?php echo $form->create('Response');?>
	<fieldset>
 		<legend><?php __('Add Response');?></legend>
	<?php
		echo $form->input('attending');
		echo $form->input('comment');
		echo $form->input('record_id');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List Responses', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Records', true), array('controller'=> 'records', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Record', true), array('controller'=> 'records', 'action'=>'add')); ?> </li>
	</ul>
</div>
