<?php // views/elements/message/eventMessage.ctp
//$event : (required) :

?>

<?php
if (!isset($event['Event']))
{
  $event = array('Event' => $event);
}
?>

<?php
$title = '<span class="teamMessageTitle">';
$title .= $html->link($event['Event']['Team']['name'], array('controller' => 'teams', 'action' => 'view', $event['Event']['team_id']));
$title .= ':</span>';
$title .= '<span class="eventMessageTitle mar10">A new event has been added: ';
$title .= $html->link($event['Event']['name'], array('controller' => 'events', 'action' => 'view', $event['Event']['id']));
$title .= '</span>';
$title .= '<span class="mar10 date smallest">' . $time->startEnd($event['Event']['start'], $event['Event']['end']) . '</span>';

$style = array('width' => '800px');

//$data = nl2br($event['Event']['description']);
$event['Event']['Event'] = $event['Event'];
//$data = $this->element('event/eventInfo', array('event' => $event['Event']));
$url = array('controller' => 'events', 'action' => 'info', $event['Event']['id']);
$options = compact('title', 'url', 'style');

echo $this->element('dropDown', compact('options'));
?>
