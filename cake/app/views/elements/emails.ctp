<?php // views/elements/emails.ctp : renders a list of emails
//$emails : (required) : array of emails to render

?>

<div class="bigger mar10 mar20Top">
  <?php if (empty($emails)): ?>
    There are no emails.
  <?php endif; ?>
    
  <?php if (!empty($emails)): ?>
    <?php foreach ($emails as $email): ?>
      <?php echo $this->element('email', compact('email')); ?>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
