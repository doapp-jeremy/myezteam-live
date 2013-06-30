<?php // views/elements/email.ctp : renders an email
//$email : (required) : the email to render
?>

<?php
if (!isset($email['Email']))
{
  $email['Email'] = $email;
}
$divId = 'email' . $email['Email']['id'];
?>

<div id="<?php echo $divId ?>" class="email mar10 mar10Top smaller">
  <?php
  $model = 'Email';
  $data = $email;

  //$ownerActions = $this->requestAction('event/ownerActions/' . $email['Email']['event_id']);
  $edit = true;
  $delete = true;
  //$title = $myHtml->title($model, $data, null, $edit, $delete);
  
  //$title .= '<span class="smallest mar10">' . $time->startEnd($event['Event']['start'], $event['Event']['end']) . '</span>';
  echo $this->element('modelView', compact('model', 'data', 'edit', 'delete'));
  ?>
</div>