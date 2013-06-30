<?php // views/elements/email/email.ctp
// $email : (required) : the email
// $email['Event'] : (required) : the event the email belongs to
// $creator : (required) : the creator of the team the email belongs to
// $managers : (required) : the managers of the team the email belongs to
// $users : (required) : array of Player Types and users emails

?>

<?php
$emailId = $email['Email']['id'];
?>

<fieldset>
<div id="email<?php echo $emailId; ?>" class="email">
  <div id="email<?php echo $emailId; ?>Header" class="emailHeader">
      <table cellpadding="2px" cellspacing="10px">
        <tr>
          <td class="bold">TO:</td>
          <td class="mar10 emailData">
            <?php if (empty($users)): ?>
              There are no players that match the player types and RSVP status of this email.
            <?php endif; ?>
            <?php if (!empty($users)): ?>
            <table cellpadding="2px" cellspacing="10px">
              <?php foreach ($users as $playerType => $player): ?>
              <tr class="playerType_<?php echo $playerType; ?>">
                <td class="bigger"><?php echo Inflector::pluralize(Inflector::humanize($playerType)); ?>:</td>
                <td class="mar5" align="left">
                <?php $i = 0; ?>
                <?php foreach ($player as $playerId => $user): ?>
                  <span title="<?php echo implode(', ', $user['emails']); ?>"><?php echo $user['name']; ?><?php if ($i++ != (sizeof($player) - 1)) echo ', '; ?></span>
                <?php endforeach; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </table>
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <td class="bold">Player Types:</td>
          <td class="mar10 emailData">
            <?php
            $playerTypes = Set::extract($email['PlayerTypes'], '{n}.humanNamePlural');
            echo implode(', ', $playerTypes);
            ?>
          </td>
        </tr>
        <tr>
          <td class="bold">RSVPs:</td>
          <td class="mar10 emailData">
            <?php
            $responses = Set::extract($email['ResponseTypes'], '{n}.humanName');
            echo implode(', ', $responses);
            ?>
          </td>
        </tr>
        <tr>
          <td class="bold">FROM:</td>
          <td class="mar10 emailData">
            <span title="Creator"><?php echo $creator['Creator']['nameOrEmail']; if (!empty($managers)) echo ', '; ?></span>
            <?php if (!empty($managers)): ?>
              <?php $managerNames = Set::extract($managers, '{n}.Managers.nameOrEmail'); ?>
              <span title="Manager"><?php echo implode(',</span> <span title="Manager">', $managerNames); ?></span>
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <td class="bold">SUBJECT:</td>
          <td class="mar10 emailData">
            <span><?php echo $email['Event']['name']; ?></span>
            <span class="date mar10 smaller"><?php echo date('l, F j, Y', strtotime($email['Event']['start'])); ?></span>
            <span class="mar10"><?php echo $email['Email']['title']; ?>
          </td>
        </tr>
      </table>
  </div>
  <?php if (isset($email['Email']['content']) && $email['Email']['content']): ?>
  <fieldset>
    <legend>Content</legend>
    <div class="emailData">
      <?php echo nl2br($email['Email']['content']); ?>
    </div>
  </fieldset>
  <?php endif; ?>
  <fieldset>
    <legend>Event Info</legend>
    <div class="emailData">
      <?php echo $this->element('event/eventInfo', array('event' => $email)); ?>
    </div>
  </fieldset>
</div>
</fieldset>
