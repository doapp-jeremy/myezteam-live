<?php // views/elements/teamPlayers.ctp : renders players for a team
//$team : (optional - if not set, $teamId must be) : the team to render players for
//$teamId : (optional - if not set, $team['Team']['id'] must be) : the team id to render players for
?>

<?php
if (!isset($teamId))
{
  if (isset($team))
  {
    $teamId = $team['Team']['id'];
  }
  else if (isset($players['Team']))
  {
    $teamId = $players['Team']['id'];
  }
}

if (!isset($players))
{
  $players = $this->requestAction('/teams/players/' . $teamId);
}

$title = null;
if (isset($isAjax) && ($isAjax === true))
{
  $title = $players['Team']['name'] . ' Players';
}

$model = 'Player';
$data = $players;
$params = array('team_id' => $teamId);
$divId = 'team' . $teamId . 'PlayersTab';
$parent = array('model' => 'Team', 'id' => $teamId);
echo $this->element('modelView', compact('model', 'data', 'divId', 'params', 'parent'));
?>
