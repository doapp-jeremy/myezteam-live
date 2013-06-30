<?php // views/responses/add.ctp

?>

<?php
$legend = 'RSVP';
if (isset($userName))
{
  $legend .= ' for ' . $userName;
}
$submit = 'RSVP';
echo $this->element('ajaxForm', compact('legend', 'submit'));
?>