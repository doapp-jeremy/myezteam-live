<?php // views/teams/view.ctp
//$team
?>

<?php
$model = 'Team';
$data = $team;
echo $this->element('modelView', compact('model', 'data'));
?>
