<?php // views/elements/modelView.ctp : renders a view for a model
//$model : (required) : the model to render the view for
//$data : (optional) : the data for the model - if not set, will get secondary actions
//$parent : (optional) : if set, gets the parent actions
?>

<?php
$this->set('isAjax', true);

$m = Inflector::pluralize(low($model));

$actions = array();
$ajaxActions = array();
?>
<?php if (isset($data)): ?>
  <?php
  $modelId = $myHtml->modelId($model, null, $data);
	if (isset($data[$model]) && isset($data[$model]['actions']))
	{
	  $actions = $data[$model]['actions'];
	}
	else
	{
	  if (isset($parent))
	  {
	    $actions = $this->requestAction('/' . Inflector::pluralize(low($parent['model'])) . '/' .$m .'_actions/' . $parent['id']);
	  }
	  else
	  {
	   $actions = $this->requestAction('/' . $m . '/actions/' . $modelId);
	  }
	}
	foreach ($actions as $action)
	{
	  $field = Inflector::singularize($action);
	  if (!isset($data[$field]))
	  {
	    array_push($ajaxActions, $action);
	  }
	}
	$divId = $myHtml->viewDivId($model, $modelId, $data);
	?>
<div id="<?php echo $divId; ?>" class="<?php echo $m; ?> view">
  <div class="title <?php echo $m; ?>Title">
    <?php
    if (isset($title))
    {
      echo $title;
    }
    else
    {
      $ownerActions = $this->requestAction($m . '/ownerActions/' . $modelId);
      $edit = $ownerActions['edit'];
      $delete = $ownerActions['delete'];
      
      echo $myHtml->title($model, $data, $divId, $edit, $delete);
    }
    ?>
  </div>
  <?php if (sizeof($actions) > 1): ?>
    <?php echo $this->element('modelActionLinks', compact('model', 'modelId', 'data', 'actions', 'selectedAction', 'ajaxActions', 'params')); ?>
  <?php endif; ?>
  <?php unset($actions['class']); ?>
  <?php echo $this->element('modelDivs', compact('model', 'modelId', 'actions', 'data', 'selectedAction', 'edit', 'params')); ?>
</div>
<?php endif; ?>
