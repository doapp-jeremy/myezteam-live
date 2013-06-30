<?php // views/players/edit.ctp

?>

<?php
$legend = 'Edit Player';
if (isset($this->data['User']))
{
  $legend = $this->data['User']['nameOrEmail'];
}
echo $this->element('ajaxForm', compact('legend'));
?>
