<?php // views/elements/eventUpcoming.ctp : renders upcoming events
//$event : (required) : array of events
//$team : (required) : the team the events belong to
?>
<?php
if (!isset($events))
{
  //$events = $this->requestAction('/teams/events_upcoming/' . $team['Team']['id']);
}

if (!empty($events['Event']))
{
  $url = array('controller' => 'teams', 'action' => 'events_upcoming', $events['Team']['id']);
  $elementName = 'events';
  $elementOptions = array('events' => $events['Event']);
  echo $this->element('paginatorView', compact('url', 'elementName', 'elementOptions'));
}
else
{
  //$addLink = $html->link('Add Event', array('controller' => 'events', 'action' => 'add', $events['Team']['id']));
  $addLink = $myAjax->link('Event', 'AddEvent', null, false, $events, true, array('team_id' => $events['Team']['id']));
  echo '<div class="mar10 mar20Top bigger">There are no upcoming events.</div>';
}
?>
