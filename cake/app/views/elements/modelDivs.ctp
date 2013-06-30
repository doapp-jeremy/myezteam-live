<?php // views/elements/modelDivs.ctp : renders divs for a model
//$model : (required) : the model to render divs for
//$modelId : (optional - if not set, $data[$model][$id] or $divId must be) : the id of the model
//$data : (optional - if not set, $modelId or $divId must be) : the data for the model
//$divId : (optional - if not set, $data or $modelId must be) : the base div id
//$selectedAction : (optional) : the action to show when loaded, if not set, will get the default action
?>

<?php
$m = low($model);

if (isset($data) || isset($modelId))
{
  $tmpId = $m;
  if (!isset($data))
  {
    $data[$model] = array();
  }
  if (!isset($modelId))
  {
    $modelId = $myHtml->modelId($model, $modelId, $data);
  }
  
  if (!isset($actions) && isset($data[$model]['actions']))
  {
    $actions = $data[$model]['actions'];
  }
  else if (!isset($actions))
  {
    $actions = $this->requestAction('/' . Inflector::pluralize($m) . '/actions/' . $modelId);
  }
}
else 
{
  if (!isset($actions))
  {
    $actions = $this->requestAction('/' . Inflector::pluralize($m) . '/actions/');
  }
}
if (!isset($action) && isset($actions['default']))
{
  $selectedAction = $actions['default'];
}

if (isset($actions['default']))
{
  unset($actions['default']);
}

if (isset($modelId) && $modelId)
{
  $tmpId .= $modelId;
}

if (!isset($divId))
{
  $divId = $tmpId;
}
$divId = $myHtml->tabsDivId($model, $modelId, $data);
?>
<div id="<?php echo $divId; ?>" class="mar20Top">
<?php $i = 0; ?>
<?php foreach ($actions as $action): ?>
  <?php $selected = (isset($selectedAction) && ($selectedAction == $action)); ?>
  <?php echo $this->element('modelDiv', compact('model', 'modelId', 'action', 'data', 'selected', 'params')); ?>
<?php endforeach; ?>
<?php if (isset($edit) && ($edit === true)): ?>
  <?php $action = 'Edit'; ?>
  <?php echo $this->element('modelDiv', compact('model', 'modelId', 'action', 'data')); ?>
<?php endif; ?>
</div>
