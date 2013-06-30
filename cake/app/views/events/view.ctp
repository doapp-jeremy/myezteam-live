<?php // views/events/view.ctp
//$team
?>

<?php
$model = 'Event';
$data = $event;
echo $this->element('modelView', compact('model', 'data'));
?>
