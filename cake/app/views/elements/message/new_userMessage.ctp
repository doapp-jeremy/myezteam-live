<?php // views/elements/message/new_userMessage.ctp
//$new_user : (required) :

?>

<?php
if (!isset($new_user['NewUser']))
{
  $new_user = array('NewUser' => $new_user);
}
?>

A new user has been added: <span class="userMessageTitle"><?php echo $new_user['NewUser']['nameAndEmail']; ?></span>
