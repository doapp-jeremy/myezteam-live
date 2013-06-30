<?php // views/elements/teamPlayers.ctp : renders responses for an event
//$event : (optional - if not set, $eventId must be) : the event to render responses for
//$eventId : (optional - if not set, $event['Event']['id'] must be) : the event id to render responses for
?>

<?php
if (!isset($event) || !isset($event['Player']))
{
  if (!isset($eventId))
  {
    $eventId = $event['Team']['id'];
  }
  $event = $this->requestAction('/events/responses/' . $eventId);
}
else if (!isset($eventId))
{
  $eventId = $event['Event']['id'];
}
$players = $event['Player'];

$url = array('controller' => 'events', 'action' => 'responses', $eventId);
$elementName = 'players';
$elementOptions = compact('players');
echo $this->element('paginatorView', compact('url', 'elementName', 'elementOptions'));
?>