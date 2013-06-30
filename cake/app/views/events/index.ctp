<?php // views/events/index.ctp
//$events : (required) :
?>

<?php
$model = 'Event';
$data = $events;
echo $this->element('modelIndex', compact('model', 'data'));
?>
