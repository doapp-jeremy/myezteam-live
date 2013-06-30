<?php // views/elements/message/message.ctp
//$message : (required) : 

?>

<?php
if (!isset($message['Message']))
{
  $message = array('Message' => $message);
}
$messageId = $message['Message']['id'];
$elementPath = 'message/';
?>


<div class="mar10Top mar10 bigger" id="message<?php echo $messageId; ?>">
  <?php
  foreach (array('event', 'response', 'topic', 'post', 'new_user', 'error') as $field)
  {
    //$model = Inflector::humanize($field);
    $model = Inflector::camelize($field);
    if (isset($message['Message'][$field . '_id']) && $message['Message'][$field . '_id'] && isset($message[$model]))
    {
      echo $this->element($elementPath . $field . 'Message', array($field => $message));
    }
  }
  ?>
</div>
