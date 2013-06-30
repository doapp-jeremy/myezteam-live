<?php // views/elements/email/upcomingEvents.ctp
//$events : (required) : 
//$showEmptyMsg : (optional) : 
?>

<?php if (!empty($events)): ?>
<fieldset style="border: 1px solid #ccc; margin-top: 30px; padding: 16px 20px; background-color: #FFCCCC;">
  <legend style="color: #FF0033; font-size: 160%; font-weight: normal; margin-left: 20px;">Upcoming Events</legend>
  <?php foreach ($events as $event): ?>
    <fieldset style="margin-top: 0px; margin-bottom: 20px; padding: 16px 0; background-color: #FFFFFF;">
      <legend style="color: #990000; font-size: 120%; font-weight: normal; margin-left: 20px;"><?php echo $event['Event']['name']; ?></legend>
        <?php
        $response = $event;
        $player['Player'] = $event['Player'];
        $player['PlayerType'] = $event['PlayerType'];
        echo $this->element('email/eventInfo', compact('event', 'response', 'response_types', 'player'));
        ?>
    </fieldset>
  <?php endforeach; ?>
</fieldset>
<?php endif; ?>
<?php if (empty($events) && isset($showEmptyMsg) && $showEmptyMsg): ?>

<?php endif; ?>