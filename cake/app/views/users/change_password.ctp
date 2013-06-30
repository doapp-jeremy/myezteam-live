<?php // views/users/forgot_password.ctp : forgot password form

?>

<?php

$ajaxOptions = array(
  'model' => 'User',
  'url' => array('controller' => 'users', 'action' => 'change_password_validator'),
  'update' => 'updater',
  'loading' => 'document.getElementById("updater").innerHTML="Changing password...";',
);

?>

<div id="updater"></div><? //This is the div tag where the results of the validation go.?> 

<?php echo $ajax->form($params = array('action' => 'change_password_validator'), $type = 'post', $ajaxOptions); ?>
  <fieldset class="fieldset">
    <legend>Change Password</legend>
    <div>
      <p>Confirm your email address and enter a new password.</p>
    </div>
    <?php echo $form->input('User.email'); ?>
    <?php echo $form->input('User.password'); ?>
    <?php echo $form->input('User.confirm', array('type' => 'password')); ?>
    <?php echo $form->input('User.password_change_key', array('type' => 'hidden')); ?>
  </fieldset>
  
<?php echo $form->end('Change Password'); ?>
