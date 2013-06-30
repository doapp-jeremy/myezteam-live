<?php // views/elements/message/responseMessage.ctp
//$response : (required) :

?>

<?php
if (!isset($response['Response']))
{
  $response = array('Response' => $response);
}
?>

<?php
$title = '';
$title .= '<span class="userMessageTitle" title="' . $response['Response']['Player']['User']['email'] . '">';
$title .= $response['Response']['Player']['User']['nameOrEmail'];
$title .= '</span>';
$rsvp = $response['Response']['ResponseType']['name'];
$title .= ' RSVP\'d ';
$title .= '<span class="response_' . $rsvp . '">' . Inflector::humanize($rsvp) . '</span>';
$title .= ' to ';
$title .= '<span class="eventMessageTitle">';
$event = $response['Response']['Event'];
$title .= $html->link($event['name'], array('controller' => 'events', 'action' => 'view', $event['id']));
$title .= '</span>';
$title .= ' for ';
$title .= '<span class="teamMessageTitle">';
$team = $response['Response']['Event']['Team'];
$title .= $html->link($team['name'], array('controller' => 'teams', 'action' => 'view', $team['id']));
$title .= '</span>';
$title .= '<span class="mar10 date smallest">' . $time->niceShort($response['Response']['created']) . '</span>';

$style = array('width' => '800px');

//$data = nl2br($event['Event']['description']);
//$event['Event']['Event'] = $response['Event'];
//$data = $this->element('event/eventInfo', array('event' => $event['Event']));
$url = array('controller' => 'events', 'action' => 'info', $response['Response']['Event']['id']);

//$links = array(
//  $html->link('RSVP', array('controller' => 'events', 'action' => 'RSVP', $response['Event']['id'])),
//);

//if (!empty($links))
//{
//  $data .= '<div class="mar20Top smaller">';
//  $data .= implode(' | ', $links);
//  $data .= '</div>';
//}

//$options = compact('title', 'data', 'style');
$options = compact('title', 'url', 'style');
echo $this->element('dropDown', compact('options'));
?>
