<div class="teams view">
<h2><?php  __('Team');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $team['Team']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $team['Team']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $team['Team']['modified']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $team['Team']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Description'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $team['Team']['description']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Visibility'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $team['Team']['visibility']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Type'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $team['Team']['type']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->link($team['User']['id'], array('controller'=> 'users', 'action'=>'view', $team['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Picture Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $team['Team']['picture_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Default Location'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $team['Team']['default_location']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit Team', true), array('action'=>'edit', $team['Team']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete Team', true), array('action'=>'delete', $team['Team']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $team['Team']['id'])); ?> </li>
		<li><?php echo $html->link(__('List Teams', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Team', true), array('action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Users', true), array('controller'=> 'users', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New User', true), array('controller'=> 'users', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Default Emails', true), array('controller'=> 'default_emails', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Default Email', true), array('controller'=> 'default_emails', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Events', true), array('controller'=> 'events', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Event', true), array('controller'=> 'events', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Players', true), array('controller'=> 'players', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Player', true), array('controller'=> 'players', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Topics', true), array('controller'=> 'topics', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Topic', true), array('controller'=> 'topics', 'action'=>'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Default Emails');?></h3>
	<?php if (!empty($team['DefaultEmail'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th><?php __('Title'); ?></th>
		<th><?php __('Days Before'); ?></th>
		<th><?php __('Content'); ?></th>
		<th><?php __('Sent'); ?></th>
		<th><?php __('Type Regular'); ?></th>
		<th><?php __('Type Sub'); ?></th>
		<th><?php __('Type Member'); ?></th>
		<th><?php __('Attending No Response'); ?></th>
		<th><?php __('Attending No'); ?></th>
		<th><?php __('Attending Yes'); ?></th>
		<th><?php __('Attending Maybe'); ?></th>
		<th><?php __('Condition Count'); ?></th>
		<th><?php __('Condition Type Regular'); ?></th>
		<th><?php __('Condition Type Sub'); ?></th>
		<th><?php __('Condition Type Member'); ?></th>
		<th><?php __('Condition Type'); ?></th>
		<th><?php __('Team Id'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($team['DefaultEmail'] as $defaultEmail):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $defaultEmail['id'];?></td>
			<td><?php echo $defaultEmail['created'];?></td>
			<td><?php echo $defaultEmail['modified'];?></td>
			<td><?php echo $defaultEmail['title'];?></td>
			<td><?php echo $defaultEmail['days_before'];?></td>
			<td><?php echo $defaultEmail['content'];?></td>
			<td><?php echo $defaultEmail['sent'];?></td>
			<td><?php echo $defaultEmail['type_regular'];?></td>
			<td><?php echo $defaultEmail['type_sub'];?></td>
			<td><?php echo $defaultEmail['type_member'];?></td>
			<td><?php echo $defaultEmail['attending_no_response'];?></td>
			<td><?php echo $defaultEmail['attending_no'];?></td>
			<td><?php echo $defaultEmail['attending_yes'];?></td>
			<td><?php echo $defaultEmail['attending_maybe'];?></td>
			<td><?php echo $defaultEmail['condition_count'];?></td>
			<td><?php echo $defaultEmail['condition_type_regular'];?></td>
			<td><?php echo $defaultEmail['condition_type_sub'];?></td>
			<td><?php echo $defaultEmail['condition_type_member'];?></td>
			<td><?php echo $defaultEmail['condition_type'];?></td>
			<td><?php echo $defaultEmail['team_id'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'default_emails', 'action'=>'view', $defaultEmail['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'default_emails', 'action'=>'edit', $defaultEmail['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> 'default_emails', 'action'=>'delete', $defaultEmail['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $defaultEmail['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New Default Email', true), array('controller'=> 'default_emails', 'action'=>'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Events');?></h3>
	<?php if (!empty($team['Event'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th><?php __('Name'); ?></th>
		<th><?php __('Start'); ?></th>
		<th><?php __('End'); ?></th>
		<th><?php __('Description'); ?></th>
		<th><?php __('Visibility'); ?></th>
		<th><?php __('Team Id'); ?></th>
		<th><?php __('Picture Id'); ?></th>
		<th><?php __('Default Response'); ?></th>
		<th><?php __('Hours Behind'); ?></th>
		<th><?php __('Location'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($team['Event'] as $event):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $event['id'];?></td>
			<td><?php echo $event['created'];?></td>
			<td><?php echo $event['modified'];?></td>
			<td><?php echo $event['name'];?></td>
			<td><?php echo $event['start'];?></td>
			<td><?php echo $event['end'];?></td>
			<td><?php echo $event['description'];?></td>
			<td><?php echo $event['visibility'];?></td>
			<td><?php echo $event['team_id'];?></td>
			<td><?php echo $event['picture_id'];?></td>
			<td><?php echo $event['default_response'];?></td>
			<td><?php echo $event['hours_behind'];?></td>
			<td><?php echo $event['location'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'events', 'action'=>'view', $event['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'events', 'action'=>'edit', $event['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> 'events', 'action'=>'delete', $event['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $event['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New Event', true), array('controller'=> 'events', 'action'=>'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Players');?></h3>
	<?php if (!empty($team['Player'])):?>
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
		foreach ($team['Player'] as $player):
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
	<h3><?php __('Related Topics');?></h3>
	<?php if (!empty($team['Topic'])):?>
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
		foreach ($team['Topic'] as $topic):
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
	<h3><?php __('Related Users');?></h3>
	<?php if (!empty($team['User'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th><?php __('Email'); ?></th>
		<th><?php __('Password'); ?></th>
		<th><?php __('Picture Id'); ?></th>
		<th><?php __('First Name'); ?></th>
		<th><?php __('Last Name'); ?></th>
		<th><?php __('Last Login'); ?></th>
		<th><?php __('Password Change Key'); ?></th>
		<th><?php __('Password Forgotten'); ?></th>
		<th><?php __('Feed Id'); ?></th>
		<th><?php __('Ip'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($team['User'] as $user):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $user['id'];?></td>
			<td><?php echo $user['created'];?></td>
			<td><?php echo $user['modified'];?></td>
			<td><?php echo $user['email'];?></td>
			<td><?php echo $user['password'];?></td>
			<td><?php echo $user['picture_id'];?></td>
			<td><?php echo $user['first_name'];?></td>
			<td><?php echo $user['last_name'];?></td>
			<td><?php echo $user['last_login'];?></td>
			<td><?php echo $user['password_change_key'];?></td>
			<td><?php echo $user['password_forgotten'];?></td>
			<td><?php echo $user['feed_id'];?></td>
			<td><?php echo $user['ip'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'users', 'action'=>'view', $user['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'users', 'action'=>'edit', $user['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> 'users', 'action'=>'delete', $user['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $user['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New User', true), array('controller'=> 'users', 'action'=>'add'));?> </li>
		</ul>
	</div>
</div>
