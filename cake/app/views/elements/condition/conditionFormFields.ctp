<?php // views/elements/condition/conditionFormFields.ctp

?>

<fieldset>
  <legend>Select Player Types for the Condition</legend>
  <?php echo $form->input('PlayerTypes');?>
</fieldset>

<fieldset>
  <legend>Select Response Types for the Condition</legend>
  <?php echo $form->input('ResponseTypes'); ?>
</fieldset>

<?php
echo $form->input('email_id', array('type' => 'hidden'));
echo $form->input('condition_type_id');
echo $form->input('number_of_players');
?>