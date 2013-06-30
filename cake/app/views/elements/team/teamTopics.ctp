<?php // views/elements/teamTopics.ctp : renders topics for a team
//$team : (optional - if not set, $teamId must be) : the team to render topics for
//$teamId : (optional - if not set, $team['Team']['id'] must be) : the team id to render topics for
?>

<?php
if (!isset($teamId))
{
  if (isset($team))
  {
    $teamId = $team['Team']['id'];
  }
  else if (isset($topics['Team']))
  {
    $teamId = $topics['Team']['id'];
  }
}
if (!isset($topics))
{
  $topics = $this->requestAction('/teams/topics/' . $teamId);
}

$title = null;
if (isset($isAjax) && ($isAjax === true))
{
  $title = $topics['Team']['name'] . ' Topics';
}

$model = 'Topic';
$data = $topics;
$params = array('team_id' => $teamId);
$divId = 'team' . $teamId . 'TopicsTab';
$parent = array('model' => 'Team', 'id' => $teamId);
echo $this->element('modelView', compact('model', 'data', 'divId', 'params', 'parent'));
?>
