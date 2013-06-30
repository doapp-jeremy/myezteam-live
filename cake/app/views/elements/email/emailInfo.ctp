<?php // views/elements/email/emailInfo.ctp : renders email info
//$email : (required) : the email to render info for
?>

<?php
$email['Email']['send'] = Inflector::humanize($email['Email']['send']);
if ($email['Email']['send'] == 'Days Before')
{
  $email['Email']['send'] .= ': ' . $email['Email']['days_before'];
}
else if ($email['Email']['send'] == 'Send On')
{
  $email['Email']['send'] .= ': ' . $time->niceShort($email['Email']['send_on']);
}

$fields = array('title', 'include_rsvp_form', 'content', 'send', 'sent', 'player_types', 'response_types', 'conditions');
if ($email['Email']['rsvp'])
{
  $email['Email']['include_rsvp_form'] = 'Yes';
}
else
{
  $email['Email']['include_rsvp_form'] = 'No';
}
$email['Email']['content'] = nl2br($email['Email']['content']);
$player_types = array();
$response_types = array();
foreach (array('PlayerTypes', 'ResponseTypes') as $model)
{
  $field = low(Inflector::underscore($model));
  foreach ($email[$model] as $type)
  {
    ${$field}[] = Inflector::humanize($type['name']);
  }
  $email['Email'][$field] = implode('<br>', ${$field});
}
$updateId = 'Email' . $email['Email']['id'] . 'conditions';
$loadingId = 'Email' . $email['Email']['id'] . 'loader';
$conditions = $email['Condition'];
//$email['Email']['conditions'] = $this->element('conditions', compact('conditions', 'updateId', 'loadingId'));

$model = 'Email';
$data = $email;
$showEmpty = false;



if (isset($data['Email']['sent']))
{
  $data['Email']['sent'] = $time->niceShort($data['Email']['sent']);
}

//if (!$ajax->isAjax() && !isset($email['Event']))
//if ($email['Email']['event_id'])
//{
//  $event = $this->requestAction('/events/info/' . $email['Email']['event_id']);
//  //$email['Event'] = $event['Event']
//  $email['Email']['event_link'] = $html->link($event['Event']['name'], array('controllers' => 'events', 'action' => 'view', $email['Email']['event_id']), array('title' => $email['Event']['name']));
//}
?>

<div class="data">
  <?php if ($email['Email']['event_id']): ?>
  <?php
  if (isset($email['Event']))
  {
    $event = $email;
  }
  else
  {
    $event = $this->requestAction('/events/info/' . $email['Email']['event_id']);
  }
  ?>
  <div class="mar10">
    <div class="header">Event</div>
    <div class="value"><?php echo $html->link($event['Event']['name'], array('controller' => 'events', 'action' => 'view', $email['Email']['event_id']), array('title' => $event['Event']['name'])); ?></div>
  </div>
  <?php endif; ?>
  <?php if ($email['Email']['team_id']): ?>
  <?php
  if (isset($email['Team']))
  {
    $team = $email;
  }
  else
  {
    $team = $this->requestAction('/teams/info/' . $email['Email']['team_id']);
  }
  ?>
  <div class="mar10">
    <div class="header">Team</div>
    <div class="value"><?php echo $html->link($team['Team']['name'], array('controller' => 'teams', 'action' => 'view', $team['Team']['id']), array('title' => $team['Team']['name'])); ?></div>
  </div>
  <?php endif; ?>
  <?php foreach($fields as $field): ?>
  <?php if ((isset($data[$model][$field]) && (strlen($data[$model][$field]) > 0))|| (isset($showEmpty) && $showEmpty)): ?>
  <div class="mar10">
    <div class="header"><?php echo Inflector::humanize($field); ?></div>
    <div class="value" id="<?php echo $model . $data[$model]['id'] . $field ?>">
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
    <?php if ($field == 'conditions'): ?>
    <div id="<?php echo $loadingId; ?>"></div>
    <?php endif; ?>
  </div>
  <?php endif; ?>
<?php endforeach; ?>
</div>

