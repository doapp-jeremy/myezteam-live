<?php // /views/elements/event/eventResponsePieChart.ctp : renders a pie chart for event responses
//$responses : (optional -- if not set, $eventId or $event must be) : array of responses
//$eventId : (optional -- if not set, $responses or $event must be) : id of event to render responses for
//$event : (optional -- if not set, $responses or $eventId must be) : event to render responses for
?>
<?php
if (!isset($responses))
{
  if (!isset($eventId))
  {
    $eventId = $event['Event']['id'];
  }
  $data = $this->requestAction('events/RSVPS/' . $eventId);
  $responses = $data['responses'];
  $this->set('responseTypes', $data['responseTypes']);
  $this->set('default', $data['default']);
}
echo $this->element('responsePieChart', compact('responses'));
?>