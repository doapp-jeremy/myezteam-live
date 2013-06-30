<div class="events form">
<?php echo $form->create('Event');?>
	<fieldset>
 		<legend><?php __('Edit Event');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('name');
		echo $form->input('start');
		echo $form->input('end');
		echo $form->input('description');
		echo $form->input('visibility');
		echo $form->input('team_id');
		echo $form->input('picture_id');
		echo $form->input('default_response');
		echo $form->input('hours_behind');
		echo $form->input('location');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('Event.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Event.id'))); ?></li>
		<li><?php echo $html->link(__('List Events', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Teams', true), array('controller'=> 'teams', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Team', true), array('controller'=> 'teams', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Emails', true), array('controller'=> 'emails', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Email', true), array('controller'=> 'emails', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Records', true), array('controller'=> 'records', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Record', true), array('controller'=> 'records', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Topics', true), array('controller'=> 'topics', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Topic', true), array('controller'=> 'topics', 'action'=>'add')); ?> </li>
	</ul>
</div>
