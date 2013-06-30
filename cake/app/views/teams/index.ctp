<?php // views/teams/index.ctp

?>

<?php
$model = 'Team';
if (!empty($teams))
{
  $data = $teams;
  echo $this->element('modelIndex', compact('model', 'data'));
}
?>

<?php if (empty($teams)): ?>
<h1>Create a Team</h1>
<p>
You don't currenly belong to or manage any teams.  Use the form below to create a team.
</p>
<?php
$action = 'add';
$this->set(compact('model', 'action'));
echo $this->element('team/teamForm'); ?>
<?php endif; ?>
