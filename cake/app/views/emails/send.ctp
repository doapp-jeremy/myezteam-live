<?php // views/emails/send.ctp

?>

<?php
$emailId = $email['Email']['id'];

$link1 = 'sendEmail' . $emailId . 'link1';
$link2 = 'sendEmail' . $emailId . 'link2';
$loadingId = 'sendingEmail' . $emailId;
$updateId = 'sendEmail' . $emailId . 'Results';
$options = array(
  'title' => 'Send Email',
  'update' => $updateId,
  'loading' => 'document.getElementById("' . $loadingId . '").innerHTML="Sending email...."; document.getElementById("' . $link1 . '").disabed=true; document.getElementById("' . $link2 . '").disabed=true;',
  'complete' => 'document.getElementById("' . $loadingId . '").innerHTML=""; document.getElementById("' . $link1 . '").disabed=false; document.getElementById("' . $link2 . '").disabed=false;'
);
?>

<div id="<?php echo $loadingId; ?>"></div>
<div id="<?php echo $updateId ?>"></div>
<div class="mar20Top"></div>

<div>
  <span id="<?php echo $link1; ?>" class="biggest emailData" style="background-color: #FFFFFF; margin-left: 200px;">
    <?php echo $ajax->link('Click to Send Email', array('controller' => 'emails', 'action' => 'really_send', $emailId), $options); ?>
  </span>
</div>

<?php echo $this->element('email/email'); ?>

<div>
  <span id="<?php echo $link2; ?>" class="biggest emailData" style="background-color: #FFFFFF; margin-left: 200px;">
    <?php echo $ajax->link('Click to Send Email', array('controller' => 'emails', 'action' => 'really_send', $emailId), $options); ?>
  </span>
</div>
