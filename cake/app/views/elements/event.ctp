<?php // views/elements/event.ctp : renders an event
//$event : (required) : the event to render
?>

<?php
if (!isset($event['Event']))
{
  $event = array('Event' => $event);
}
$divId = 'event' . $event['Event']['id'];
?>

<div id="<?php echo $divId; ?>" class="event mar10 mar10Top">
  <?php
  $model = 'Event';
  $data = $event;

  $ownerActions = $this->requestAction('events/ownerActions/' . $event['Event']['id']);
  $edit = $ownerActions['edit'];
  $delete = $ownerActions['delete'];
  $title = $myHtml->title($model, $data, null, $edit, $delete);
  
  $title .= '<span class="smallest mar10">' . $time->startEnd($event['Event']['start'], $event['Event']['end']) . '</span>';
  echo $this->element('modelView', compact('model', 'data', 'title', 'edit', 'delete'));
  ?>
</div>
