<?php // views/elements/teamForm.ctp : renders a team form

?>


<?php
$calId = 'calId';
$enableCalId = 'document.getElementById("' . $calId . '").disabled=this.checked;';
echo $form->input('name');
echo $form->input('type');
echo $form->input('default_location', array('type' => 'textarea', 'rows' => 2));
echo $form->input('description', array('type' => 'textarea', 'rows' => 3));
//echo $form->input('create_calendar', array('label' => 'Create Google Calendar (you must have a google account)', 'type' => 'checkbox', 'onclick' => $enableCalId));
//echo $form->input('calendar_id', array('label' => 'Calendar ID', 'id' => $calId, 'disabled' => $this->data['Team']['create_calendar']));
//echo $form->input('visibility');
//echo $form->input('picture_id');
if (isset($isAdmin) && ($isAdmin === true))
{
  echo $form->input('user_id', array('label' => 'Owner'));
}
else
{
  echo $form->input('user_id', array('type' => 'hidden'));
}
if (!empty($managers))
{
  echo $form->input('Managers');
}
?>
