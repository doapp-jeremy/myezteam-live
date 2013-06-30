<?php // views/users/forgot_password.ctp : forgot password form

?>

<?php

$ajaxOptions = array(
  'model' => 'User',
  'url' => array('controller' => 'users', 'action' => 'forgot_password_validator'),
  'update' => 'updater',
  'loading' => 'document.getElementById("updater").innerHTML="Sending email...";',
);

$legend = 'Reset Password';
if (isset($this->data['User']) && (!isset($this->data['User']['password']) || (strlen($this->data['User']['password']) <= 0)))
{
  $legend = 'Activate Account';
}
?>

<div id="updater"></div><? //This is the div tag where the results of the validation go.?> 

<?php echo $ajax->form($params = array('action' => 'forgot_password_validator'), $type = 'post', $ajaxOptions); ?>
  <fieldset class="fieldset">
    <legend><?php echo $legend; ?></legend>
    <div>
      <ul>
        <li>Enter your email address and click the button, an email will be sent to you</li>
        <li>Click on the link in the email and then you will be able to set a new password</li>
      </ul>
    </div>
    <?php echo $form->input('User.email'); ?>
  </fieldset>
  
<?php echo $form->end('Send Email'); ?>
