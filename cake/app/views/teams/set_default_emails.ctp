<?php // views/teams/set_default_emails.ctp
//$eventNames : (required) : 

?>
<?php if (empty($eventNames)): ?>
<div>
There are no upcoming events.
</div>
<?php endif; ?>

<?php if (!empty($eventNames)): ?>
<div class="flashGood">
  <h2>Default emails set for the following events</h2>
  <div class="mar10">
  <ul>
    <?php foreach ($eventNames as $eventName): ?>
      <li><?php echo $eventName; ?></li>
    <?php endforeach; ?>
  </ul>
  </div>
</div>
<?php endif; ?>
