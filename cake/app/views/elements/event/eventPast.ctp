<?php // views/elements/eventUpcoming.ctp : renders upcoming events
//$event : (required) : array of events

?>

<?php
if (!empty($events['Event']))
{
  $url = array('controller' => 'teams', 'action' => 'events_past', $team['Team']['id']);
  $elementName = 'events';
  $elementOptions = array('events' => $events['Event']);
  echo $this->element('paginatorView', compact('url', 'elementName', 'elementOptions'));
}
else
{
  echo '<div class="mar10 mar20Top">There are no past events</div>';
}
?>
