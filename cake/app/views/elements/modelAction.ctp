<?php // views/elements/modelAction.ctp : renders an action elemnt for a model
//$model : (required) : the model to render the action for
//$action : (required) : the action to render
//$modelId : (optional - if not set, $data[$model]['id'] must be) : the id of the model
//$data : (optional - if not set, $modelId must be) : if set and $data[$model][$action] is set, will render the div
?>

<?php
$modelId = $myHtml->modelId($model, $modelId, $data);

$m = low($model);
$mP = Inflector::pluralize($m);
${$m} = $data;
${$mP} = $data;
$mId = $m . '_id';
${$mId} = $modelId;
if ($model == $action)
{
  $action = 'List';
}
echo $this->element($m . '/' . $m . $action, compact($m, $mId, $mP));
?>
