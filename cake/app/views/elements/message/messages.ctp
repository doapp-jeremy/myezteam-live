<?php  // views/elements/message/messages.ctp
//$messags : (required) :
?>

<?php foreach ($messages as $message): ?>
  <?php echo $this->element('message/message', compact('message'));?>
<?php endforeach; ?>
