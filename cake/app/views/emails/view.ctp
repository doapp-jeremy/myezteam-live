<?php // views/emails/view.ctp
//$email : (required) : 
?>

<?php
$model = 'Email';
$data = $email;
echo $this->element('modelView', compact('model', 'data'));
?>
