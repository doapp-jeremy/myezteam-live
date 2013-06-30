<div class="players index">
<h2><?php __('Players');?></h2>
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
	<th><?php echo $paginator->sort('type');?></th>
	<th><?php echo $paginator->sort('team_id');?></th>
	<th><?php echo $paginator->sort('contact_id');?></th>
	<th><?php echo $paginator->sort('user_id');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($players as $player):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $player['Player']['id']; ?>
		</td>
		<td>
			<?php echo $player['Player']['created']; ?>
		</td>
		<td>
			<?php echo $player['Player']['modified']; ?>
		</td>
		<td>
			<?php echo $player['Player']['type']; ?>
		</td>
		<td>
			<?php echo $html->link($player['Team']['name'], array('controller'=> 'teams', 'action'=>'view', $player['Team']['id'])); ?>
		</td>
		<td>
			<?php echo $player['Player']['contact_id']; ?>
		</td>
		<td>
			<?php echo $html->link($player['User']['id'], array('controller'=> 'users', 'action'=>'view', $player['User']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $player['Player']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $player['Player']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $player['Player']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $player['Player']['id'])); ?>
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
		<li><?php echo $html->link(__('New Player', true), array('action'=>'add')); ?></li>
		<li><?php echo $html->link(__('List Teams', true), array('controller'=> 'teams', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Team', true), array('controller'=> 'teams', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Users', true), array('controller'=> 'users', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New User', true), array('controller'=> 'users', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Records', true), array('controller'=> 'records', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Record', true), array('controller'=> 'records', 'action'=>'add')); ?> </li>
	</ul>
</div>
