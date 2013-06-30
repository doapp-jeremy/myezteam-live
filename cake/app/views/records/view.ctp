<div class="records view">
<h2><?php  __('Record');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $record['Record']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $record['Record']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $record['Record']['modified']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Event'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->link($record['Event']['name'], array('controller'=> 'events', 'action'=>'view', $record['Event']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Player'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->link($record['Player']['id'], array('controller'=> 'players', 'action'=>'view', $record['Player']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Response Key'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $record['Record']['response_key']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit Record', true), array('action'=>'edit', $record['Record']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete Record', true), array('action'=>'delete', $record['Record']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $record['Record']['id'])); ?> </li>
		<li><?php echo $html->link(__('List Records', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Record', true), array('action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Events', true), array('controller'=> 'events', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Event', true), array('controller'=> 'events', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Players', true), array('controller'=> 'players', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Player', true), array('controller'=> 'players', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Responses', true), array('controller'=> 'responses', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Response', true), array('controller'=> 'responses', 'action'=>'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Responses');?></h3>
	<?php if (!empty($record['Response'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th><?php __('Attending'); ?></th>
		<th><?php __('Comment'); ?></th>
		<th><?php __('Record Id'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($record['Response'] as $response):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $response['id'];?></td>
			<td><?php echo $response['created'];?></td>
			<td><?php echo $response['modified'];?></td>
			<td><?php echo $response['attending'];?></td>
			<td><?php echo $response['comment'];?></td>
			<td><?php echo $response['record_id'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'responses', 'action'=>'view', $response['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'responses', 'action'=>'edit', $response['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> 'responses', 'action'=>'delete', $response['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $response['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New Response', true), array('controller'=> 'responses', 'action'=>'add'));?> </li>
		</ul>
	</div>
</div>
