<?php // views/elements/modelDrop.ctp : renders a drop down element for a model
//$model : (required) : the model to render the drop down for
//$modelId : (optional - if not set, $data[$model]['id'] must be) : the id of the model
//$data : (optional - if not set, $modelId must be) : 
//$useAjax : (required) : if true, uses an ajax link
//$extraTitle : (optional) : 
?>

<?php
if (!$modelId)
{
  $modelId = $data[$model]['id'];
}
if (!isset($data))
{
  $data = null;
}

$action = Inflector::pluralize($model);
$title = $myAjax->link($model, $action, $modelId, null, $data, $useAjax);
if (isset($extraTitle))
{
  $title .= $extraTitle;
}
$m = low($model);

$id = $myHtml->baseDivId($model, $modelId, $data);

$options = array(
        'title' => $title,
        'id' => $id,
        'action' => $action,
        'style' => array('width' => '300px'));

if (isset($useUrl) && ($useUrl === true))
{
  array_merge($options, array('url' => array('controller' => Inflector::pluralize($m), 'action' => 'snapshot', $modelId)));
}

echo $this->element('dropDown', compact('options'));
?>
