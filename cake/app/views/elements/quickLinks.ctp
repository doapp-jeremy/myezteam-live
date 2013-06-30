<?php // views/elements/quickLinks.ctp

?>
<?php
$user = $session->read('User');
?>

<?php if ($session->check('User')): ?>
  <fieldset>
    <legend><?php echo $user['User']['nameOrEmail']; ?><span class="mar5 smallest"><?php echo $html->link('edit', array('controller' => 'users', 'action' => 'edit', $user['User']['id'])); ?></span></legend>
    <ul class="mar10Top quickLinks">
      <li><?php echo $html->link('Whats New', array('controller' => 'messages', 'action' => 'index')); ?></li>
      <li><?php echo $html->link('My Teams', array('controller' => 'teams', 'action' => 'index')); ?></li>
      <li><?php echo $html->link('Add Team', array('controller' => 'teams', 'action' => 'add')); ?></li>
      <li><?php echo $html->link('Add Event', array('controller' => 'events', 'action' => 'add')); ?></li>
      <li><?php echo $html->link('Add Post', array('controller' => 'topics', 'action' => 'add')); ?></li>
      <li><?php echo $html->link('Logout', array('controller' => 'users', 'action' => 'logout')); ?></li>
    </ul>
  </fieldset>      
<?php endif; ?>
  