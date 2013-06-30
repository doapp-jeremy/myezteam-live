<?php // views/responses/rsvp.ctp
//$event : (required) :
?>

<?php
if (isset($isLoggedIn) && $isLoggedIn)
{
  echo $this->element('event/eventInfo');
}
else
{
  echo '<div class="mar10 mar20Top">';
  echo $html->link('Click here to login to view your teams and event information', array('controller' => 'users', 'action' => 'login'));
  echo '</div>';
}
?>