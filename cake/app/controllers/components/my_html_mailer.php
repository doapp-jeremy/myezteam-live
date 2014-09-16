<?php
class MyHtmlMailerComponent extends Object
{
  var $controller = true;
  var $hostname;
  var $time;
  
  
  function startup(&$controller)
  {
    // This method takes a reference to the controller which is loading it.
    // Perform controller initialization here.
    $this->controller = &$controller;
    $this->hostname = $controller->hostname;
    App::import('Helper', 'Time');
    $this->time = & new TimeHelper();
  }
  
  
  
  function getHtmlBody($user, $emails, $email, $response_types, $player, $isIBMEmail, $onlyIBMEMail, $response, $pieChartData)
  {
    $output = '';
    $output .= $this->head();
    
    $output .= $this->title();
    
    $output .= $this->accountInfo($user, $emails);
    
    $output .= $this->event_info($email, $response, $pieChartData, $response_types, $player);
    
    $output .= $this->rsvp($response_types, $email, $player, $isIBMEmail, $onlyIBMEMail);
    
    $output .= $this->tail();
    return $output;
  }
  
  function getDailyDigestBody($user, $emails, $messages, $events = array(), $response_types = array())
  {
    $output = '';
    $output .= $this->head();
    
    $output .= $this->title();
    $output .= $this->accountInfo($user, $emails);

    $output .= $this->whatsHappened($messages);
    if (!empty($events))
    {
      $output .= $this->upcomingEvents($events, $response_types);
    }
    
    $output .= $this->tail();
    return $output;
  }
  
  function upcomingEvents($events, $response_types)
  {
    $output = '';
    $output .= '<fieldset style="border: 1px solid #ccc; margin-top: 30px; padding: 16px 20px; background-color: #FFCCCC;">';
    $output .= '  <legend style="color: #FF0033; font-size: 160%; font-weight: normal; margin-left: 20px;">Upcoming Events</legend>';
    foreach ($events as $event)
    {
      $output .= '  <fieldset style="margin-top: 0px; margin-bottom: 20px; padding: 16px 0; background-color: #FFFFFF;">';
      $output .= '    <legend style="color: #990000; font-size: 120%; font-weight: normal; margin-left: 20px;">' . $event['Event']['name'] . '</legend>';
      $response = $event;
      $player['Player'] = $event['Player'];
      $player['PlayerType'] = $event['PlayerType'];
      $pieChartData = $event['PieChartData'];
      $output .= $this->email_event_info($event, $response, $pieChartData, $response_types, $player);
      $output .= '  </fieldset>';
    }
    $output .= '</fieldset>';
    
    return $output;
  }
  
  function eventMessage($event)
  {
    if (isset($event['Team']))
    {
      $event['Event']['Team'] = $event['Team'];
    }
    $output = '<span style="color: #006633;">';
    $output .= $this->link($event['Event']['Team']['name'], '/main.html#/teams/' . $event['Event']['team_id'], null, 'style="color: #006633;"');
    $output .= ':</span>';
    $output .= '<span style="margin-left: 5px;">A new event has been added: ';
    $output .= $this->link($event['Event']['name'], '/main.html#/teams/' . $event['Event']['team_id'], null, 'style="color: #990000;"');
    $output .= '</span>';
    $output .= '<span style="margin-left: 10px; font-size: 75%; color: #333333;">' . $this->time->startEnd($event['Event']['start'], $event['Event']['end']) . '</span>';
    return $output;
  }
  
