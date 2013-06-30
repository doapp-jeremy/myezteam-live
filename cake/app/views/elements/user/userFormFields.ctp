<?php // views/elements/user/userFormFields.ctp : renders user form fields

?>

<?php
echo $form->input('email');
if (isset($isOwner) && $isOwner)
{
  echo $form->input('password');
  echo $form->input('confirm', array('type' => 'password'));
}
echo $form->input('first_name');
echo $form->input('last_name');
if ($action == 'edit')
{
  echo $this->element('user/accountSettingsForm');
}
else
{
  echo $form->input('password');
  echo $form->input('confirm', array('type' => 'password'));
}
?>
