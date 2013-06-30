<?php // views/elements/event/eventDrop.ctp : renders an event drop down element
//$event : (required) :  the event to render the drop down for
//$useAjax : (required) : whether the title should be ajax or not
?>

<?php
$model = 'Event';
$modelId = $event['Event']['id'];
$data = $event;
$extraTitle = '<div>';
$extraTitle .= '<span class="date smallest mar5">' . $time->startEnd($event['Event']['start'], $event['Event']['end']) . '</span>';
$extraTitle .= '</div>';
$extraTitle .= '<div class="mar5 small" style="color: #003300">';
$extraTitle .= $event['Team']['name'];
$extraTitle .= '</div>';
echo $this->element('modelDrop', compact('model', 'modelId', 'data', 'useAjax', 'extraTitle'));
?>
