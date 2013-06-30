<?php // views/elements/message/new_userMessage.ctp
//$new_user : (required) :

?>

<?php
if (!isset($new_user['NewUser']))
{
  $new_user = array('NewUser' => $new_user);
}
$activated = (isset($new_user['NewUser']['password']) && (strlen($new_user['NewUser']['password']) > 0));

?>

A new user has been added: <span style="color: #996600;"><?php echo $new_user['NewUser']['nameAndEmail']; ?></span>
<span style="margin-left: 5px; font-size: 75%; color: #333333;"><?php echo $new_user['NewUser']['created']; ?></span>
<?php if ($activated): ?>
  <span style="margin-left: 5px;">Activated</span>
<?php endif; ?>