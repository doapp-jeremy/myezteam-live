<?php // views/messages/index.ctp
?>

<?php $pagingDivId = 'messagesHome'; ?>

<div class="messages index" id="<?php echo $pagingDivId; ?>">
    <div>
      <h1 style="color: #339900">Welcome Back to My EZ Team</h1>
      <div class="mar20 mar10Top bigger">
        <p>
          With <b>My EZ Team</b>, you no longer need to constantly monitor your inbox to find out who will be at the game.
          <br>
          <b>My EZ Team</b> will keep track of who will, and who will not, be at your game and display that information to you
          in an easy to read manner.
        </p>
      </div>
      <ul class="mar10Top quickLinks">
        <li><?php echo $html->link('My Teams', array('controller' => 'teams', 'action' => 'index')); ?></li>
        <li><?php echo $html->link('Add Team', array('controller' => 'teams', 'action' => 'add')); ?></li>
        <li><?php echo $html->link('Add Event', array('controller' => 'events', 'action' => 'add')); ?></li>
        <li><?php echo $html->link('Add Post', array('controller' => 'topics', 'action' => 'add')); ?></li>
      </ul>
    </div>
  <div class="mar20Top">
    <?php if (empty($messages)): ?>
        <span class="bigger">Nothing new has happened with your teams since your last login:</span><span class="mar10 bigger date"><?php echo $time->niceShort($user['User']['last_login']); ?></span>
    <?php endif; ?>
    <?php if (!empty($messages)): ?>
      <?php if (isset($user['User']['last_login']) && $user['User']['last_login']): ?>
        <h1 style="color: #339900">Here's what has happened since your last login<span class="mar10 smallest date"><?php echo $time->niceShort($user['User']['last_login']); ?></span></h1>
      <?php endif; ?>
      <?php if (!isset($user['User']['last_login']) || !$user['User']['last_login']): ?>
        <h1 style="color: #339900">Here's what has been happening</h1>
      <?php endif; ?>
    <div class="mar20Top mar20">
    <?php
    $url = array('controller' => 'messages', 'action' => 'index');
    $elementName = 'message/messages';
    $elementOptions = compact('messages');
    echo $this->element('paginatorView', compact('url', 'elementName', 'elementOptions', 'pagingDivId'));
    ?>
    </div>
    <?php endif; ?>
  </div>
</div>