  function responseMessage($response)
  {
    foreach (array('Player', 'ResponseType', 'Event') as $model)
    {
      if (isset($response[$model]))
      {
        $response['Response'][$model] = $response[$model];
      }
    }
    if (isset($response['User']))
    {
      $response['Response']['Player']['User'] = $response['User'];
    }
    if (isset($response['Team']))
    {
      $response['Response']['Event']['Team'] = $response['Team'];
    }

    $output = '';
    $output .= '<span style="color: #996600;" title="' . $response['Response']['Player']['User']['email'] . '">';
    $output .= $response['Response']['Player']['User']['nameOrEmail'];
    $output .= '</span>';
    $rsvp = $response['Response']['ResponseType']['name'];
    $output .= ' RSVP\'d ';
    $output .= '<span style="color: #' . $response['Response']['ResponseType']['color'] . '">' . Inflector::humanize($rsvp) . '</span>';
    $output .= ' to ';
    $output .= '<span class="eventMessageTitle">';
    $event = $response['Response']['Event'];
    $output .= $this->link($event['name'], '/main.html#/teams/' . $event['team_id'], null, 'style="color: #990000;"');
    $output .= '</span>';
    $output .= ' for ';
    $output .= '<span class="teamMessageTitle">';
    $team = $response['Response']['Event']['Team'];
    $output .= $this->link($team['name'], '/main.html#/teams/' . $team['id'], null, 'style="color: #006633;"');
    $output .= '</span>';
    $output .= '<span style="margin-left: 10px; font-size: 75%; color: #333333;">' . $this->time->niceShort($response['Response']['created']) . '</span>';
    if (isset($response['Response']['comment']) && (strlen($response['Response']['comment']) > 0))
    {
      $output .= '<div style="margin-left: 20px; font-size: 90%;">';
      $output .= $response['Response']['comment'];
      $output .= '</div>';
    }
    return $output;
  }
  
  function topicMessage($topic)
  {
    if (isset($topic['User']))
    {
      $topic['Topic']['User'] = $topic['User'];
    }
    $link = $this->link($topic['Topic']['title'], '/topics/posts/' . $topic['Topic']['id'], null, 'style="color: #66FF00;"');
    $output = 'A new topic was started: <span class="topicMessageTitle">' . $link . '</span>';
    $output .= ' by <span style="color: #996600;">' . $topic['Topic']['User']['nameOrEmail'] . '</span> at';
    $output .= '<span style="margin-left: 5px; font-size: 75%; color: #333333;">' . $this->time->niceShort($topic['Topic']['created']) . '</span>';
    return $output;
  }
  
  function postMessage($post)
  {
    if (isset($post['User']))
    {
      $post['Post']['User'] = $post['User'];
    }
    if (isset($post['Topic']))
    {
      $post['Post']['Topic'] = $post['Topic'];
    }
    $output = '<span style="color: #996600;">' . $post['Post']['User']['nameOrEmail'] . '</span> posted to ';
    $output .= '<span style="color: #66FF00;">';
    $output .= $this->link($post['Post']['Topic']['title'], '/topics/posts/' . $post['Post']['topic_id'], null, 'style="color: #66FF00;"');
    $output .= '</span>';
    $output .= '<span style="margin-left: 10px; font-size: 75%; color: #333333;">' . $this->time->niceShort($post['Post']['created']) . '</span>';


    $data = nl2br($post['Post']['text']);
    $maxPostSize = 500;
    if (strlen($data) > $maxPostSize)
    {
      $data = nl2br(substr($data, 0, $maxPostSize)) . '...' . $this->link('View the rest of the post', '/topics/posts/' . $post['Post']['topic_id']);
    }

    $output .= '<div style="margin-top: 10px; margin-left: 20px; font-size: 90%;">';
    $output .= $data;
    $output .= '</div>';

    return $output;
  }

  function new_userMessage($new_user)
  {
    $activated = (isset($new_user['NewUser']['password']) && (strlen($new_user['NewUser']['password']) > 0));
    $output = '';
    $output .= 'A new user has been added: <span style="color: #996600;">' . $new_user['NewUser']['nameAndEmail'] . '</span>';
    $output .= '<span style="margin-left: 5px; font-size: 75%; color: #333333;">' . $new_user['NewUser']['created'] . '</span>';
    if ($activated)
    {
      $output .= '<span style="margin-left: 5px;">Activated</span>';
    }
    return $output;
  }

