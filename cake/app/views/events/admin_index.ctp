<div class="events index">
<h2><?php __('Events');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('id');?></th>
	<th><?php echo $paginator->sort('created');?></th>
	<th><?php echo $paginator->sort('modified');?></th>
	<th><?php echo $paginator->sort('name');?></th>
	<th><?php echo $paginator->sort('start');?></th>
	<th><?php echo $paginator->sort('end');?></th>
	<th><?php echo $paginator->sort('description');?></th>
	<th><?php echo $paginator->sort('visibility');?></th>
	<th><?php echo $paginator->sort('team_id');?></th>
	<th><?php echo $paginator->sort('picture_id');?></th>
	<th><?php echo $paginator->sort('default_response');?></th>
	<th><?php echo $paginator->sort('hours_behind');?></th>
	<th><?php echo $paginator->sort('location');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($events as $event):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $event['Event']['id']; ?>
		</td>
		<td>
			<?php echo $event['Event']['created']; ?>
		</td>
		<td>
			<?php echo $event['Event']['modified']; ?>
		</td>
		<td>
			<?php echo $event['Event']['name']; ?>
		</td>
		<td>
			<?php echo $event['Event']['start']; ?>
		</td>
		<td>
			<?php echo $event['Event']['end']; ?>
		</td>
		<td>
			<?php echo $event['Event']['description']; ?>
		</td>
		<td>
			<?php echo $event['Event']['visibility']; ?>
		</td>
		<td>
			<?php echo $html->link($event['Team']['name'], array('controller'=> 'teams', 'action'=>'view', $event['Team']['id'])); ?>
		</td>
		<td>
			<?php echo $event['Event']['picture_id']; ?>
		</td>
		<td>
			<?php echo $event['Event']['default_response']; ?>
		</td>
		<td>
			<?php echo $event['Event']['hours_behind']; ?>
		</td>
		<td>
			<?php echo $event['Event']['location']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $event['Event']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $event['Event']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $event['Event']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $event['Event']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('New Event', true), array('action'=>'add')); ?></li>
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
