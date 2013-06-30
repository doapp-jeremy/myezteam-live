<?php // views/elements/secondarActions.ctp : renders secondary actions
//$actions : the actions to render

?>

<?php
if (!isset($baseId))
{
  $baseId = rand();
}

$baseId .= 'SecondaryActions';

if (!isset($class))
{
  $class = 'secondaryActions';
}
?>

<div id="<?php echo $baseId; ?>" class="<?php echo $class; ?>">
  <?php foreach ($actions as $action): ?>
    <div id="<?php echo $baseId; ?>$action
  <?php endforeach; ?>
</div>