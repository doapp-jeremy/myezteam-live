<?php // views/elements/message/responseMessage.ctp
//$response : (required) :

?>

<?php
if (!isset($response['Response']))
{
  $response = array('Response' => $response);
}
foreach (array('Player', 'ResponseType', 'Event') as $model)
{
  if (isset($response[$model]))
  {
    $response['Response'][$model] = $response[$model];
  }
}
if (isset($response['User']))
{
  $response['Response']['Player']['User'] = $response['User'];
}
if (isset($response['Team']))
{
  $response['Response']['Event']['Team'] = $response['Team'];
}
?>

<?php
$title = '';
$title .= '<span style="color: #996600;" title="' . $response['Response']['Player']['User']['email'] . '">';
$title .= $response['Response']['Player']['User']['nameOrEmail'];
$title .= '</span>';
$rsvp = $response['Response']['ResponseType']['name'];
$title .= ' RSVP\'d ';
$title .= '<span style="color: #' . $response['Response']['ResponseType']['color'] . '">' . Inflector::humanize($rsvp) . '</span>';
$title .= ' to ';
$title .= '<span class="eventMessageTitle">';
$event = $response['Response']['Event'];
$title .= $html->link($event['name'], 'http://' . $hostname . '/events/view/' . $event['id'], array('style' => 'color: #990000;'));
$title .= '</span>';
$title .= ' for ';
$title .= '<span class="teamMessageTitle">';
$team = $response['Response']['Event']['Team'];
$title .= $html->link($team['name'], 'http://' . $hostname . 'teams/view/' . $team['id'], array('style' => 'color: #006633;'));
$title .= '</span>';
$title .= '<span style="margin-left: 10px; font-size: 75%; color: #333333;">' . $time->niceShort($response['Response']['created']) . '</span>';
if (isset($response['Response']['comment']) && (strlen($response['Response']['comment']) > 0))
{
  $title .= '<div style="margin-left: 20px; font-size: 90%;">';
  $title .= $response['Response']['comment'];
  $title .= '</div>';
}

echo $title;
?>
