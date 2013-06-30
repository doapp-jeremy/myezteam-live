<?php // views/emails/really_send.ctp
//$goodUsers : (optional) : 
//$badUsers : (optional) : 
?>

<?php if (isset($goodUsers) && !empty($goodUsers)): ?>
<div class="flashGood">
  <h2>Email successfully sent to:</h2>
  <ul>
	<?php foreach ($goodUsers as $name => $emails): ?>
    <li><?php echo $name; ?>: (<?php echo implode(', ', $emails); ?>)</li>
  <?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>
<?php if (isset($badUsers) && !empty($badUsers)): ?>
<br>
<div class="flashBad">
  <h2>Email failed trying to send to:</h2>
  <ul>
  <?php foreach ($badUsers as $name => $emails): ?>
    <li><?php echo $name; ?>: (<?php echo implode(', ', $emails); ?>)</li>
  <?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>

<?php if ((!isset($goodUsers) || empty($goodUsers)) && (!isset($badUsers) || empty($badUsers))): ?>
<div class="flashBad">
  <h2>There were no players that matched the criteria of the email.</h2>
  <h2>The email was not sent</h2>
</div>
<?php endif; ?>
