<?php // views/elements/user/addEmailForm.ctp

?>

<?php
$ajaxOptions = array(
  'model' => 'User',
  'url' => array('controller' => 'users', 'action' => 'add_email_validator'),
  'update' => 'updater',
  'loading' => 'document.getElementById("updater").innerHTML="Adding email...";',
);
?>


<?php echo $ajax->form($params = array('action' => 'add_email_validator'), $type = 'post', $ajaxOptions); ?>
  <fieldset>
    <legend>Add Email</legend>
    <? //This is the div tag where the results of the validation go.?> 
    <div id="emails">
      <?php echo $this->element('user/emails', array('user' => $this->data)); ?>
      <div id="updater"></div>
    </div>
    <?php
    echo $form->input('UserEmail.user_id', array('type' => 'hidden', 'value' => $this->data['User']['id']));
    echo $form->input('UserEmail.email');
    ?>
  </fieldset>
<?php echo $form->end('Add Email'); ?>