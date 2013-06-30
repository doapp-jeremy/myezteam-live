<?php
class GoogleCalComponent extends Object 
{
  var $controller;
  var $cal = null;
  
  const CALENDAR_OWN_FEED_URI = 'http://www.google.com/calendar/feeds/default/owncalendars/full';
  const CALENDAR_EVENT_FEED_URI = 'http://www.google.com/calendar/feeds/default/private/full';
  const CALENDAR_UPDATE_FEED_URI = 'http://www.google.com/calendar/feeds/default/owncalendars/full/';
  #const CALENDAR_FEED_ACL_URI = 'http://www.google.com/calendar/feeds/default/acl/full/default';
  var $aclSchema = 'http://schemas.google.com/acl/2007#accessControlList';
  
  function startup(&$controller)
  {
    // This method takes a reference to the controller which is loading it.
    // Perform controller initialization here.
    $this->controller = &$controller;

    set_include_path(get_include_path() . PATH_SEPARATOR . dirname(dirname(dirname(dirname(__FILE__)))) . DS . 'vendors');
    include('Zend/gdata_connect.php');
  }
  
  function createCalendar($team, $creator)
  {
    $cal = $this->__getCalendar();
    
    if ($cal)
    {
      $data = "
    <entry xmlns='http://www.w3.org/2005/Atom' 
       xmlns:gd='http://schemas.google.com/g/2005' 
       xmlns:gCal='http://schemas.google.com/gCal/2005'>
      <title type='text'>" . $team['Team']['name'] . "</title>
      <summary type='text'>" . $team['Team']['description'] . "</summary>
      <gCal:timezone value='" . $creator['Creator']['value'] . "'></gCal:timezone>
      <gCal:hidden value='false'></gCal:hidden>
      <gCal:color value='#2952A3'></gCal:color>
      <gCal:accesslevel value='owner'/>
      <gd:where rel='' label='' valueString='" . $team['Team']['default_location'] . "'></gd:where>
    </entry>
    ";
      $uri = self::CALENDAR_OWN_FEED_URI;
try {
      $response = $cal->post($data, $uri);
echo $response;
echo '<br>';
}
catch (Exception $e)
{
  echo 'Google Exception: ' . $e->getMessage();
  print_r($e);
  return null;
}
      $location = $response->getHeader('Location');
      $location = explode('/', $location);
      $calId = $location[sizeof($location) - 1];
echo 'calId: ' . $calId;
exit();
      $this->makePublic($calId);
      return $calId;
    }
    return false;
  }
  
  function makePublic($calId)
  {
    $cal = $this->__getCalendar();
    if ($cal)
    {
      $data = "
        <entry xmlns='http://www.w3.org/2005/Atom' xmlns:gAcl='http://schemas.google.com/acl/2007'>
          <category scheme='http://schemas.google.com/g/2005#kind' term='http://schemas.google.com/acl/2007#accessRule'/>
          <gAcl:scope type='default'></gAcl:scope>
          <gAcl:role value='http://schemas.google.com/gCal/2005#read'></gAcl:role>
        </entry>
      ";
      $uri = 'http://www.google.com/calendar/feeds/' . $calId . '/acl/full';
      $result = $cal->post($data, $uri);
    }
  }
  
  function __createEvent($calId, $event)
  {
    // only create event if end is after start cause google doesn't like that
    if (strtotime($event['Event']['start']) >= strtotime($event['Event']['end']))
    {
      return false;
    }
    $cal = $this->__getCalendar();
    
    if ($cal)
    {
      $newEvent = $cal->newEventEntry();
      $newEvent->title = $cal->newTitle($event['Event']['name']);
      $newEvent->where = array($cal->newWhere($event['Event']['location']));
      $newEvent->content = $cal->newContent($event['Event']['description']);

      $when = $cal->newWhen();
      $when->startTime = date('c', strtotime($event['Event']['start']));
      $when->endTime = date('c', strtotime($event['Event']['end']));

      $newEvent->when = array($when);

      $uri = 'http://www.google.com/calendar/feeds/' . $calId . '/private/full';
      $insertedEvent = $cal->insertEvent($newEvent, $uri);
      return $insertedEvent;
    }
    return false;
  }
  
