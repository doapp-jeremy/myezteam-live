<?php // views/elements/player.ctp : renders a player
//$player : (required) : the player to render

?>

<?php
//$oPlayer = $player;
//if (isset($player['Player']))
//{
//	$player = $player['Player'];
//}
//$playerId = $player['id'];
$playerId = $player['Player']['id'];
?>
<div id="player<?php echo $playerId; ?>" class="player">
  <span id="player<?php echo $playerId; ?>Name" class="biggest playerType_<?php echo $player['PlayerType']['name']; ?>">
    <?php //echo $this->element('playerName', compact('player')); ?>
    <?php echo $html->link($player['User']['nameOrEmail'], array('controller' => 'users', 'action' => 'view', $player['Player']['user_id']), array('title' => $player['User']['email'])); ?>
  </span>
	<?php if (!isset($type)): ?>
	<span id="player<?php echo $playerId; ?>Type" class="bigger playerType_<?php echo $player['PlayerType']['name']; ?>"><?php echo $player['PlayerType']['name']; ?></span>
	<?php endif; ?>
	<?php if (isset($isTeamManager) && ($isTeamManager === true)): ?>
	<span class="edit"><?php echo $html->link('edit', array('controller' => 'players', 'action' => 'edit', $playerId)); ?></span>
   | 
  <span class="edit">
    <?php //echo $html->link('delete', array('controller' => 'players', 'action' => 'delete', $playerId)); ?>
    <?php echo $myHtml->delete('Player', $playerId, $player, 'Are you sure you want to remove ' . $player['User']['nameOrEmail'] . ' from the team?'); ?>
  </span>
    <?php if ($player['User']['last_login']): ?>
      <span class="mar5 small" style="color: #999999">Last Login:</span>
      <span class="mar5 small"><?php echo $time->niceShort($player['User']['last_login']); ?></span>
    <?php endif; ?>
	<?php endif; ?>
</div>
