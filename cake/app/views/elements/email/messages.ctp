<?php // views/elements/email/messages.ctp
//$messages : (required) :
?>

<fieldset style="border: 1px solid #ccc; margin-top: 30px; padding: 16px 20px; background-color: #FFFFCC;">
  <legend style="color: #FF9900; font-size: 120%; font-weight: normal; margin-left: 20px;">What's Happened</legend>
  <div style="clear: left; margin: 0 10px; background-color: #FFFFFF; padding: 5px; border: 1px solid; text-align: left;">
  <?php if (!empty($messages)): ?>
    <?php foreach ($messages as $message): ?>
      <?php echo $this->element('email/message', compact('message'));?>
    <?php endforeach; ?>
  <?php endif; ?>
  <?php if (empty($messages)): ?>
    Nothing new has happened
  <?php endif; ?>
  </div>
</fieldset>
