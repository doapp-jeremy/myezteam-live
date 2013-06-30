<div class="errors index">
<h2><?php __('Errors');?></h2>
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
	<th><?php echo $paginator->sort('function');?></th>
	<th><?php echo $paginator->sort('message');?></th>
	<th><?php echo $paginator->sort('user_Id');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($errors as $error):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $error['Error']['id']; ?>
		</td>
		<td>
			<?php echo $error['Error']['created']; ?>
		</td>
		<td>
			<?php echo $error['Error']['modified']; ?>
		</td>
		<td>
			<?php echo $error['Error']['function']; ?>
		</td>
		<td>
			<?php echo $error['Error']['message']; ?>
		</td>
		<td>
			<?php echo $error['Error']['user_Id']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $error['Error']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $error['Error']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $error['Error']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $error['Error']['id'])); ?>
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
		<li><?php echo $html->link(__('New Error', true), array('action'=>'add')); ?></li>
	</ul>
</div>
