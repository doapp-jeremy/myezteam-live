<?php // views/topics/view.ctp
//$topic : (required) : 
?>

<?php
$model = 'Topic';
$data = $topic;
echo $this->element('modelView', compact('model', 'data'));
?>