  function errorMessage($error)
  {
    $output = '';
    $output .= 'An error has occurred: <span style="color: red">' . $error['Error']['function'] . '</span>';
    $output .= '<div style="margin-top: 10px; margin-left: 10px">';
    $output .= $error['Error']['message'];
    $output .= '</div>';
    return $output;
  }
  
  function message($message)
  {
    $output = '';
    $output .= '<div style="margin-top: 10px; margin-left: 10px; font-size: 120%;" id="' . $message['Message']['id'] . '">';
    foreach (array('event', 'response', 'topic', 'post', 'new_user', 'error') as $field)
    {
      $model = Inflector::camelize($field);
      if (isset($message['Message'][$field . '_id']) && $message['Message'][$field . '_id'] && isset($message[$model]))
      {
        $output .= $this->{$field . 'Message'}($message);
      }
    }
    $output .= '';
    $output .= '</div>';
    return $output;
  }
  
  function whatsHappened($messages)
  {
    $output = '';
    $output .= '<fieldset style="border: 1px solid #ccc; margin-top: 30px; padding: 16px 20px; background-color: #FFFFCC;">';
    $output .= '  <legend style="color: #FF9900; font-size: 120%; font-weight: normal; margin-left: 20px;">What\'s Happened</legend>';
    $output .= '  <div style="clear: left; margin: 0 10px; background-color: #FFFFFF; padding: 5px; border: 1px solid; text-align: left;">';
    if (!empty($messages))
    {
      foreach ($messages as $message)
      {
        $output .= $this->message($message);
      }
    }
    else
    {
      $output .= 'Nothing new has happened';
    }
    $output .= '  </div>';
    $output .= '</fieldset>';
    return $output;
  }
  
  function event_info($email, $response, $pieChartData, $response_types, $player)
  {
    $output = '';
    $output .= '<fieldset style="border: 1px solid #ccc; margin-top: 30px; padding: 16px 20px; background-color: #FFCCCC;">';
    $output .= '  <legend style="color: #FF0033; font-size: 160%; font-weight: normal; margin-left: 20px;">Event Info: ' . $email['Event']['name'] . '</legend>';
    $output .= '  <div style="clear: left; margin: 0 10px; background-color: #FFFFFF; padding: 5px; border: 1px solid; text-align: left;">';
    $output .= $this->email_event_info($email, $response, $pieChartData, $response_types, $player);
    $output .= '  </div>';
    $output .= '</fieldset>';
    return $output;
  }
  
