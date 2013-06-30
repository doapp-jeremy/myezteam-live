<?php // views/elements/playerName.ctp : renders a player name
//$player : (required) : the player to render the name for
//$includeEmail : (optional) : if true, includes the user's email
?>

<?php
//$link = array('controller' => 'players', 'action' => 'view', $player['id']);
$link = false;
$oPlayer = $player;
if (isset($player['Player']))
{
  $player = $player['Player'];
}
if (!isset($player['name']))
{
	$noSpan = true;
	if (!isset($oPlayer['User']))
	{
		$userId = $player['user_id'];
		$userName = $this->element('userName', compact('userId', 'includeEmail', 'noSpan', 'link'));
	}
	else
	{
		$user = $oPlayer;
		$userName = $this->element('userName', compact('user', 'includeEmail', 'noSpan', 'link'));
  }
}
else
{
	$userName = $player['name'];
  $title = $player['name'];
	if (isset($includeEmail) && ($includeEmail === true))
	{
		//echo 'TODO: you want to include email, but player name set in afterFind';
	}
  $userName = $html->link($userName, $link, compact('title'));
}


echo $userName;
?>