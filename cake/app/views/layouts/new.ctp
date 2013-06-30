<?php // views/layouts.new.ctp

?>
<!DOCTYPE  html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?php if (isset($title) && (strlen($title) > 0)) echo $title . ' - '; ?>My EZ Team</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="Description" content="MyEzTeam makes it easy for you to manager your teams.  MyEzTeam will automatically send emails and track who is going to be at your games." />
    <meta name="Keywords" content="team manager sports leagues basketball baseball football volleyball broomball usabroomball ifba soccer hockey nhl nba espn easy team management email events amateur" />

    <?php echo $html->css('winterplain'); ?>
    <?php echo $html->css('new'); ?>
    <?php echo $html->css('dropDown'); ?>
  
    <?php
    if (isset($javascript))
    {
      echo $javascript->link('prototype');
      echo $javascript->link('scriptaculous');
      echo $javascript->link('dropDown');
    }
    ?>
    
    <script type="text/javascript">
    function myfunction(id)
    {
      if ((id == "EmailSendOnMonth") || (id == "EmailSendOnDay") || (id == "EmailSendOnYear"))
      {
        document.getElementById("EmailSendNow").checked = false;
        document.getElementById("EmailSendDaysBefore").checked = false;
        document.getElementById("EmailSendSendOn").checked = true;
      }
      else if (id == "EmailDaysBefore")
      {
        document.getElementById("EmailSendNow").checked = false;
        document.getElementById("EmailSendDaysBefore").checked = true;
        document.getElementById("EmailSendSendOn").checked = false;
      }
    }
    </script>
    
  </head>
  
  <body class="">
  
  
    <div id="container" class="">
      <div id="top">
        <div class="left">
          <div>
              <span id="site_title" style="font-size: 200%">My EZ Team</span>
            <span id="ad1" style="display: inline; height: 90px; width: 728px; margin-left: 50px;">
              <?php echo $this->element('ads', array('adId' => 1)); ?>
            </span>
            <div id="site_description">Manage your teams with ease</div>
          </div>
        </div> <!-- end title and description -->
        <a href="#" class="button feed right"><span></span></a>
        <div class="clearer"></div>
      </div> <!-- end top div -->
      
      <?php 
      $links = array(
              'Home' => '/', 
              //'Teams' => array('controller' => 'teams', 'action' => 'index'),
              //'Events' => array('controller' => 'events', 'action' => 'index'),
              //'Msg Board' => array('controller' => 'topics', 'action' => 'index'),
              //'Contacts' => array('controller' => 'contacts', 'action' => 'index')
      );
      if ($session->valid() && $session->check('User'))
      {
        $links['Teams'] = array('controller' => 'teams', 'action' => 'index');
        $links['Events'] = array('controller' => 'events', 'action' => 'index');
        $user = $session->read('User');
        $links['Edit Profile'] = array('controller' => 'users', 'action' => 'edit', $user['User']['id']);
        $links['Logout'] = array('controller' => 'users', 'action' => 'logout');
        if (isset($isAdmin) && $isAdmin)
        {
          if ($session->check('adminOn'))
          {
            $links['Turn Admin Off'] = array('controller' => 'users', 'action' => 'off');
          }
          else
          {
            $links['Turn Admin On'] = array('controller' => 'users', 'action' => 'on');
          }
        }
      }
      else
      {
        $links['Register'] = array('controller' => 'users', 'action' => 'add');
      }
      ?>
      
      <div class="path" id="nav">
        <ul>
          <?php foreach ($links as $link => $target): ?>
          <li><?php echo $html->link($link, $target, array('title' => $link)); ?></li>
          <?php endforeach; ?>
        </ul>
        <span class="flashGood hidden" id="flashGood"></span>
        <span class="hidden" id="loader"></span>
        <div class="clearer"></div>
      </div> <!-- end nav div -->

      <div id="content" class="">
        <div id="mainFlash">
        <?php
        if ($session->check('Message.flash'))
        {
          $session->flash();
        }
        ?>
        </div>
        <!-- <span id="flashGood" class="flash">Test stuff</span> -->
        
        <?php echo $content_for_layout; ?>
      </div> <!-- end content div -->
    </div> <!-- end container div -->
  
    <!-- Google Analytics -->
    <?php if (!isset($isDevelopment) || !$isDevelopment): ?>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
        _uacct = "UA-3096910-1";
        urchinTracker();
    </script>
    <?php endif; ?>
  </body>
</html>