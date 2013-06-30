<div class="players view">
<h2><?php  __('Player');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $player['Player']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $player['Player']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $player['Player']['modified']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Type'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $player['Player']['type']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Team'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->link($player['Team']['name'], array('controller'=> 'teams', 'action'=>'view', $player['Team']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Contact Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $player['Player']['contact_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->link($player['User']['id'], array('controller'=> 'users', 'action'=>'view', $player['User']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit Player', true), array('action'=>'edit', $player['Player']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete Player', true), array('action'=>'delete', $player['Player']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $player['Player']['id'])); ?> </li>
		<li><?php echo $html->link(__('List Players', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Player', true), array('action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Teams', true), array('controller'=> 'teams', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Team', true), array('controller'=> 'teams', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Users', true), array('controller'=> 'users', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New User', true), array('controller'=> 'users', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Records', true), array('controller'=> 'records', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Record', true), array('controller'=> 'records', 'action'=>'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Records');?></h3>
	<?php if (!empty($player['Record'])):?>
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
		foreach ($player['Record'] as $record):
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
