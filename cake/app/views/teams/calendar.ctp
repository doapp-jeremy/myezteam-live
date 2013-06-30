<?php // views/teams/calendar.ctp
//$team : (required) :
?>


<?php if (isset($team['Team']['calendar_id']) && $team['Team']['calendar_id']): ?>
  <?php if (!$isAjax): ?>
    <h1><?php echo $team['Team']['name']; ?> Calendar</h1>
  <?php endif; ?>
  <?php
  //$prefix = 'http://www.google.com/calendar/feeds/default/owncalendars/full/';
  //$prefix = 'http://www.google.com/calendar/feeds/default/allcalendars/full/';
  $prefix = 'http://www.google.com/calendar/embed?src=';
  $link = $prefix . $team['Team']['calendar_id'] . '&ctz=' . $user['TimeZone']['value'];
  debug($link);
  echo $this->element('calendar', array('link' => $link));
  ?>
<?php endif; ?>

<?php if (!isset($team['Team']['calendar_id']) || !$team['Team']['calendar_id']): ?>
  <h2>A calendar has not been setup for <?php echo $team['Team']['name']; ?></h2>
  <?php if ($isManager): ?>
    <br>
    <p>
    <?php
//    $next = 'http://' . $hostname . '/teams/create_calendar/' . $team['Team']['id'];
//    $scope = 'http://www.google.com/calendar/feeds/';
//    $secure = false;
//    $createSession = true;
//    $authLink = Zend_Gdata_AuthSub::getAuthSubTokenUri($next, $scope, $secure, $createSession);
    ?>
    Click <?php echo $html->link('here to create a google calendar', array('controller' => 'teams', 'action' => 'create_calendar', $team['Team']['id'])); ?> for the team.  You must have a google account to
    create the calendar and will be asked for access to your google account.
    </p>
  <?php endif; ?>
<?php endif; ?>

<?php if (false && $session->check('calendarId')): ?>
  <?php $calId = $session->read('calendarId'); ?>
<!-- Need to make calendar publically visible -->
<script type="text/javascript" src="http://www.google.com/jsapi?key=<?php echo $devKey; ?>">
    google.load("gdata", "1");

    //var ACL_URL = "http://www.google.com/calendar/feeds/default/acl/full/default";
    var ACL_URL = "http://www.google.com/calendar/feeds/" + <?php echo $calId; ?> + "/acl/full/default";

    var entry = new google.gdata.AclEntry();
    var role = new google.gdata.AclRole();
    //role.setValue(google.gdata.AclRole.VALUE_READER);
    role.setValue('http://schemas.google.com/gCal/2005#read');
    entry.setRole(role);

    var scope = new google.gdata.AclScope();
    scope.setType(google.gdata.AclScope.TYPE_DEFAULT);
    entry.setScope(scope);

    calendarService.updateEntry(ACL_URL, entry, function() {alert('ACL isupdated');}, handleError, google.gdata.AclEntry); 
</script>
<?php endif; ?>