  function email_event_info($event, $response, $pieChartData, $response_types, $player)
  {
    $output = '';
    $fields = array('team', 'name', 'when', 'location', 'description', 'default_response');
    if ($player['Player']['id'] && ($player['PlayerType']['name'] != 'member'))
    {
      array_push($fields, 'your_RSVP');
    }
    $event['Event']['name'] = $this->link($event['Event']['name'], '/events/view/' . $event['Event']['id'], $event['Event']['name']);
    $event['Event']['team'] = $this->link($event['Team']['name'], '/teams/view/' . $event['Event']['team_id'], $event['Team']['name']);
    if (!isset($event['DefaultResponse']))
    {
      $event['DefaultResponse'] = $event['ResponseType'];
    }

    if (empty($response) || !$response['Response']['id'])
    {
      $rsvp = '<span style="color: #' . $event['DefaultResponse']['color'] . '">';
      $rsvp .= Inflector::humanize($event['DefaultResponse']['name']);
      $rsvp .= ' (Default)';
      $rsvp .= '</span>';
    }
    else
    {
      $rsvp = '<span style="color: #' . $response['ResponseType']['color'] . '">';
      $rsvp .= Inflector::humanize($response['ResponseType']['name']);
      $rsvp .= '</span>';
      $rsvp .= '<span style="color: #9999CC; font-size: 80%; margin-left: 10px;">' . $this->time->niceShort($response['Response']['created']) . '</span>';
      $rsvp .= '<br>';
      $rsvp .= '<div style="margin-left: 10px;">' . nl2br($response['Response']['comment']) . '</div>';
    }
    if (isset($event['Email']['rsvp']) && $event['Email']['rsvp'])
    {
      array_push($fields, 'change_RSVP');

      $changeRsvp = '';
      $keys = array_keys($response_types);
      $i = 0;
      foreach ($response_types as $response_type)
      {
        $typeId = $keys[$i++];
        $name = $response_type;
        $link = '/index.html#/responses/email_rsvp/' . $event['Event']['id'] . '/' . $player['Player']['id'] . '/' . $typeId . '/' . $player['Player']['response_key'];
        $changeRsvp .= $this->link(Inflector::humanize($name), $link, $name);
        $changeRsvp .= '<br>';
      }
      $event['Event']['change_RSVP'] = $changeRsvp;
    }
    else if (!isset($event['Email']) && $player['Player']['id'] && (($player['PlayerType']['name'] == 'regular') || $response['Response']['id']))
    {
      //array_push($fields, 'change_RSVP');
      //$event['Event']['change_RSVP'] = $this->link('Change RSVP', '/responses/add/' . $event['Event']['id']);
    }

    if ($player['Player']['id'] && ($player['PlayerType']['name'] != 'member'))
    {
      $event['Event']['your_RSVP'] = $rsvp;
    }

    $event['Event']['when'] = $this->time->startEnd($event['Event']['start'], $event['Event']['end']);
    $event['Event']['location'] = nl2br($event['Event']['location']);
    $event['Event']['description'] = nl2br($event['Event']['description']);
    $event['Event']['default_response'] = Inflector::humanize($event['DefaultResponse']['name']);
    $model = 'Event';
    $data = $event;
    $showEmpty = false;

    foreach($fields as $field)
    {
      if ((isset($data[$model][$field]) && (strlen($data[$model][$field]) > 0)) || (isset($showEmpty) && $showEmpty))
      {
        $output .= '<div style="margin-left: 10px">';
        $output .= '  <div style="font-weight: bold;">' . Inflector::humanize($field) . '</div>';
        $output .= '  <div style="margin-left: 20px;">';
        if (isset($data[$model][$field]))
        {
          if (is_array($data[$model][$field]))
          {
            $output .= implode('<br>', $data[$model][$field]);
          }
          else
          {
            $output .= $data[$model][$field];
          }
        }
        $output .= '  </div>';
        $output .= '</div>';
      }
    }
    $output .= $this->pie_chart($pieChartData);
    return $output;
  }
  
  function pie_chart($pieChartData)
  {
    $output = '';
    $output .= '<div style="margin-left: 10px">';
    $output .= '  <div style="font-weight: bold;">RSVPs</div>';
    $output .= '  <div style="margin: 10px;">';
    $output .= $this->response_pie_chart($pieChartData);
    $output .= '  </div>';
    $output .= '</div>';
    return $output;
  }
  
  function response_pie_chart($pieChartData)
  {
    $responses = $pieChartData['responses'];
    $responseTypes = $pieChartData['responseTypes'];
    $default = $pieChartData['default'];
    $output = '';
    $labels = array();
    $colors = array();
    $values = array();
    foreach ($responseTypes as $responseType)
    {
      $responseType = array_pop($responseTypes);
      if (($responseType != 'no_response') || ($default == 'no_response'))
      {
        $responseName = $responseType['ResponseType']['name'];
        array_push($colors, $responseType['ResponseType']['color']);
        $value = 0;
        if (isset($responses[$responseName]))
        {
          $value = $responses[$responseName];
        }
        array_push($values, $value);
        $label = Inflector::humanize($responseName) . ' (' . $value . ')';
        if ($default == $responseName)
        {
          $label .= ' - Default';
        }
        array_push($labels, $label);
      }
    }
    $url = 'http://chart.apis.google.com/chart?';
    $url .= 'cht=p3';
    $url .= '&chs=600x240';
    $url .= '&chd=t:' . implode(',', $values);
    $url .= '&chco=' . implode(',', $colors);
    $url .= '&chl=' . implode('|', $labels);
    $output .= '<img alt="Response Pie Chart" src="' . $url . '" />';
    return $output;
  }
  
