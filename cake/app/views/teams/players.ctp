<?php // views/teams/players.ctp
//$team: the team to render players for
?>

<?php //echo $this->element('player/playerList'); ?>
<?php echo $this->element('team/teamPlayers', compact('team')); ?>
