<div class="events view">
<h2><?php  __('Event');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $event['Event']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $event['Event']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $event['Event']['modified']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $event['Event']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Start'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $event['Event']['start']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('End'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $event['Event']['end']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Description'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $event['Event']['description']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Visibility'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $event['Event']['visibility']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Team'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->link($event['Team']['name'], array('controller'=> 'teams', 'action'=>'view', $event['Team']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Picture Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $event['Event']['picture_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Default Response'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $event['Event']['default_response']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Hours Behind'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $event['Event']['hours_behind']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Location'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $event['Event']['location']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit Event', true), array('action'=>'edit', $event['Event']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete Event', true), array('action'=>'delete', $event['Event']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $event['Event']['id'])); ?> </li>
		<li><?php echo $html->link(__('List Events', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Event', true), array('action'=>'add')); ?> </li>
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
<div class="related">
	<h3><?php __('Related Emails');?></h3>
	<?php if (!empty($event['Email'])):?>
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
		<th><?php __('Event Id'); ?></th>
		<th><?php __('Rsvp'); ?></th>
		<th><?php __('Send'); ?></th>
		<th><?php __('Send On'); ?></th>
		<th><?php __('Manager'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($event['Email'] as $email):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $email['id'];?></td>
			<td><?php echo $email['created'];?></td>
			<td><?php echo $email['modified'];?></td>
			<td><?php echo $email['title'];?></td>
			<td><?php echo $email['days_before'];?></td>
			<td><?php echo $email['content'];?></td>
			<td><?php echo $email['sent'];?></td>
			<td><?php echo $email['type_regular'];?></td>
			<td><?php echo $email['type_sub'];?></td>
			<td><?php echo $email['type_member'];?></td>
			<td><?php echo $email['attending_no_response'];?></td>
			<td><?php echo $email['attending_no'];?></td>
			<td><?php echo $email['attending_yes'];?></td>
			<td><?php echo $email['attending_maybe'];?></td>
			<td><?php echo $email['condition_count'];?></td>
			<td><?php echo $email['condition_type_regular'];?></td>
			<td><?php echo $email['condition_type_sub'];?></td>
			<td><?php echo $email['condition_type_member'];?></td>
			<td><?php echo $email['condition_type'];?></td>
			<td><?php echo $email['event_id'];?></td>
			<td><?php echo $email['rsvp'];?></td>
			<td><?php echo $email['send'];?></td>
			<td><?php echo $email['send_on'];?></td>
			<td><?php echo $email['manager'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'emails', 'action'=>'view', $email['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'emails', 'action'=>'edit', $email['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> 'emails', 'action'=>'delete', $email['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $email['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New Email', true), array('controller'=> 'emails', 'action'=>'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Records');?></h3>
	<?php if (!empty($event['Record'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th><?php __('Event Id'); ?></th>
		<th><?php __('Player Id'); ?></th>
		<th><?php __('Response Key'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($event['Record'] as $record):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $record['id'];?></td>
			<td><?php echo $record['created'];?></td>
			<td><?php echo $record['modified'];?></td>
			<td><?php echo $record['event_id'];?></td>
			<td><?php echo $record['player_id'];?></td>
			<td><?php echo $record['response_key'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'records', 'action'=>'view', $record['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'records', 'action'=>'edit', $record['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> 'records', 'action'=>'delete', $record['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $record['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New Record', true), array('controller'=> 'records', 'action'=>'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Topics');?></h3>
	<?php if (!empty($event['Topic'])):?>
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
		foreach ($event['Topic'] as $topic):
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
