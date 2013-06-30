<div class="users form">
<?php echo $form->create('User');?>
	<fieldset>
 		<legend><?php __('Edit User');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('email');
		echo $form->input('password');
		echo $form->input('picture_id');
		echo $form->input('first_name');
		echo $form->input('last_name');
		echo $form->input('last_login');
		echo $form->input('password_change_key');
		echo $form->input('password_forgotten');
		echo $form->input('feed_id');
		echo $form->input('ip');
		echo $form->input('Player');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('User.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('User.id'))); ?></li>
		<li><?php echo $html->link(__('List Users', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Pictures', true), array('controller'=> 'pictures', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Picture', true), array('controller'=> 'pictures', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Topics', true), array('controller'=> 'topics', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Topic', true), array('controller'=> 'topics', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List User Emails', true), array('controller'=> 'user_emails', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New User Email', true), array('controller'=> 'user_emails', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List User Ips', true), array('controller'=> 'user_ips', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New User Ip', true), array('controller'=> 'user_ips', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Messages', true), array('controller'=> 'messages', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Message', true), array('controller'=> 'messages', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Players', true), array('controller'=> 'players', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Player', true), array('controller'=> 'players', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Posts', true), array('controller'=> 'posts', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Post', true), array('controller'=> 'posts', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Teams', true), array('controller'=> 'teams', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Team', true), array('controller'=> 'teams', 'action'=>'add')); ?> </li>
	</ul>
</div>
