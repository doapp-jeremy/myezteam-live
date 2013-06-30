<div class="emails index">
<h2><?php __('Emails');?></h2>
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
	<th><?php echo $paginator->sort('title');?></th>
	<th><?php echo $paginator->sort('days_before');?></th>
	<th><?php echo $paginator->sort('content');?></th>
	<th><?php echo $paginator->sort('sent');?></th>
	<th><?php echo $paginator->sort('type_regular');?></th>
	<th><?php echo $paginator->sort('type_sub');?></th>
	<th><?php echo $paginator->sort('type_member');?></th>
	<th><?php echo $paginator->sort('attending_no_response');?></th>
	<th><?php echo $paginator->sort('attending_no');?></th>
	<th><?php echo $paginator->sort('attending_yes');?></th>
	<th><?php echo $paginator->sort('attending_maybe');?></th>
	<th><?php echo $paginator->sort('condition_count');?></th>
	<th><?php echo $paginator->sort('condition_type_regular');?></th>
	<th><?php echo $paginator->sort('condition_type_sub');?></th>
	<th><?php echo $paginator->sort('condition_type_member');?></th>
	<th><?php echo $paginator->sort('condition_type');?></th>
	<th><?php echo $paginator->sort('event_id');?></th>
	<th><?php echo $paginator->sort('rsvp');?></th>
	<th><?php echo $paginator->sort('send');?></th>
	<th><?php echo $paginator->sort('send_on');?></th>
	<th><?php echo $paginator->sort('manager');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($emails as $email):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $email['Email']['id']; ?>
		</td>
		<td>
			<?php echo $email['Email']['created']; ?>
		</td>
		<td>
			<?php echo $email['Email']['modified']; ?>
		</td>
		<td>
			<?php echo $email['Email']['title']; ?>
		</td>
		<td>
			<?php echo $email['Email']['days_before']; ?>
		</td>
		<td>
			<?php echo $email['Email']['content']; ?>
		</td>
		<td>
			<?php echo $email['Email']['sent']; ?>
		</td>
		<td>
			<?php echo $email['Email']['type_regular']; ?>
		</td>
		<td>
			<?php echo $email['Email']['type_sub']; ?>
		</td>
		<td>
			<?php echo $email['Email']['type_member']; ?>
		</td>
		<td>
			<?php echo $email['Email']['attending_no_response']; ?>
		</td>
		<td>
			<?php echo $email['Email']['attending_no']; ?>
		</td>
		<td>
			<?php echo $email['Email']['attending_yes']; ?>
		</td>
		<td>
			<?php echo $email['Email']['attending_maybe']; ?>
		</td>
		<td>
			<?php echo $email['Email']['condition_count']; ?>
		</td>
		<td>
			<?php echo $email['Email']['condition_type_regular']; ?>
		</td>
		<td>
			<?php echo $email['Email']['condition_type_sub']; ?>
		</td>
		<td>
			<?php echo $email['Email']['condition_type_member']; ?>
		</td>
		<td>
			<?php echo $email['Email']['condition_type']; ?>
		</td>
		<td>
			<?php echo $html->link($email['Event']['name'], array('controller'=> 'events', 'action'=>'view', $email['Event']['id'])); ?>
		</td>
		<td>
			<?php echo $email['Email']['rsvp']; ?>
		</td>
		<td>
			<?php echo $email['Email']['send']; ?>
		</td>
		<td>
			<?php echo $email['Email']['send_on']; ?>
		</td>
		<td>
			<?php echo $email['Email']['manager']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $email['Email']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $email['Email']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $email['Email']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $email['Email']['id'])); ?>
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
		<li><?php echo $html->link(__('New Email', true), array('action'=>'add')); ?></li>
		<li><?php echo $html->link(__('List Events', true), array('controller'=> 'events', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Event', true), array('controller'=> 'events', 'action'=>'add')); ?> </li>
	</ul>
</div>