  function rsvp($response_types, $email, $player, $isIBMEmail, $onlyIBMEMail)
  {
    $output = '';
    $output .= '<fieldset style="border: 1px solid #ccc; margin-top: 30px; padding: 16px 20px; background-color: #FFFFCC;">';
    if (isset($email['Email']['content']) && $email['Email']['content'])
    {
      $output .= $this->email_content($email);
    }
    if (isset($email['Email']['rsvp']) && $email['Email']['rsvp'])
    {
      $output .= $this->rsvp_form($response_types, $email, $player, $isIBMEmail, $onlyIBMEMail);
    }
    $output .= '</fieldset>';
    return $output;
  }
  
  function rsvp_form($response_types, $email, $player, $isIBMEmail, $onlyIBMEmail)
  {
    $output = '';
    $output .= '<legend style="color: #FF9900; font-size: 160%; font-weight: bold;">RSVP</legend>';
    $output .= '  <fieldset style="margin-top: 0px; margin-bottom: 20px; padding: 16px 0;">';
    $output .= '    <legend style="color: #FF9900; font-size: 120%; font-weight: normal; margin-left: 20px;">Click on a link to RSVP</legend>';
    $output .= '    <div style="clear: left; margin: 0 10px;">';
    $keys = array_keys($response_types);
    $i = 0;
    foreach ($response_types as $response_type)
    {
      $typeId = $keys[$i++];
      $name = $response_type;
      $link = '/index.html#/responses/email_rsvp/' . $email['Event']['id'] . '/' . $player['Player']['id'] . '/' . $typeId . '/' . $player['Player']['response_key'];
      $output .= '<span style="margin-left: 20px">';
      $output .= $this->link(Inflector::humanize($name), $link, $name);
      $output .= '</span>';
    }
    $output .= '    </div>';
    $output .= '  </fieldset>';
//     if (!$isIBMEmail || !$onlyIBMEmail)
//     {
//       $output .= '<h2>Or</h2>';
//       $output .= '<fieldset style="margin-top: 0px; margin-bottom: 20px; padding: 16px 0;">';
//       $output .= '  <legend style="color: #FF9900; font-size: 120%; font-weight: normal; margin-left: 20px;">Select your RSVP</legend>';
//       if ($isIBMEmail && !$onlyIBMEmail)
//       {
//         $output .= '<div style="clear: left; margin: 0 10px;">';
//         $output .= 'We have detected that 1 of your email address is an IBM account.  Please use the links above if your are responding from that email address because Lotus Notes doesn\'t like the form in the email.';
//         $output .= '</div>';
//         $output .= '<br>';
//       }
//       $output .= '  <div style="margin-left: 30px">';
//       $output .= '    <form action="http://' . $this->hostname . '/responses/rsvp" method="post">';
//       $output .= '      <input type="hidden" value="POST" name="_method" />';
//       $output .= '      <input id="ResponseEventId" type="hidden" value="' . $email['Event']['id'] . '" name="data[Response][event_id]" />';
//       $output .= '      <input id="ResponsePlayerId" type="hidden" value="' . $player['Player']['id'] . '" name="data[Response][player_id]" />';
//       $output .= '     <div style="font-weight: bold;">';
//       $output .= '       <label for="ResponseRSVP">RSVP</label>';
//       $output .= '     </div>';
//       $output .= '     <div style="margin-left: 20px">';
//       $output .= '        <select id="ResponseResponseTypeId" name="data[Response][response_type_id]">';
//       $i = 0;
//       foreach ($response_types as $response_type)
//       {
//         $typeId = $keys[$i++];
//         $name = $response_type;
//         $output .= '<option value="' . $typeId . '">' . $name . '</option>';
//       }
//       $output .= '        </select>';
//       $output .= '     </div>';
//       $output .= '     <div style="font-weight: bold;">Comment</div>';
//       $output .= '       <div style="margin-left: 20px;">';
//       $output .= '         <div class="input">';
//       $output .= '           <textarea id="ResponseComment" rows="3" cols="50" name="data[Response][comment]"></textarea>';
//       $output .= '         </div>';
//       $output .= '        </div>';
//       $output .= '      <div class="submit">';
//       $output .= '        <input type="submit" value="RSVP" />';
//       $output .= '      </div>';
//       $output .= '   </form>';
//       $output .= '  </div>';
//       $output .= '</fieldset>';
//     }
    return $output;
  }
  
