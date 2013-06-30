<?php // views/elements/login.ctp : renders a login box

?>

<?php
$ajaxOptions = array(
  'model' => 'User',
  'url' => array('controller' => 'users', 'action' => 'login_validator'),
  'update' => 'updater',
  'loading' => 'document.getElementById("updater").innerHTML="Verifying credentials...";',
);
?>


<!-- <div id="loginForm" class="mar10 border left login" style="width: 310px"> -->
<div class="login">
  <?php if (!$session->check('User')): ?>
    <?php echo $ajax->form($params = array('controller' => 'users', 'action' => 'login_validator'), $type = 'post', $ajaxOptions); ?>
      <fieldset>
        <legend>Login</legend>
        <?php
        echo $form->input('User.email');
        echo $form->input('User.password');
        ?>
        <?php echo $form->submit('Login'); ?>
        <div style="display: inline">
          <span><?php echo $html->link('Register', array('controller' => 'users', 'action' => 'add')); ?></span>
          <span class="mar10"><?php echo $html->link('Forgot Password', array('controller' => 'users', 'action' => 'forgot_password')); ?></span>
        </div>
      
      </fieldset>
    <?php echo $form->end(); ?>
  <div id="updater"></div>
  <?php endif; ?>
  <?php if ($session->check('User')): ?>
    <?php echo $this->element('quickLinks'); ?>
  <?php endif; ?>
</div>
