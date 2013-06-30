<?php // views/elements/eventInfo.ctp : renders event info
//$event : (required) : the event to render info for
//$response : (required) : 
//$response_types : (required) :
//$player : (required) :
?>

<?php
$fields = array('team', 'name', 'when', 'location', 'description', 'default_response');
if ($player['Player']['id'] && ($player['PlayerType']['name'] != 'member'))
{
  array_push($fields, 'your_RSVP');
}
$event['Event']['name'] = $html->link($event['Event']['name'], 'http://' . $hostname . '/events/view/' . $event['Event']['id'], array('title' => $event['Event']['name']));
$event['Event']['team'] = $html->link($event['Team']['name'], 'http://' . $hostname . '/teams/view/' . $event['Event']['team_id'], array('title' => $event['Team']['name']));
if (!isset($response))
{
  $response = $this->requestAction('/events/rsvp_status/' . $event['Event']['id']);
}

if (!isset($event['DefaultResponse']))
{
  $event['DefaultResponse'] = $event['ResponseType'];
}

if (empty($response) || !$response['Response']['id'])
{
  $rsvp = '<span style="color: #' . $event['DefaultResponse']['color'] . '">';
  $rsvp .= Inflector::humanize($event['DefaultResponse']['name']);
  $rsvp .= ' (Default)';
  $rsvp .= '</span>';
}
else
{
  $rsvp = '<span style="color: #' . $response['ResponseType']['color'] . '">';
  $rsvp .= Inflector::humanize($response['ResponseType']['name']);
  $rsvp .= '</span>';
  $rsvp .= '<span style="color: #9999CC; font-size: 80%; margin-left: 10px;">' . $time->niceShort($response['Response']['created']) . '</span>';
  $rsvp .= '<br>';
  $rsvp .= '<div style="margin-left: 10px;">' . nl2br($response['Response']['comment']) . '</div>';
}
if ((isset($event['Email']) && $event['Email']['rsvp']))
{
  array_push($fields, 'change_RSVP');
  
  $changeRsvp = '';
  $keys = array_keys($response_types);
  $i = 0;
  foreach ($response_types as $response_type)
  {
    $typeId = $keys[$i++];
    $name = $response_type;
    $link = 'http://' . $hostname . '/responses/email_rsvp/' . $event['Event']['id'] . '/' . $player['Player']['id'] . '/' . $typeId . '/' . $player['Player']['response_key'];
    $changeRsvp .= $html->link(Inflector::humanize($name), $link, array('title' => $name));
    $changeRsvp .= '<br>';
  }
  $event['Event']['change_RSVP'] = $changeRsvp;
}
else if (!isset($event['Email']) && $player['Player']['id'] && (($player['PlayerType']['name'] == 'regular') || $response['Response']['id']))
{
  array_push($fields, 'change_RSVP');
  $event['Event']['change_RSVP'] = $html->link('Change RSVP', 'http://' . $hostname . '/responses/add/' . $event['Event']['id']);
}
if ($player['Player']['id'] && ($player['PlayerType']['name'] != 'member'))
{
  $event['Event']['your_RSVP'] = $rsvp;
}

$event['Event']['when'] = $time->startEnd($event['Event']['start'], $event['Event']['end']);
$event['Event']['location'] = nl2br($event['Event']['location']);
$event['Event']['description'] = nl2br($event['Event']['description']);
$event['Event']['default_response'] = Inflector::humanize($event['DefaultResponse']['name']);
$model = 'Event';
$data = $event;
$showEmpty = false;
?>

<?php foreach($fields as $field): ?>
  <?php if ((isset($data[$model][$field]) && (strlen($data[$model][$field]) > 0)) || (isset($showEmpty) && $showEmpty)): ?>
  <div style="margin-left: 10px">
    <div style="font-weight: bold;"><?php echo Inflector::humanize($field); ?></div>
    <div style="margin-left: 20px;">
      <?php
      if (isset($data[$model][$field]))
      {
        if (is_array($data[$model][$field]))
        {
          echo implode('<br>', $data[$model][$field]);
        }
        else
        {
          echo $data[$model][$field];
        }
      }
      ?>
    </div>
  </div>
  <?php endif; ?>
<?php endforeach; ?>
  <div style="margin-left: 10px">
    <div style="font-weight: bold;">RSVPs</div>
    <div style="margin: 10px;"><?php echo $this->element('event/eventResponsePieChart', compact('event')); ?></div>
  </div>
