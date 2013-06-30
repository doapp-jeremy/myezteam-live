<?php // views/elements/message/errorMessage.ctp
//$error : (required) :

?>

<?php
if (!isset($error['Error']))
{
  $new_user = array('Error' => $error);
}
?>

An error has occurred: <?php echo $error['Error']['function'] ?>
<div class="mar10 mar10Top">
  <?php echo $error['Error']['message']; ?>
</div>
