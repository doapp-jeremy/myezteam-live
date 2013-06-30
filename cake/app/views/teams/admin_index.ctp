<div class="teams index">
<h2><?php __('Teams');?></h2>
<p>
<?php $myHtml->link('Home', '/'); ?>
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
	<th><?php echo $paginator->sort('description');?></th>
	<th><?php echo $paginator->sort('visibility');?></th>
	<th><?php echo $paginator->sort('type');?></th>
	<th><?php echo $paginator->sort('user_id');?></th>
	<th><?php echo $paginator->sort('picture_id');?></th>
	<th><?php echo $paginator->sort('default_location');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($teams as $team):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $team['Team']['id']; ?>
		</td>
		<td>
			<?php echo $team['Team']['created']; ?>
		</td>
		<td>
			<?php echo $team['Team']['modified']; ?>
		</td>
		<td>
			<?php echo $team['Team']['name']; ?>
		</td>
		<td>
			<?php echo $team['Team']['description']; ?>
		</td>
		<td>
			<?php echo $team['Team']['visibility']; ?>
		</td>
		<td>
			<?php echo $team['Team']['type']; ?>
		</td>
		<td>
			<?php echo $myHtml->link($team['User']['id'], array('controller'=> 'users', 'action'=>'view', $team['User']['id'])); ?>
		</td>
		<td>
			<?php echo $team['Team']['picture_id']; ?>
		</td>
		<td>
			<?php echo $team['Team']['default_location']; ?>
		</td>
		<td class="actions">
			<?php echo $myHtml->link(__('View', true), array('action'=>'view', $team['Team']['id'])); ?>
			<?php echo $myHtml->link(__('Edit', true), array('action'=>'edit', $team['Team']['id'])); ?>
			<?php echo $myHtml->link(__('Delete', true), array('action'=>'delete', $team['Team']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $team['Team']['id'])); ?>
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
		<li><?php echo $myHtml->link(__('New Team', true), array('action'=>'add')); ?></li>
		<li><?php echo $myHtml->link(__('List Users', true), array('controller'=> 'users', 'action'=>'index')); ?> </li>
		<li><?php echo $myHtml->link(__('New User', true), array('controller'=> 'users', 'action'=>'add')); ?> </li>
		<li><?php echo $myHtml->link(__('List Default Emails', true), array('controller'=> 'default_emails', 'action'=>'index')); ?> </li>
		<li><?php echo $myHtml->link(__('New Default Email', true), array('controller'=> 'default_emails', 'action'=>'add')); ?> </li>
		<li><?php echo $myHtml->link(__('List Events', true), array('controller'=> 'events', 'action'=>'index')); ?> </li>
		<li><?php echo $myHtml->link(__('New Event', true), array('controller'=> 'events', 'action'=>'add')); ?> </li>
		<li><?php echo $myHtml->link(__('List Players', true), array('controller'=> 'players', 'action'=>'index')); ?> </li>
		<li><?php echo $myHtml->link(__('New Player', true), array('controller'=> 'players', 'action'=>'add')); ?> </li>
		<li><?php echo $myHtml->link(__('List Topics', true), array('controller'=> 'topics', 'action'=>'index')); ?> </li>
		<li><?php echo $myHtml->link(__('New Topic', true), array('controller'=> 'topics', 'action'=>'add')); ?> </li>
	</ul>
</div>
