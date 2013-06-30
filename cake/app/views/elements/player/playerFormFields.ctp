<?php // views/elements/playerFormFields.ctp : renders form fields for a player

?>

<?php echo $form->input('team_id', array('type' => 'hidden')); ?>

<?php if (false && $action == 'add'): ?>
  <div>Select a player type, then choose an existing contact, or create a new one</div>
  <?php echo $form->select('Player.type', array('regular' => 'Regular', 'sub' => 'Sub', 'member' => 'Member'), null, array(), false); ?>
<?php endif; ?>

<?php if ($action == 'add'): ?>

<?php if (false): ?>
<fieldset>
  <legend>Choose From Your Contacts</legend>
  <?php echo $form->input('user_id', array('label' => false)); ?>
</fieldset>
<?php endif; ?>

<fieldset>
  <legend>Create a New Player</legend>
  <?php
    echo $form->input('User.first_name');
    echo $form->input('User.last_name');
    echo $form->input('User.email');
  ?>
</fieldset>
<?php endif; ?>
