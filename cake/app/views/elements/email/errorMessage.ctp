<?php // views/elements/message/errorMessage.ctp
//$error : (required) :

?>

<?php
if (!isset($error['Error']))
{
  $new_user = array('Error' => $error);
}
?>

An error has occurred: <span style="color: red"><?php echo $error['Error']['function'] ?></span>
<div style="margin-top: 10px; margin-left: 10px">
  <?php echo $error['Error']['message']; ?>
</div>
