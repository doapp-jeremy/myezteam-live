<?php // views/elements/userName.ctp : renders a user name
//$user : (optional - if not set, $userId must be) : the user to render the name for
//$userId : (optional - if not set, $user must be) : the userId of the user to render the name for
//$includeEmail : (optional) : if true, will include the email of the user
//$link : (optional) : if true, links to /users/view/
//$noSpan : (optional) : if set, won't wrap user name in a span
?>

<?php
if (!isset($user))
{
	$user = $this->requestAction('/users/info/' . $userId);
}
$userName = '';
if (isset($noSpan) && ($noSpan === false))
{
  $userName = '<span class="user name" title="' . $user['User']['email'] . '">';
}

if (isset($user['User']['nameOrEmail']))
{
  $userName .= '<span title="' . $user['User']['email'] . '">';
	$userName .= $user['User']['nameOrEmail'];
	$userName .= '</span>';
}
else
{
  $userName .= $user['User']['first_name'] . ' ' . $user['User']['last_name'];	
}

if (isset($noSpan) && ($noSpan === false))
{
  $userName .= '</span>';
}

if (isset($includeEmail) && ($includeEmail === true))
{
	if (isset($noSpan) && ($noSpan === false))
  {
    $userName .= '<span class="email">';
  }
	$userName .= '(' . $user['User']['email'] . ')';
  if (isset($noSpan) && ($noSpan === false))
  {
    $userName .= '</span>';
  }
}
if (isset($link) && ($link !== false))
{
  if ($link === true)
  {
	 $userName = $html->link($userName, array('controller' => 'users', 'action' => 'view', $user['User']['id']), array('title' => $user['User']['email']));
  }
  else
  {
   $userName = $html->link($userName, $link, array('title' => $user['User']['email']));
  }
}
echo $userName;
?>