  function updateEvent($event)
  {
    $cal = $this->__getCalendar();
    if ($cal)
    {
      $oldEvent = $cal->getCalendarEventEntry($event['Event']['cal_event_id']);
      $oldEvent->title = $cal->newTitle($event['Event']['name']);
      $oldEvent->where = array($cal->newWhere($event['Event']['location']));
      $oldEvent->content = $cal->newContent($event['Event']['description']);

      $when = $cal->newWhen();
      $when->startTime = date('c', strtotime($event['Event']['start']));
      $when->endTime = date('c', strtotime($event['Event']['end']));
      $oldEvent->when = array($when);

      $oldEvent->save();
    }
  }
  
  function createEvents($calId, $events)
  {
    $createdEvents = array();
    $cal = $this->__getCalendar();
    
    if ($cal)
    {
      foreach ($events as $event)
      {
        $newEvent = $this->__createEvent($calId, $event);
        if ($newEvent)
        {
          $createdEvents[$event['Event']['id']] = $newEvent;
        }
      }
    }
    return $createdEvents;
  }
  
  function deleteEvent($eventId)
  {
    $cal = $this->__getCalendar();
    if ($cal)
    {
      $event = $cal->getCalendarEventEntry($eventId);
      $event->delete();
    }
  }
  
  function deleteCalendar($calId)
  {
    $cal = $this->__getCalendar();
    if ($cal)
    {
      $uri = self::CALENDAR_UPDATE_FEED_URI . $calId;
      $cal->delete($uri);
    }
  }
  
  
  function __getCalendar()
  {
    if (!$this->cal)
    {
//      $sessionToken = Zend_Gdata_AuthSub::getAuthSubSessionToken($token);
//      $client = Zend_Gdata_AuthSub::getHttpClient($sessionToken);
//
//      $this->cal = new Zend_Gdata_Calendar($client, 'MyEZTeam');
      $this->cal = $this->controller->_getGoogleClient();
    }
    return $this->cal;;
  }
  
//  function getAuthLink($path)
//  {
//    $next = 'http://' . $this->controller->hostname . '/' . $path;
//    $scope = 'http://www.google.com/calendar/feeds/';
//    $secure = false;
//    $createSession = true;
//    $authLink = Zend_Gdata_AuthSub::getAuthSubTokenUri($next, $scope, $secure, $createSession);
//    return $authLink;
//  }
  
//  function createEvents($cal, $calId, $events)
//  {
//    $data = "
//      <feed xmlns='http://www.w3.org/2005/Atom' xmlns:batch='http://schemas.google.com/gdata/batch' xmlns:gCal='http://schemas.google.com/gCal/2005'>
//        <category scheme='http://schemas.google.com/g/2005#kind' term='http://schemas.google.com/g/2005#event' />
//    ";
//    $i = 1;
//    foreach ($events as $event)
//    {
//      $start = strtotime($event['Event']['start']);
//      $end = strtotime($event['Event']['end']);
//      if ($end <= $start)
//      {
//        // don't create event is end is < start
//        continue;
//      }
//      $start = date('c', $start);
//      $end = date('c', $end);
//      $data .= "
//          <entry xmlns='http://www.w3.org/2005/Atom' xmlns:gd='http://schemas.google.com/g/2005'>
//            <batch:id>" . $i++ . "</batch:id>
//            <batch:operation type='insert' />
//            <category scheme='http://schemas.google.com/g/2005#kind' term='http://schemas.google.com/g/2005#event'></category>
//            <title type='text'>" . $event['Event']['name']  . "</title>
//            <content type='text'>" . $event['Event']['description'] . "</content>
//            <gd:transparency value='http://schemas.google.com/g/2005#event.opaque'></gd:transparency>
//            <gd:eventStatus value='http://schemas.google.com/g/2005#event.confirmed'></gd:eventStatus>
//            <gd:where valueString='" . $event['Event']['location'] . "'></gd:where>
//            <gd:when startTime='" . $start . "' endTime='" . $end . "'></gd:when>
//          </entry>
//      ";
//    }
//    $data .= "</feed>";
//    $uri = 'http://www.google.com/calendar/feeds/' . $calId . '/private/full/batch';
//    $response = $cal->post($data, $uri);
//    debug($response);
//    $eventEntry = new Zend_Gdata_App_Entry($response->getBody());
//    debug($eventEntry->getContent());
//    exit();
//  }
}
?>
