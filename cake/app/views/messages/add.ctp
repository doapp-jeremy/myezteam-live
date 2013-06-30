<div class="messages form">
<?php echo $form->create('Message');?>
	<fieldset>
 		<legend><?php __('Add Message');?></legend>
	<?php
		echo $form->input('msg');
		echo $form->input('title');
		echo $form->input('link');
		echo $form->input('response_id');
		echo $form->input('event_id');
		echo $form->input('user_id');
		echo $form->input('del');
		echo $form->input('save');
		echo $form->input('topic_id');
		echo $form->input('post_id');
		echo $form->input('error_id');
		echo $form->input('new_user_id');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List Messages', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Responses', true), array('controller'=> 'responses', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Response', true), array('controller'=> 'responses', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Events', true), array('controller'=> 'events', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Event', true), array('controller'=> 'events', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Users', true), array('controller'=> 'users', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New User', true), array('controller'=> 'users', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Topics', true), array('controller'=> 'topics', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Topic', true), array('controller'=> 'topics', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Posts', true), array('controller'=> 'posts', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Post', true), array('controller'=> 'posts', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Errors', true), array('controller'=> 'errors', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Error', true), array('controller'=> 'errors', 'action'=>'add')); ?> </li>
	</ul>
</div>