  function email_content($email)
  {
    $output = '';
    $output .= '<fieldset style="margin-top: 0px; margin-bottom: 20px; padding: 16px 0;">';
    $output .= '  <legend style="color: #FF9900; font-size: 120%; font-weight: normal; margin-left: 20px;">Content</legend>';
    $output .= '  <div style="clear: left; margin: 0 10px; background-color: #FFFFFF; padding: 5px; border: 1px solid; text-align: left;">';
    $output .= nl2br($email['Email']['content']);
    $output .= '  </div>';
    $output .= '</fieldset>';
    return $output;
  }
  
  function accountInfo($user, $emails)
  {
    $activated = (isset($user['User']['password']) && (strlen($user['User']['password']) > 0));
    $output = '';
    $output .= '<fieldset style="border: 1px solid #E0E5F0 margin-top: 30px; padding: 16px 20px; background-color: #F6F8FA">';
    $output .= '  <legend style="color: #364E6D; font-size: 160%; font-weight: bold;">Account Info</legend>';
    if (!$activated)
    {
      $output .= '  <h2>Your account has not been activated yet.</h2>';
      $output .= '  <div>';
      $output .= $this->link('Click here to activate your account', '/users/activate/' . $user['User']['email']);
      $output .= '  <br>';
      $output .= 'You don\'t have to activate your account to RSVP. But once you have activated your account, you can login and view your teams message board and upcoming events.';
      $output .= '  </div>';
      $output .= '  <br>';
    }
    $output .= '  <div style="background-color: #FFFFFF; padding: 5px; border: 1px solid; text-align: left;">';
    $output .= '    <div style="font-weight: bold;">Name</div>';
    $output .= '    <div style="margin-left: 20px;">' . $user['User']['nameOrEmail'] . '</div>';
    $output .= '    <div style="font-weight: bold;">Login Email</div>';
    $output .= '    <div style="margin-left: 20px;">' . $user['User']['email'] . '</div>';
    $output .= '    <div style="font-weight: bold;">All Emails</div>';
    $output .= '    <div style="margin-left: 20px;">' . implode('<br>', $emails) . '</div>';
    if ($activated)
    {
      $output .= '  <div style="font-weight: bold;">Account Settings</div>';
      $output .= '  <div style="margin-left: 20px;">' . $this->link('Edit account settings', '/users/edit') . '</div>';
    }
    $output .= '  </div>';
    $output .= '</fieldset>';
    return $output;
  }
  
  function title()
  {
    $output = '';
    $output .= '<div>';
    $output .= '  <h1><a style="font: bold 1em Georgia,sans-serif; color: #567; text-decoration: none;" href="http://' . $this->hostname . '">My EZ Team</a></h1>';
    $output .= '  <div style="font: normal 1em Verdana,sans-serif; color: #999;">Manage your teams with ease</div>';
    $output .= '</div> <!-- end title and description -->';
    return $output;
  }
  
  function head()
  {
    $output = '';
    $output .= '<!DOCTYPE  html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    $output .= '<html xmlns="http://www.w3.org/1999/xhtml">';
    $output .= '  <body>';
    return $output;
  }
  
  function tail()
  {
    $output = '';
    $output .= 'This email was sent on <b>' . date('l, F j, Y g:i:s a') . '</b>';
    $output .= '  </body>';
    $output .= '</html>';
    return $output;
  }
  
  function link($name, $url, $title = null, $style = '')
  {
    if (!$title)
    {
      $title = $name;
    }
    $output = '';
    $output .= '<a href="http://' . $this->hostname . $url . '" title="' . $title . '" ' . $style . '>' . $name . '</a>';
    return $output;
  }
}
?>