<?php // views/pages/home.ctp : the home page

?>

<div class="" style="width: 980px">
  <div class="mar10Top bigger left" style="width: 625px">
    <div>
      <h1 style="color: #339900">Manage your teams with ease</h1>
      <div class="mar20 mar10Top">
        <p>
          With <b>My EZ Team</b>, you no longer need to constantly monitor your inbox to find out who will be at the game.
          <b>My EZ Team</b> will keep track of who will, and who will not, be at your game and display that information to you
          in an easy to read manner.
        </p>
      </div>
    </div>

  <!-- <div class="big mar10 items left border" style="width: 625px"> -->
    <div>
    <div class="mar20Top features">
      <h2 style="color: #339900">Features:</h2>
      <ul>
        <li><b>Automatically send emails.</b>  You create the content, then we'll send it when you want.</li>
        <li>We'll gather the responses from your players as they come in so you don't have to.</li>
        <li>With our <b>color-coded pie chart</b>, you can view in an instant how many players you'll have at your game.</li>
        <li><b>Message Board.</b>  Eliminate the clutter in your inbox.  You can talk smack, or discuss strategy, with your teammates on your personalized message board, eliminting the need to send emails back and forth.</li>
        <li>Integration with <b>Google Calendar</b>. View all your events in the calendar view</li>
      </ul>
    </div>
    <div class="mar20Top">
      <h2 style="color: #339900">Coming Soon:</h2>
      <ul class="features">
        <li>Ability to subscribe to calendar so it shows up in your google calendar</li>
        <li><b>RSS Feed.</b>  View what's happening with your teams via your personalized RSS Feed.  You will see when someone RSVPs to your event or when someone adds a post to your message board in your favorite reader.</li>
        <li><b>Support for leagues.</b>  Link teams together and create schedules for an entire league.</li>
        <li><b>Statistics</b></li>
      </ul>
    </div>
    <?php if (false): ?>
    <div class="mar20Top">
      <h2 style="color: #339900">Steps:</h2>
      <ul class="features">
        <li><?php echo $html->link('Login', array('controller' => 'users', 'action' => 'login')); ?> or <?php echo $html->link('Register', array('controller' => 'users', 'action' => 'add')); ?></li>
        <li>Create a <?php echo $html->link('team', array('controller' => 'teams', 'action' => 'add')); ?></li>
        <li>Add <?php echo $html->link('players', array('controller' => 'players', 'action' => 'add')); ?> to the team</li>
        <li>Create an <?php echo $html->link('event', array('controller' => 'events', 'action' => 'add')); ?> for the team</li>
      </ul>
    </div>
    <?php endif; ?>
  </div>
  </div>
  <div id="loginForm" class="mar10 left" style="width: 325px">
    <?php echo $this->element('login'); ?>
    <?php echo $this->element('spottt'); ?>
    <?php if (false): ?>
      <style type="text/css"> table.spottt_tb, table.spottt_tb tr, table.spottt_tb td, table.spottt_tb a, table.spottt_tb tr a img { margin:0; padding:0; border:none; background:none; position:static; text-decoration:none; display:block; width:125px; vertical-align:top;} .spottt_thumb, .spottt_thumb img, .spottt_thumb a, .spottt_thumb td { height:125px; } .spottt_label, .spottt_label img, .spottt_label a, .spottt_label td { height:21px;}</style><table cellspacing="0px" class="spottt_tb" id="sp_code_table_do3myqj97sow4kkw"><tr class="spottt_thumb"><td><a href="http://click.spottt.com/sp_click_do3myqj97sow4kkw.php" target="_top"><img src="http://view.spottt.com/sp_image_do3myqj97sow4kkw.jpg" border="0" alt="Spottt" /></a></td></tr><tr class="spottt_label"><td><a href="http://home.spottt.com/sp_index_do3myqj97sow4kkw.php" ><img src="http://view.spottt.com/sp_label_do3myqj97sow4kkw.jpg" border="0" alt="Spottt" /></a></td></tr></table>
    <?php endif; ?>
    <?php if (false): ?>
    <div style="float: right">
    <script type="text/javascript"><!--
    google_ad_client = "pub-6732472736045078";
    /* 180x150, created 4/30/08 */
    google_ad_slot = "5366303016";
    google_ad_width = 180;
    google_ad_height = 150;
    //-->
    </script>
    <script type="text/javascript"
    src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
    </script>
        <script type="text/javascript"><!--
        google_ad_client = "pub-6732472736045078";
        /* 180x150, created 4/30/08 */
        google_ad_slot = "9355085132";
        google_ad_width = 180;
        google_ad_height = 150;
        //-->
        </script>
          <script type="text/javascript"><!--
          google_ad_client = "pub-6732472736045078";
          /* 180x150, created 4/29/08 */
          google_ad_slot = "5934961493";
          google_ad_width = 180;
          google_ad_height = 150;
          //-->
          </script>
          <script type="text/javascript"
          src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
          </script>
    </div>
    <?php endif; ?>
  </div>

</div>