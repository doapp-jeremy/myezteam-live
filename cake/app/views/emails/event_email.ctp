<?php // views/elements/email/event_email.ctp

?>

<div>
  <h1><a style="font: bold 1em Georgia,sans-serif; color: #567; text-decoration: none;" href="http://<?php echo $hostname; ?>">My EZ Team</a></h1>
  <div style="font: normal 1em Verdana,sans-serif; color: #999;">Manage your teams with ease</div>
</div> <!-- end title and description -->

<?php if (!isset($user['User']['password']) || (strlen($user['User']['password']) <= 0)): ?>
<fieldset style="border: 1px solid #E0E5F0 margin-top: 30px; padding: 16px 20px; background-color: #F6F8FA">
  <legend style="color: #364E6D; font-size: 160%; font-weight: bold;">Account Info</legend>
  <h2>Your account has not been activated yet.</h2>
  <div>
  <?php echo $html->link('Click here to activate your account', 'http://' . $hostname . '/users/activate/' . $user['User']['email']); ?>
  <br>
  You don't have to activate your account to RSVP. But once you have activated your account, you can login and view your teams' message board and upcoming events. 
  </div>
  <br>
  <div style="background-color: #FFFFFF; padding: 5px; border: 1px solid; text-align: left;">
    <div style="font-weight: bold;">Name</div>
    <div style="margin-left: 20px;"><?php echo $user['User']['nameOrEmail']; ?></div>
    <div style="font-weight: bold;">Login Email</div>
    <div style="margin-left: 20px;"><?php echo $user['User']['email']; ?></div>
    <div style="font-weight: bold;">All Emails</div>
    <div style="margin-left: 20px;"><?php echo implode('<br>', $emails); ?></div>
  </div>
</fieldset>
<?php endif; ?>


<fieldset style="border: 1px solid #ccc; margin-top: 30px; padding: 16px 20px; background-color: #FFFFCC;">
  <?php if (isset($email['Email']['content']) && $email['Email']['content']): ?>
  <fieldset style="margin-top: 0px; margin-bottom: 20px; padding: 16px 0;">
    <legend style="color: #FF9900; font-size: 120%; font-weight: normal; margin-left: 20px;">Content</legend>
    <div style="clear: left; margin: 0 10px; background-color: #FFFFFF; padding: 5px; border: 1px solid; text-align: left;">
      <?php echo nl2br($email['Email']['content']); ?>
    </div>
  </fieldset>
  <?php endif; ?>
  
<?php if (isset($email['Email']['rsvp']) && $email['Email']['rsvp']): ?>
<!-- <fieldset style="margin-top: 0px; margin-bottom: 20px; padding: 16px 0;"> -->
  <legend style="color: #FF9900; font-size: 160%; font-weight: bold;">RSVP</legend>
    <fieldset style="margin-top: 0px; margin-bottom: 20px; padding: 16px 0;">
      <legend style="color: #FF9900; font-size: 120%; font-weight: normal; margin-left: 20px;">Click on a link to RSVP</legend>
      <div style="clear: left; margin: 0 10px;">
      <?php
      $keys = array_keys($response_types);
      $i = 0;
      foreach ($response_types as $response_type)
      {
        $typeId = $keys[$i++];
        $name = $response_type;
        $link = 'http://' . $hostname . '/responses/email_rsvp/' . $email['Event']['id'] . '/' . $player['Player']['id'] . '/' . $typeId . '/' . $player['Player']['response_key'];
        echo '<span style="margin-left: 20px">';
        echo $html->link(Inflector::humanize($name), $link, array('title' => $name));
        echo '</span>';
      }
      ?>
      </div>
    </fieldset>
  <?php if (!$isIBMEmail || !$onlyIBMEmail): ?>
    <h2>Or</h2>
    <fieldset style="margin-top: 0px; margin-bottom: 20px; padding: 16px 0;">
      <legend style="color: #FF9900; font-size: 120%; font-weight: normal; margin-left: 20px;">Select your RSVP</legend>
      <?php if ($isIBMEmail && !$onlyIBMEmail): ?>
      <div style="clear: left; margin: 0 10px;">
        We have detected that 1 of your email address is an IBM account.  Please use the links above if your are responding from that email address because Lotus Notes doesn't like the form in the email.
      </div>
      <br>
      <?php endif; ?>
      <div style="margin-left: 30px">
      <?php
      //echo $form->create('Response', array('action' => 'rsvp', array('style' => 'clear: both; margin-right: 20px; padding: 0; width: 90%;')));
      echo $form->create('Response', array('url' => 'http://' . $hostname . '/responses/rsvp', array('style' => 'clear: both; margin-right: 20px; padding: 0; width: 90%;')));
      echo $form->input('event_id', array('type' => 'hidden'));
      echo $form->input('player_id', array('type' => 'hidden'));
      ?>
      <div style="font-weight: bold"><?php echo $form->label('RSVP'); ?></div>
      <div style="margin-left: 20px"><?php echo $form->select('response_type_id', $response_types, 'yes', array(), false); ?></div>
      <div style="font-weight: bold">Comment</div>
      <div style="margin-left: 20px"><?php echo $form->input('comment', array('label' => false, 'type' => 'textarea', 'rows' => 3, 'cols' => 50)); ?></div>
      <?php
      echo $form->end('RSVP');
      ?>
      </div>
    </fieldset>
  <?php endif; ?>
<?php endif; ?>
</fieldset>

<fieldset style="border: 1px solid #ccc; margin-top: 30px; padding: 16px 20px; background-color: #FFCCCC;">
  <!-- <fieldset style="margin-top: 0px; margin-bottom: 20px; padding: 16px 0;"> -->
    <legend style="color: #FF0033; font-size: 160%; font-weight: normal; margin-left: 20px;">Event Info: <?php echo $email['Event']['name']; ?></legend>
    <div style="clear: left; margin: 0 10px; background-color: #FFFFFF; padding: 5px; border: 1px solid; text-align: left;">
      <?php //echo $this->element('event/eventInfo', array('event' => $email)); ?>
      <?php echo $this->element('email/eventInfo', array('event' => $email)); ?>
    </div>
  <!-- </fieldset> -->
</fieldset>
This email was sent on <b><?php echo date('l, F j, Y g:i:s a'); ?></b>
