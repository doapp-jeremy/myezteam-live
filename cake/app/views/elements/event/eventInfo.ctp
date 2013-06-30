<?php // views/elements/eventInfo.ctp : renders event info
//$event : (required) : the event to render info for
?>

<?php
$fields = array('team', 'name', 'when', 'location', 'description', 'default_response', 'your_RSVP');
if ($isAdmin)
{
  array_push($fields, 'cal_event_id');
}
$event['Event']['team'] = $html->link($event['Team']['name'], array('controller' => 'teams', 'action' => 'view', $event['Event']['team_id']), array('title' => $event['Team']['name']));
if (!isset($response))
{
  $response = $this->requestAction('/events/rsvp_status/' . $event['Event']['id']);
}

if (empty($response))
{
  $rsvp = '<span class="response_' . $event['ResponseType']['name'] . '">';
  $rsvp .= Inflector::humanize($event['ResponseType']['name']);
  $rsvp .= ' (Default)';
  $rsvp .= '</span>';
  $rsvp .= '<span class="mar10">';
  $rsvp .= $html->link('RSVP', array('controller' => 'responses', 'action' => 'add', $event['Event']['id']));
  $rsvp .= '</span>';
}
else
{
  $rsvp = '<span class="response_' . $response['ResponseType']['name'] . '">';
  $rsvp .= Inflector::humanize($response['ResponseType']['name']);
  $rsvp .= '</span>';
  $rsvp .= '<span class="date smaller mar10">' . $time->niceShort($response['Response']['created']) . '</span>';
  $rsvp .= '<span class="mar10">';
  $rsvp .= $html->link('Change RSVP', array('controller' => 'responses', 'action' => 'add', $event['Event']['id']));
  $rsvp .= '</span>';
  $rsvp .= '<br>';
  if (isset($response['Response']['comment']) && (strlen($response['Response']['comment']) > 0))
  {
    $rsvp .= '<div class="mar10">' . nl2br($response['Response']['comment']) . '</div>';
  }
}


$event['Event']['your_RSVP'] = $rsvp;

$event['Event']['when'] = $time->startEnd($event['Event']['start'], $event['Event']['end']);
$event['Event']['location'] = nl2br($event['Event']['location']);
$event['Event']['description'] = nl2br($event['Event']['description']);
$event['Event']['default_response'] = Inflector::humanize($event['ResponseType']['name']);
$model = 'Event';
$data = $event;
$showEmpty = false;
?>

<?php foreach($fields as $field): ?>
  <?php if ((isset($data[$model][$field]) && (strlen($data[$model][$field]) > 0)) || (isset($showEmpty) && $showEmpty)): ?>
  <div class="mar10">
    <div class="header"><?php echo Inflector::humanize($field); ?></div>
    <div class="value">
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
  <div class="mar10">
    <div class="header">RSVPs</div>
    <div><?php echo $this->element('event/eventResponsePieChart', compact('event')); ?></div>
  </div>
