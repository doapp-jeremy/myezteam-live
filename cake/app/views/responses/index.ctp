<div class="responses index">
<h2><?php __('Responses');?></h2>
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
	<th><?php echo $paginator->sort('attending');?></th>
	<th><?php echo $paginator->sort('comment');?></th>
	<th><?php echo $paginator->sort('record_id');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($responses as $response):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $response['Response']['id']; ?>
		</td>
		<td>
			<?php echo $response['Response']['created']; ?>
		</td>
		<td>
			<?php echo $response['Response']['modified']; ?>
		</td>
		<td>
			<?php echo $response['Response']['attending']; ?>
		</td>
		<td>
			<?php echo $response['Response']['comment']; ?>
		</td>
		<td>
			<?php echo $html->link($response['Record']['id'], array('controller'=> 'records', 'action'=>'view', $response['Record']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $response['Response']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $response['Response']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $response['Response']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $response['Response']['id'])); ?>
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
		<li><?php echo $html->link(__('New Response', true), array('action'=>'add')); ?></li>
		<li><?php echo $html->link(__('List Records', true), array('controller'=> 'records', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Record', true), array('controller'=> 'records', 'action'=>'add')); ?> </li>
	</ul>
</div>
