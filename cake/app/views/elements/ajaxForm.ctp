<?php // views/elements/ajaxForm.ctp : renders an ajax form
//$model : (required) : 
//$action : (required) : add or edit
//$association : (optional) : the associated data
?>

<?php
$m = low($model);
$p = Inflector::pluralize($model);
$controller = low($p);
?>

<?php if (($action == 'add') && isset($assocation) && empty(${$assocation['name']})): ?>
<div class="mar10 bigger mar20Top">
You don't currently manage any <?php echo $assocation['name']; ?>, <?php echo $html->link('click here to create one', array('controller' => $assocation['name'], 'action' => 'add')); ?>
</div>
<?php endif; ?>

<?php if (($action == 'edit') || !isset($assocation) || !empty(${$association['name']})): ?>
<?php
$formId = $model . 'Form' . rand();
if (isset($this->data[$model]['id']))
{
  $formId = $model . $this->data[$model]['id'] . 'Form'; 
}
if (!isset($submit))
{
  $submit = 'Save ' . $model;
  if ($action == 'add')
  {
    $submit = 'Create ' . $model;
  }
}

$endDiv = false;
if (!isset($updater))
{
  $updater = 'updater';
  echo '<div id="updater"></div>'; //This is the div tag where the results of the validation go.
}
else
{ 
  //$endDiv = true;
  //echo '<div id="' . $updater . '">';
}
  

$ajaxOptions = array(
  'model' => $model,
  'url' => array('controller' => $controller, 'action' => 'validator'),
  'update' => $updater,
  'loading' => 'document.getElementById("submitButton").disabled = true; document.getElementById("updater").innerHTML="Saving ' . $m . '...";',
);

if (!isset($legend))
{
  $legend = Inflector::humanize($action) . ' ' . $model;;
}
?>


<div id="<?php echo $formId; ?>Div" class="<?php echo $controller; ?> form">
  <?php //echo $ajax->form($params = array('action' => 'validator'), $type = 'post', $options = array('url' => 'validator', 'update' => 'updater')); ?>
  <?php echo $ajax->form($params = array('action' => 'validator', 'id' => $formId), $type = 'post', $ajaxOptions); ?>
  <fieldset class="<?php echo $m; ?>Fieldset">
    <legend>
    <?php 
    echo $legend;
    ?>
    </legend>
  <?php
    echo $form->input('isAjax', array('type' => 'hidden', 'value' => $ajax->isAjax()));
    if (($action == 'edit') || isset($this->data[$model]['id']))
    {
      echo $form->input('id');
    }
    //if (isset(${$association['name']}) && !empty(${$association['name']}))
    if (isset($associations))
    {
      foreach ($associations as $assoc)
      {
        $field = $assoc['field'] . '_id';
        $label = Inflector::humanize($field);
        if (isset($assoc['label']))
        {
          $label = $assoc['label'];
        }
        echo $form->input($field, array('label' => $label));
      }
    }
    else if (isset($association))
    {
      if (isset($association['label']))
      {
        $label = $association['label']; 
      }
      else
      {
        $label = Inflector::humanize($association['name']);
      }
      echo $form->input(Inflector::singularize($association['name']) . '_id', array('label' => $label));
    }
    
    if (isset($formFields))
    {
      echo $formFields;
    }
    else if (isset($formFieldElement))
    {
      echo $this->element($formFieldElement);
    }
    else
    {
      echo $this->element($m . '/' . $m . 'FormFields');
    }
  ?>
  </fieldset>
  <?php
    //echo $form->submit($submit, array('id' => 'submitButton', 'onclick' => 'this.disabled = true;'));
    echo $form->submit($submit, array('id' => 'submitButton'));
    echo $form->end();
  ?>

</div>
<?php endif; ?>

<?php if ($endDiv): ?>
</div>
<?php endif; ?>