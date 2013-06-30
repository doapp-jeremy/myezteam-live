<?php // views/elements/player/playerList.ctp : renders a list of players

?>

<?php
if (!empty($players['Player']))
{
  $url = array('controller' => 'players', 'action' => 'index', $players['Team']['id']);
  $elementName = 'players';
  $elementOptions = array('players' => $players['Player']);
  echo $this->element('paginatorView', compact('url', 'elementName', 'elementOptions'));
}
else
{
  echo 'There are no players';
}
?>
