<?php // views/elements/modelMain.ctp : renders the main area for a model
//$model : (required) : the model to render the main area for

?>

<?php
$mL = low($model);
$mPL = Inflector::pluralize($mL);
$mP = Inflector::pluralize($model);

$divId = $myHtml->mainDivId($model);
?>

<div id="<?php echo $divId; ?>" class="modelMain">
  <?php // create a div for each model data ?>
  <?php $class = ''; ?>
  <?php foreach ($data as ${$mL}): ?>
  <div id="<?php echo $mL . ${$mL}[$model]['id'] . $mP; ?>Holder" class="<?php echo $class; ?>">
    <?php
    if ($class == '')
    {
      echo $this->element('modelView', array('model' => $model, 'data' => ${$mL}));
    }
    ?>
    <?php $class = 'hidden'; ?>
  </div>
  <?php endforeach; ?>
</div>
