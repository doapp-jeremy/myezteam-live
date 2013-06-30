<?php // views/emails/make_default.ctp
//$team : (required) :
//$defaultEmails : (required) :
//$email : (required) :
//$message : (required) :
?>

<div class="mar20Top">
<?php
if ($success)
{
  echo '<h2>Email ' . $email['Email']['title'] . ' is now a default email for team ' . $team['Team']['name'] . '</h2>';
}
else
{
  echo '<h2>Could not make email ' . $email['Email']['title'] . ' a default email for team ' . $team['Team']['name'] . '</h2>';
  if (isset($message))
  {
    echo '<div class="mar20Top">' . $message . '</div>';
  }
}
?>

  <?php echo $this->element('email/defaultEmails'); ?>
</div>