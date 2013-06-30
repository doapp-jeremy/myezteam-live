<div class="messages index">
<h2><?php __('Messages');?></h2>
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
	<th><?php echo $paginator->sort('msg');?></th>
	<th><?php echo $paginator->sort('title');?></th>
	<th><?php echo $paginator->sort('link');?></th>
	<th><?php echo $paginator->sort('response_id');?></th>
	<th><?php echo $paginator->sort('event_id');?></th>
	<th><?php echo $paginator->sort('user_id');?></th>
	<th><?php echo $paginator->sort('del');?></th>
	<th><?php echo $paginator->sort('save');?></th>
	<th><?php echo $paginator->sort('topic_id');?></th>
	<th><?php echo $paginator->sort('post_id');?></th>
	<th><?php echo $paginator->sort('error_id');?></th>
	<th><?php echo $paginator->sort('new_user_id');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($messages as $message):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $message['Message']['id']; ?>
		</td>
		<td>
			<?php echo $message['Message']['created']; ?>
		</td>
		<td>
			<?php echo $message['Message']['modified']; ?>
		</td>
		<td>
			<?php echo $message['Message']['msg']; ?>
		</td>
		<td>
			<?php echo $message['Message']['title']; ?>
		</td>
		<td>
			<?php echo $message['Message']['link']; ?>
		</td>
		<td>
			<?php echo $html->link($message['Response']['id'], array('controller'=> 'responses', 'action'=>'view', $message['Response']['id'])); ?>
		</td>
		<td>
			<?php echo $html->link($message['Event']['name'], array('controller'=> 'events', 'action'=>'view', $message['Event']['id'])); ?>
		</td>
		<td>
			<?php echo $html->link($message['User']['id'], array('controller'=> 'users', 'action'=>'view', $message['User']['id'])); ?>
		</td>
		<td>
			<?php echo $message['Message']['del']; ?>
		</td>
		<td>
			<?php echo $message['Message']['save']; ?>
		</td>
		<td>
			<?php echo $html->link($message['Topic']['title'], array('controller'=> 'topics', 'action'=>'view', $message['Topic']['id'])); ?>
		</td>
		<td>
			<?php echo $html->link($message['Post']['id'], array('controller'=> 'posts', 'action'=>'view', $message['Post']['id'])); ?>
		</td>
		<td>
			<?php echo $html->link($message['Error']['id'], array('controller'=> 'errors', 'action'=>'view', $message['Error']['id'])); ?>
		</td>
		<td>
			<?php echo $html->link($message['NewUser']['id'], array('controller'=> 'users', 'action'=>'view', $message['NewUser']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $message['Message']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $message['Message']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $message['Message']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $message['Message']['id'])); ?>
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
		<li><?php echo $html->link(__('New Message', true), array('action'=>'add')); ?></li>
		<li><?php echo $html->link(__('List Responses', true), array('controller'=> 'responses', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Response', true), array('controller'=> 'responses', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Events', true), array('controller'=> 'events', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Event', true), array('controller'=> 'events', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Users', true), array('controller'=> 'users', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New User', true), array('controller'=> 'users', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Topics', true), array('controller'=> 'topics', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Topic', true), array('controller'=> 'topics', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Posts', true), array('controller'=> 'posts', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Post', true), array('controller'=> 'posts', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Errors', true), array('controller'=> 'errors', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Error', true), array('controller'=> 'errors', 'action'=>'add')); ?> </li>
	</ul>
</div>
