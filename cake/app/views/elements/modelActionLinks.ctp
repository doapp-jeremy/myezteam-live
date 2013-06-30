<?php // views/elements/modelActionLinks.ctp : renders model action links
//$model : (required) model to render actions for
//$modelId : (optional -- if not set, $data[$model]['id'] must be) id of model to render actions for
//$data : (optional -- if $data[$model]['id'] is not set, $modelId must be) : the model data
//$actions : (optional) : the actions to render -- if not set, will check $data[$model]['actions'] and then request /$model/actions/$modelId
//$selectedAction : (optional) the currently selected action
//$ajaxActions : (optional) : actions to render ajax-ically
//$actionsClass : (optional) : the class for the actions, default is 'small actions path'
?>

<?php if (isset($modelId) || isset($data)): ?>
<?php
$modelId = $myHtml->modelId($model, $modelId, $data);

if (!isset($actions))
{
  if (isset($data) && isset($data[$model]['actions']))
  {
    $actions = $data[$model]['actions'];
  }
  else
  {
    $actions = $this->requestAction('/' . Inflector::pluralize(low($model)) . '/actions/' . $modelId);
  }
}
?>
<?php endif; ?>

<?php
if (!isset($data))
{
  $data = null;
}
if (!isset($modelId))
{
	$modelId = null;
}
if (isset($actions['class']))
{
  $actionsClass = $actions['class'];
  unset($actions['class']);
}
else if (!isset($actionsClass))
{
  $actionsClass = 'small actions path';
}
if (!isset($selectedAction))
{
  //$selectedAction = $myHtml->defaultAction($model, $modelId);
  $selectedAction = $actions['default'];
}
if (isset($actions['default']))
{
  unset($actions['default']);
}

if (!isset($params))
{
  $params = null;
}

$divId = $myHtml->actionsDivId($model, $modelId, $data);
?>

<div id="<?php echo $divId; ?>" class="<?php echo $actionsClass; ?>">
  <ul>
  <?php foreach ($actions as $action): ?>
  <?php
  $class = '';
  if ($selectedAction == $action)
  {
    $class = 'currentAction';
  }

  $actionId = $myHtml->actionId($model, $action, $modelId, $data);
  ?>
	 <li id="<?php echo $actionId; ?>" class="<?php echo $class; ?>">
	    <?php
	    $ajax = (!isset($ajaxActions) || (array_search($action, $ajaxActions) !== false));
	    $actionDivId = $myHtml->actionDivId($model, $action, $modelId, $data);
	    echo $myAjax->link($model, $action, $modelId, $selectedAction, $data, $ajax, $params);
	    ?>
    </li>
  <?php endforeach; ?>
  </ul>
  <ul>
  <?php if (true): // have to figure out how to hide the refresh links?>
  <?php foreach ($actions as $action): ?>
  <?php
    $style = 'style="display:none;"';
    //$class = 'hidden';
    $class = 'refresh';
    if ($selectedAction == $action)
    {
      $class = 'refresh';
      $style = '';
    }
   $refreshId = $myHtml->refreshId($model, $action, $modelId, $data);
   ?>
  <?php if ($myAjax->showRefresh($model, $action)): ?>
    <li id="<?php echo $refreshId; ?>" <?php echo $style; ?> class="<?php echo $class; ?>">
  <?php echo $myAjax->refresh($model, $action, $modelId, $data, $params); ?>
    </li>
  <?php endif; ?>
  <?php // don't show refresh for all actions, however, we need an item with the id for the others to work? ?>
  <?php if (!$myAjax->showRefresh($model, $action)): ?>
    <li id="<?php echo $refreshId; ?>" style="display: none"></li>
  <?php endif; ?>
  <?php endforeach; ?>
  </ul>
  <?php endif; ?>
</div>