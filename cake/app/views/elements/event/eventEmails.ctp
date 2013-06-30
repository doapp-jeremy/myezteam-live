<?php // views/elements/event/eventEmails.ctp : renders emails for an event
//$event : (optional - if not set, $eventId must be) : the event to render emails for
//$eventId : (optional - if not set, $event['Event']['id'] must be) : the event id to render emails for
//$emails : (optional) : 
//$past : (optional) : if true, will get past events
?>

<?php
if (!isset($eventId))
{
  if (isset($event))
  {
    $eventId = $event['Event']['id'];
  }
  else if (isset($emails['Event']))
  {
    $eventId = $emails['Event']['id'];
  }
}
if (!isset($emails))
{
  $emails = $this->requestAction('/events/emails/' . $eventId);
}

$title = null;
if (!isset($isAjax) || ($isAjax === false))
{
  $title = $emails['Event']['name'] . ' Emails';
}

$model = 'Email';
$data = $emails;
$params = array('event_id' => $eventId);
$divId = 'event' . $eventId . 'EmailsTab';
$parent = array('model' => 'Event', 'id' => $eventId);
echo $this->element('modelView', compact('model', 'data', 'divId', 'params', 'parent'));
?>
