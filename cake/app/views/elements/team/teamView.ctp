<?php // views/elements/teamView.ctp : renders a team view
//$team : the team to render the view for
?>

<?php
$model = 'Team';
$data = $team;
echo $this->element('modelView', compact('model', 'data'));
?>
