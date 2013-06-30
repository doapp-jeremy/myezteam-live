<?php // views/events/add_default_emails.ctp
//$team : (required) : the team the default emails belong to
//$defaultEmails : (optional -- if not set, $team['DefaultEmails'] must be) : array of default emails

?>
<?php echo $this->renderElement('email/defaultEmails'); ?>
