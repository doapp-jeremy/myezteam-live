<div class="users view">
<h2><?php  __('User');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $user['User']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $user['User']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $user['User']['modified']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Email'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $user['User']['email']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Password'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $user['User']['password']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Picture'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->link($user['Picture']['id'], array('controller'=> 'pictures', 'action'=>'view', $user['Picture']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('First Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $user['User']['first_name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Last Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $user['User']['last_name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Last Login'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $user['User']['last_login']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Password Change Key'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $user['User']['password_change_key']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Password Forgotten'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $user['User']['password_forgotten']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Feed Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $user['User']['feed_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Ip'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $user['User']['ip']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit User', true), array('action'=>'edit', $user['User']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete User', true), array('action'=>'delete', $user['User']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $user['User']['id'])); ?> </li>
		<li><?php echo $html->link(__('List Users', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New User', true), array('action'=>'add')); ?> </li>
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
	<div class="related">
		<h3><?php  __('Related Pictures');?></h3>
	<?php if (!empty($user['Picture'])):?>
		<dl>	<?php $i = 0; $class = ' class="altrow"';?>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['Picture']['id'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['Picture']['created'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['Picture']['modified'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Path');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['Picture']['path'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User Id');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['Picture']['user_id'];?>
&nbsp;</dd>
		</dl>
	<?php endif; ?>
		<div class="actions">
			<ul>
				<li><?php echo $html->link(__('Edit Picture', true), array('controller'=> 'pictures', 'action'=>'edit', $user['Picture']['id'])); ?></li>
			</ul>
		</div>
	</div>
		<div class="related">
		<h3><?php  __('Related Topics');?></h3>
	<?php if (!empty($user['Topic'])):?>
		<dl>	<?php $i = 0; $class = ' class="altrow"';?>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['Topic']['id'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['Topic']['created'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['Topic']['modified'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Team Id');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['Topic']['team_id'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Event Id');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['Topic']['event_id'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User Id');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['Topic']['user_id'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['Topic']['title'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Ip');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['Topic']['ip'];?>
&nbsp;</dd>
		</dl>
	<?php endif; ?>
		<div class="actions">
			<ul>
				<li><?php echo $html->link(__('Edit Topic', true), array('controller'=> 'topics', 'action'=>'edit', $user['Topic']['id'])); ?></li>
			</ul>
		</div>
	</div>
		<div class="related">
		<h3><?php  __('Related User Emails');?></h3>
	<?php if (!empty($user['UserEmail'])):?>
		<dl>	<?php $i = 0; $class = ' class="altrow"';?>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['UserEmail']['id'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['UserEmail']['created'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['UserEmail']['modified'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User Id');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['UserEmail']['user_id'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Email');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['UserEmail']['email'];?>
&nbsp;</dd>
		</dl>
	<?php endif; ?>
		<div class="actions">
			<ul>
				<li><?php echo $html->link(__('Edit User Email', true), array('controller'=> 'user_emails', 'action'=>'edit', $user['UserEmail']['id'])); ?></li>
			</ul>
		</div>
	</div>
		<div class="related">
		<h3><?php  __('Related User Ips');?></h3>
	<?php if (!empty($user['UserIp'])):?>
		<dl>	<?php $i = 0; $class = ' class="altrow"';?>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['UserIp']['id'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['UserIp']['created'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['UserIp']['modified'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User Id');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['UserIp']['user_id'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Ip');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $user['UserIp']['ip'];?>
&nbsp;</dd>
		</dl>
	<?php endif; ?>
		<div class="actions">
			<ul>
				<li><?php echo $html->link(__('Edit User Ip', true), array('controller'=> 'user_ips', 'action'=>'edit', $user['UserIp']['id'])); ?></li>
			</ul>
		</div>
	</div>
	<div class="related">
	<h3><?php __('Related Messages');?></h3>
	<?php if (!empty($user['Message'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th><?php __('Msg'); ?></th>
		<th><?php __('Title'); ?></th>
		<th><?php __('Link'); ?></th>
		<th><?php __('Response Id'); ?></th>
		<th><?php __('Event Id'); ?></th>
		<th><?php __('User Id'); ?></th>
		<th><?php __('Del'); ?></th>
		<th><?php __('Save'); ?></th>
		<th><?php __('Topic Id'); ?></th>
		<th><?php __('Post Id'); ?></th>
		<th><?php __('Error Id'); ?></th>
		<th><?php __('New User Id'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($user['Message'] as $message):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $message['id'];?></td>
			<td><?php echo $message['created'];?></td>
			<td><?php echo $message['modified'];?></td>
			<td><?php echo $message['msg'];?></td>
			<td><?php echo $message['title'];?></td>
			<td><?php echo $message['link'];?></td>
			<td><?php echo $message['response_id'];?></td>
			<td><?php echo $message['event_id'];?></td>
			<td><?php echo $message['user_id'];?></td>
			<td><?php echo $message['del'];?></td>
			<td><?php echo $message['save'];?></td>
			<td><?php echo $message['topic_id'];?></td>
			<td><?php echo $message['post_id'];?></td>
			<td><?php echo $message['error_id'];?></td>
			<td><?php echo $message['new_user_id'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'messages', 'action'=>'view', $message['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'messages', 'action'=>'edit', $message['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> 'messages', 'action'=>'delete', $message['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $message['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New Message', true), array('controller'=> 'messages', 'action'=>'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Pictures');?></h3>
	<?php if (!empty($user['Picture'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th><?php __('Path'); ?></th>
		<th><?php __('User Id'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($user['Picture'] as $picture):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $picture['id'];?></td>
			<td><?php echo $picture['created'];?></td>
			<td><?php echo $picture['modified'];?></td>
			<td><?php echo $picture['path'];?></td>
			<td><?php echo $picture['user_id'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'pictures', 'action'=>'view', $picture['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'pictures', 'action'=>'edit', $picture['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> 'pictures', 'action'=>'delete', $picture['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $picture['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New Picture', true), array('controller'=> 'pictures', 'action'=>'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Players');?></h3>
	<?php if (!empty($user['Player'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th><?php __('Type'); ?></th>
		<th><?php __('Team Id'); ?></th>
		<th><?php __('Contact Id'); ?></th>
		<th><?php __('User Id'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($user['Player'] as $player):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $player['id'];?></td>
			<td><?php echo $player['created'];?></td>
			<td><?php echo $player['modified'];?></td>
			<td><?php echo $player['type'];?></td>
			<td><?php echo $player['team_id'];?></td>
			<td><?php echo $player['contact_id'];?></td>
			<td><?php echo $player['user_id'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'players', 'action'=>'view', $player['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'players', 'action'=>'edit', $player['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> 'players', 'action'=>'delete', $player['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $player['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New Player', true), array('controller'=> 'players', 'action'=>'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Posts');?></h3>
	<?php if (!empty($user['Post'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th><?php __('Topic Id'); ?></th>
		<th><?php __('User Id'); ?></th>
		<th><?php __('Text'); ?></th>
		<th><?php __('Ip'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($user['Post'] as $post):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $post['id'];?></td>
			<td><?php echo $post['created'];?></td>
			<td><?php echo $post['modified'];?></td>
			<td><?php echo $post['topic_id'];?></td>
			<td><?php echo $post['user_id'];?></td>
			<td><?php echo $post['text'];?></td>
			<td><?php echo $post['ip'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'posts', 'action'=>'view', $post['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'posts', 'action'=>'edit', $post['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> 'posts', 'action'=>'delete', $post['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $post['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New Post', true), array('controller'=> 'posts', 'action'=>'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Teams');?></h3>
	<?php if (!empty($user['Team'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th><?php __('Name'); ?></th>
		<th><?php __('Description'); ?></th>
		<th><?php __('Visibility'); ?></th>
		<th><?php __('Type'); ?></th>
		<th><?php __('User Id'); ?></th>
		<th><?php __('Picture Id'); ?></th>
		<th><?php __('Default Location'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($user['Team'] as $team):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $team['id'];?></td>
			<td><?php echo $team['created'];?></td>
			<td><?php echo $team['modified'];?></td>
			<td><?php echo $team['name'];?></td>
			<td><?php echo $team['description'];?></td>
			<td><?php echo $team['visibility'];?></td>
			<td><?php echo $team['type'];?></td>
			<td><?php echo $team['user_id'];?></td>
			<td><?php echo $team['picture_id'];?></td>
			<td><?php echo $team['default_location'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'teams', 'action'=>'view', $team['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'teams', 'action'=>'edit', $team['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> 'teams', 'action'=>'delete', $team['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $team['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New Team', true), array('controller'=> 'teams', 'action'=>'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Topics');?></h3>
	<?php if (!empty($user['Topic'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th><?php __('Team Id'); ?></th>
		<th><?php __('Event Id'); ?></th>
		<th><?php __('User Id'); ?></th>
		<th><?php __('Title'); ?></th>
		<th><?php __('Ip'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($user['Topic'] as $topic):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $topic['id'];?></td>
			<td><?php echo $topic['created'];?></td>
			<td><?php echo $topic['modified'];?></td>
			<td><?php echo $topic['team_id'];?></td>
			<td><?php echo $topic['event_id'];?></td>
			<td><?php echo $topic['user_id'];?></td>
			<td><?php echo $topic['title'];?></td>
			<td><?php echo $topic['ip'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'topics', 'action'=>'view', $topic['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'topics', 'action'=>'edit', $topic['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> 'topics', 'action'=>'delete', $topic['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $topic['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New Topic', true), array('controller'=> 'topics', 'action'=>'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related User Emails');?></h3>
	<?php if (!empty($user['UserEmail'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th><?php __('User Id'); ?></th>
		<th><?php __('Email'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($user['UserEmail'] as $userEmail):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $userEmail['id'];?></td>
			<td><?php echo $userEmail['created'];?></td>
			<td><?php echo $userEmail['modified'];?></td>
			<td><?php echo $userEmail['user_id'];?></td>
			<td><?php echo $userEmail['email'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'user_emails', 'action'=>'view', $userEmail['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'user_emails', 'action'=>'edit', $userEmail['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> 'user_emails', 'action'=>'delete', $userEmail['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $userEmail['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New User Email', true), array('controller'=> 'user_emails', 'action'=>'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related User Ips');?></h3>
	<?php if (!empty($user['UserIp'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th><?php __('User Id'); ?></th>
		<th><?php __('Ip'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($user['UserIp'] as $userIp):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $userIp['id'];?></td>
			<td><?php echo $userIp['created'];?></td>
			<td><?php echo $userIp['modified'];?></td>
			<td><?php echo $userIp['user_id'];?></td>
			<td><?php echo $userIp['ip'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'user_ips', 'action'=>'view', $userIp['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'user_ips', 'action'=>'edit', $userIp['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> 'user_ips', 'action'=>'delete', $userIp['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $userIp['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New User Ip', true), array('controller'=> 'user_ips', 'action'=>'add'));?> </li>
		</ul>
	</div>
</div>
