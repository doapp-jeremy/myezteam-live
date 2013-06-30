<div class="emails form">
<?php echo $form->create('Email');?>
	<fieldset>
 		<legend><?php __('Add Email');?></legend>
	<?php
		echo $form->input('title');
		echo $form->input('days_before');
		echo $form->input('content');
		echo $form->input('sent');
		echo $form->input('type_regular');
		echo $form->input('type_sub');
		echo $form->input('type_member');
		echo $form->input('attending_no_response');
		echo $form->input('attending_no');
		echo $form->input('attending_yes');
		echo $form->input('attending_maybe');
		echo $form->input('condition_count');
		echo $form->input('condition_type_regular');
		echo $form->input('condition_type_sub');
		echo $form->input('condition_type_member');
		echo $form->input('condition_type');
		echo $form->input('event_id');
		echo $form->input('rsvp');
		echo $form->input('send');
		echo $form->input('send_on');
		echo $form->input('manager');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List Emails', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Events', true), array('controller'=> 'events', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Event', true), array('controller'=> 'events', 'action'=>'add')); ?> </li>
	</ul>
</div>
