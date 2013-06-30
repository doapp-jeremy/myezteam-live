<?php // views/messages/daily_digest.ctp
//$user : (required) :
//$emails : (required) : array of user emails
//$messages : (required) :
?>

<?php
echo $this->element('email/messages');
echo $this->element('email/upcomingEvents');
?>

