<?php // views/elements/modelDiv.ctp : renders a div for a model action
//$model : (required) : the model to render the action div for
//$action : (required) : the action to render the div for
//$modelId : (optional - if not set, $data[$model]['id'] or $divId must be) : the id of the model
//$data : (optional - if not set, $modelId or $divId must be) : if set and $data[$model][$action] is set, will render the div
//$params: (optional) -- params for the refresh link
?>

<?php
if (!isset($params))
{
  $params = null;
}
$modelId = $myHtml->modelId($model, $modelId, $data);
?>

<?php
$holderDivId = $myHtml->actionHolderDivId($model, $action, $modelId, $data);
$actionDivId = $myHtml->actionDivId($model, $action, $modelId, $data);

$field = Inflector::singularize($action);
$selected = (isset($selected) && ($selected !== false)) || (isset($data) && isset($data[$field]));
?>

<div id="<?php echo $holderDivId; ?>" class="modelDiv <?php if (!$selected) echo 'hidden'; ?>">
  <!-- <div id="<?php echo $actionDivId; ?>"> -->
  <?php
  //if ((isset($selected) && $selected) || (isset($data) && isset($data[$field])))
  if ($selected)
  {
    echo $this->element('modelAction', compact('model', 'modelId', 'action', 'data'));
  }
  ?>
<!-- </div> -->
</div>
