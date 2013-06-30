<?php // views/elements/email/emailList.ctp : renders a list of emails
//$emails : (required) : the list of emails to render
?>

<?php
if (!empty($emails['Email']))
{
  $url = array('controller' => 'emails', 'action' => 'index', $emails['Event']['id']);
  $elementName = 'emails';
  $elementOptions = array('emails' => $emails['Email']);
  echo $this->element('paginatorView', compact('url', 'elementName', 'elementOptions'));
}
else
{
  echo '<div class="mar10 mar20Top">There are no emails</div>';
}
?>
