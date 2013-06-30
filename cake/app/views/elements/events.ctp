<?php // views/elements/events.ctp : renders events
//$events: array of events to render
?>
<div class="data">
  <?php foreach($events as $event): ?>
    <?php echo $this->element('event', compact('event')); ?>
  <?php endforeach; ?>
</div>
