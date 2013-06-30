<?php // views/elements/email/defaultEmails
//$team : (required) : the team the default emails belong to
//$defaultEmails : (optional -- if not set, $team['DefaultEmails'] must be) : array of default emails

?>

<?php
if (!isset($defaultEmails))
{
  $defaultEmails = $team['DefaultEmail'];
}
?>

<?php if (!empty($defaultEmails)): ?>
<div id="updater"></div>
<?php
$options = array(
  'update' => 'updater',
  'loading' => 'document.getElementById("updater").innerHTML="Setting emails for all upcoming events to default..."',
);
echo $ajax->link('Set all upcoming events to default',
  array('controller' => 'teams', 'action' => 'set_default_emails', $team['Team']['id']),
  $options,
  'Are you sure you want to set all upcoming events to the default emails?  Any existing emails will be deleted.'
);
?>
<?php endif; ?>

<div class="mar10 mar20Top">
  <?php if (!empty($defaultEmails)): ?>
    <h2>Default emails for <?php echo $team['Team']['name']; ?></h2>
    <?php foreach ($defaultEmails as $defaultEmail): ?>
      <?php 
      if (!isset($defaultEmail['DefaultEmail']))
      {
        $defaultEmail = array('DefaultEmail' => $defaultEmail);
      }
      $playerTypes = Set::extract($defaultEmail, 'DefaultEmail.PlayerTypes.{n}.humanNamePlural');
      $days = 'days';
      if ($defaultEmail['DefaultEmail']['days_before'] == 1)
      {
        $days = 'day';
      }
      $title = $defaultEmail['DefaultEmail']['title'];
      $title = '';
      if ($defaultEmail['DefaultEmail']['title'])
      {
        $title .= '<b>' . $defaultEmail['DefaultEmail']['title'] . '</b>';
      }
      else
      {
        $title .= 'Email';
      }
      $title .= ' to be sent <b>' . $defaultEmail['DefaultEmail']['days_before'] . '</b> ' . $days . ' before event to';
      $title .= ' <b>' . implode(', ', $playerTypes) . '</b>';
      $title .= '  ';
      $confirm = 'Are you sure you want to remove the default email ' . $defaultEmail['DefaultEmail']['title'] . ' from ' . $team['Team']['name']; 
      $attribs = array('title' => 'Delete default email', 'style' => 'color: red; font-size: 70%;');
      $title .= $html->link('delete', array('controller' => 'emails', 'action' => 'delete', $defaultEmail['DefaultEmail']['id']), $attribs, $confirm);
      $title .= '';
      $url = array('controller' => 'emails', 'action' => 'info', $defaultEmail['DefaultEmail']['id']);
      $style = array('width' => '600px');
      $options = compact('title', 'url', 'style');
      echo $this->element('dropDown', compact('options'));
      ?>
      <?php if (false): ?>
      <div>
        <b><?php echo $defaultEmail['DefaultEmail']['title']; ?></b>
        sent <b><?php echo $defaultEmail['DefaultEmail']['days_before']; ?></b> days before event to 
        <b><?php echo implode(', ', $playerTypes); ?></b>
      </div>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>
  <?php if (empty($defaultEmails)): ?>
    There are no default emails for team <?php echo $team['Team']['name']; ?>
  <?php endif; ?>
</div>
