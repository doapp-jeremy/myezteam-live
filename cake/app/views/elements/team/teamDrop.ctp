<?php // views/elements/teamDrop.ctp : renders a team drop down element
//$team : (required) :  the team to render
//$useAjax : (required) : whether the title should be ajax or not
?>

<?php
$model = 'Team';
$modelId = $team['Team']['id'];
$data = $team;
if (isset($team['Event']) && $team['Event']['name'])
{
  $extraTitle = '<div class="mar5 " style="color: #003300; font-size: 16px">';
  $extraTitle .= $team['Event']['name'];
  $extraTitle .= '<div>';
  $extraTitle .= '<span class="date smallest">' . $time->startEnd($team['Event']['start'], $team['Event']['end']) . '</span>';
  $extraTitle .= '</div>';
  $extraTitle .= '</div>';
}
else
{
  $extraTitle = '<div class="mar5">';
  $extraTitle .= $html->link('Add Event', array('controller' => 'events', 'action' => 'add', $team['Team']['id']), array('style' => 'color: #993300; font-size: 14px;'));
  $extraTitle .= '</div>';
}
echo $this->element('modelDrop', compact('model', 'modelId', 'data', 'useAjax', 'extraTitle'));
?>
