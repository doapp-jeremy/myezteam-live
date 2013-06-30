<?php // views/elements/email/accountInfo.ctp
//$user : (required) :
//$emails : (required) :
?>

<?php $activated = (isset($user['User']['password']) && (strlen($user['User']['password']) > 0)); ?>

<fieldset style="border: 1px solid #E0E5F0 margin-top: 30px; padding: 16px 20px; background-color: #F6F8FA">
  <legend style="color: #364E6D; font-size: 160%; font-weight: bold;">Account Info</legend>
  <?php if (!$activated): ?>
  <h2>Your account has not been activated yet.</h2>
  <div>
  <?php echo $html->link('Click here to activate your account', 'http://' . $hostname . '/users/activate/' . $user['User']['email']); ?>
  <br>
  You don't have to activate your account to RSVP. But once you have activated your account, you can login and view your teams' message board and upcoming events. 
  </div>
  <br>
  <?php endif; ?>
  <div style="background-color: #FFFFFF; padding: 5px; border: 1px solid; text-align: left;">
    <div style="font-weight: bold;">Name</div>
    <div style="margin-left: 20px;"><?php echo $user['User']['nameOrEmail']; ?></div>
    <div style="font-weight: bold;">Login Email</div>
    <div style="margin-left: 20px;"><?php echo $user['User']['email']; ?></div>
    <?php if (!empty($emails)): ?>
    <div style="font-weight: bold;">All Emails</div>
    <div style="margin-left: 20px;"><?php echo implode('<br>', $emails); ?></div>
    <?php endif; ?>
    <?php if ($activated): ?>
    <div style="font-weight: bold;">Account Settings</div>
    <div style="margin-left: 20px;"><?php echo $html->link('Edit account settings', 'http://' . $hostname . '/users/edit/'); ?></div>
    <?php endif; ?>
  </div>
</fieldset>

