<?php // views/elements/teamEvents.ctp : renders events for a team
//$team : (optional - if not set, $teamId must be) : the team to render topics for
//$teamId : (optional - if not set, $team['Team']['id'] must be) : the team id to render topics for
//$events : (optional) : 
//$past : (optional) : if true, will get past events
?>

<?php if (true): ?>
<?php
  if (!isset($teamId))
  {
    if (isset($team))
    {
      $teamId = $team['Team']['id'];
    }
    else if (isset($events['Team']))
    {
      $teamId = $events['Team']['id'];
    }
  }
if (!isset($events['Event']))
{
  if (isset($team['Events']))
  {
    $events = $team['Events'];
  }
  else
  {
    $action = 'events';
    if (isset($past) && ($past === true))
    {
      $action = 'past_events';
    }
    $events = $this->requestAction('/teams/' . $action . '/' . $teamId);
  }
}
//else if (isset($team['Events']))
//{
//  $events = $team['Events'];
//}

  if (!isset($teamId))
  {
    if (isset($team))
    {
      $teamId = $team['Team']['name'];
    }
    else if (isset($events['Team']))
    {
      $teamId = $events['Team']['id'];
    }
  }

$title = $events['Team']['name'] . ' Events';
if (isset($isAjax) && ($isAjax === true))
{
  $title = null;
}

$model = 'Event';
$data = $events;
$params = array('team_id' => $teamId);
$parent = array('model' => 'Team', 'id' => $teamId);
$divId = 'team' . $teamId . 'EventsTab';
if (isset($past) && ($past === true))
{
  $divId .= 'Past';
}

echo $this->element('modelView', compact('model', 'data', 'divId', 'params', 'parent'));
?>
<?php endif; ?>
