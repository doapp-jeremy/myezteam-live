<?php // views/elements/message/eventMessage.ctp
//$event : (required) :

?>

<?php
if (!isset($event['Event']))
{
  $event = array('Event' => $event);
}
if (isset($event['Team']))
{
  $event['Event']['Team'] = $event['Team'];
}
?>

<?php

$title = '<span style="color: #006633;">';
$title .= $html->link($event['Event']['Team']['name'], 'http://' . $hostname . '/teams/view/' . $event['Event']['team_id'], array('style' => 'color: #006633;'));
$title .= ':</span>';
$title .= '<span style="margin-left: 5px;">A new event has been added: ';
$title .= $html->link($event['Event']['name'], 'http://' . $hostname . '/events/view/' . $event['Event']['id'], array('style' => 'color: #990000;'));
$title .= '</span>';
$title .= '<span style="margin-left: 10px; font-size: 75%; color: #333333;">' . $time->startEnd($event['Event']['start'], $event['Event']['end']) . '</span>';

echo $title;
//$style = array('width' => '800px');
//
////$data = nl2br($event['Event']['description']);
//$event['Event']['Event'] = $event['Event'];
////$data = $this->element('event/eventInfo', array('event' => $event['Event']));
//$options = compact('title', 'style');
//
//echo $this->element('dropDown', compact('options'));
?>
