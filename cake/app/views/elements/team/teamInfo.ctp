<?php // views/elements/teamInfo.ctp : renders team info
// $team

?>

<?php
$fields = array('type', 'default_location', 'calendar_id', 'description', 'managers');
$team['Team']['default_location'] = nl2br($team['Team']['default_location']);
$team['Team']['description'] = nl2br($team['Team']['description']);
$model = 'Team';
$data = $team;
$managers[] = $html->link($team['Creator']['nameOrEmail'], array('controller' => 'users', 'action' => 'view', $team['Creator']['id']));
foreach ($team['Managers'] as $manager)
{
  if ($team['Creator']['id'] != $manager['id'])
  {
    $userLink = $html->link($manager['nameOrEmail'], array('controller' => 'users', 'action' => 'view', $manager['id']));
    array_push($managers, $userLink);
  }
}
$data['Team']['managers'] = implode('<br>', $managers);
$showEmpty = false;
?>

<?php echo $this->element('infos', compact('fields', 'model', 'data', 'showEmpty')); ?>

<?php if (false): ?>
<?php foreach (array('type', 'default_location', 'calendar_id', 'description') as $field): ?>
  <?php if (isset($team['Team'][$field])): ?>
    <div class="mar10">
      <div class="header"><?php echo Inflector::humanize($field); ?></div>
      <div class="value"><?php echo $team['Team'][$field]; ?></div>
    </div>
  <?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
