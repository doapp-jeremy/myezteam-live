<?php // views/elements/user/userInfo.ctp : renders player info

?>

<?php
$model = 'User';
$fields = array('name', 'email', 'additional_emails');
if (isset($user['UserEmail']) && !empty($user['UserEmail']))
{
  $emails = Set::extract($user['UserEmail'], '{n}.email');
  $user['User']['additional_emails'] = implode('<br>', $emails);
}

$fields[] = 'teams';
foreach ($user['Player'] as $player)
{
  $teamId = $player['Team']['id'];
  if (array_search($teamId, $teamIds) !== false)
  {
    $teams[] = $html->link($player['Team']['name'], array('controller' => 'teams', 'action' => 'view', $teamId));
  }
  else
  {
    $teams[] = $player['Team']['name'];
  }
}
if (!empty($teams))
{
  $user['User']['teams'] = implode('<br>', $teams);
}

if (isset($isAdmin) && $isAdmin)
{
  $fields[] = 'created';
  $user['User']['created'] = $time->niceShort($user['User']['created']);
  if (isset($user['User']['ip']) && (strlen($user['User']['ip']) > 0))
  {
    $user['User']['ip_addresses'] = $user['User']['ip'] . '<span class="mar5 small" style="color: #999999">' . $time->niceShort($user['User']['last_login']) . '</span>';
  }
  else
  {
    $user['User']['ip_addresses'] = '';  
  }
  
  $fields[] = 'last_login';
  $user['User']['last_login'] = $time->niceShort($user['User']['last_login']) . '<span class="mar5 small" style="color: #999999">' . $user['User']['ip'] . '</span>';
  
  $fields[] = 'ip_addresses';
  if (isset($user['UserIp']) && !empty($user['UserIp']))
  {
    $ips = Set::combine($user['UserIp'], '{n}.ip', '{n}.created');
    foreach ($ips as $ip => $when)
    {
      $user['User']['ip_addresses'] .= '<br>' . $ip . '<span class="mar5 small" style="color: #999999">' . $time->niceShort($when) . '</span>';
    }
  }
  $fields[] = 'feed_id';
  $fields[] = 'password_forgotten';
  $fields[] = 'password_change_key';
  $fields[] = 'time_zone';
  $user['User']['time_zone'] = $user['TimeZone']['name'];
}
$data = $user;
$showEmpty = false;

$edit = '';
if (isset($isManager) && $isManager)
{
  $edit = '<span class="mar10 smaller">' . $html->link('edit', array('controller' => 'users', 'action' => 'edit', $user['User']['id'])) . '</span>';
}
?>

<div class="player view">
  <h1>User Info<?php echo $edit; ?></h1>
  <?php echo $this->element('infos', compact('fields', 'model', 'data', 'showEmpty')); ?>
</div>
