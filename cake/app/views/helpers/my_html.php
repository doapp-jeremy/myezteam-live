<?php  // views/helpers/my_html.php
class MyHtmlHelper extends AppHelper 
{
  var $name = 'MyHtml';
  var $helpers = array('Html', 'Session', 'MyAjax', 'Ajax');
  var $responseTypes = array('yes', 'probable', 'maybe', 'no');

  var $actions = array(
    'Team' => array(
      'title' => 'name',
      'ajaxDelete' => false,
      'Teams' => array('title' => 'name', 'url' => array('controller' => 'teams', 'action' => 'view'), 'linkClass' => ''),
      'Info' => array('title' => 'Info', 'refresh' => true, 'url' => array('controller' => 'teams', 'action' => 'info')),
      'Events' => array('title' => 'Events', 'url' => array('controller' => 'teams', 'action' => 'events')),
      'Calendar' => array('title' => 'Calendar', 'refresh' => true, 'url' => array('controller' => 'teams', 'action' => 'calendar')),
      'Topics' => array('title' => 'Msg Board', 'url' => array('controller' => 'teams', 'action' => 'topics')),
      'Players' => array('title' => 'Players', 'refresh' => true, 'url' => array('controller' => 'teams', 'action' => 'players')),
      'DefaultEmails' => array('title' => 'Default Emails', 'refresh' => true, 'url' => array('controller' => 'teams', 'action' => 'default_emails')),
      'Edit' => array('title' => 'edit', 'url' => array('controller' => 'teams', 'action' => 'edit')),
      'Delete' => array('title' => 'delete', 'url' => array('controller' => 'teams', 'action' => 'delete')),
    ),
    'Event' => array(
      'title' => 'name',
      'ajaxDelete' => true,
      'Events' => array('title' => 'name', 'url' => array('controller' => 'events', 'action' => 'view'), 'linkClass' => ''),
      'Info' => array('title' => 'Info', 'refresh' => true, 'url' => array('controller' => 'events', 'action' => 'info')),
      'Upcoming' => array('title' => 'Upcoming Events', 'refresh' => true, 'url' => array('controller' => 'teams', 'action' => 'events_upcoming'), 'params' => array('team_id')),
      'AddEvent' => array('title' => 'Add Event', 'convert' => false, 'url' => array('controller' => 'events', 'action' => 'add'), 'params' => array('team_id')),
      'Past' => array('title' => 'Past Events', 'refresh' => true, 'url' => array('controller' => 'teams', 'action' => 'events_past'), 'params' => array('team_id')),
      'Edit' => array('title' => 'edit', 'url' => array('controller' => 'events', 'action' => 'edit')),
      'Delete' => array('title' => 'delete', 'url' => array('controller' => 'events', 'action' => 'delete')),
      'Emails' => array('title' => 'Emails', 'url' => array('controller' => 'events', 'action' => 'emails')),
      'RSVP' => array('convert' => false, 'url' => array('controller' => 'responses', 'action' => 'add'), 'params' => array('event_id', 'player_id')),
      'RSVPS' => array('title' => 'RSVPs', 'refresh' => true, 'url' => array('controller' => 'players', 'action' => 'responses')),
    ),
    'Email' => array(
      'title' => 'title',
      'ajaxDelete' => true,
      'Emails' => array('title' => 'title', 'url' => array('controller' => 'emails', 'action' => 'view'), 'linkClass' => ''),
      'Info' => array('title' => 'Info', 'refresh' => 'true', 'url' => array('controller' => 'emails', 'action' => 'info')),
      'Send' => array('title' => 'Send', 'refresh' => true, 'convert' => false, 'url' => array('controller' => 'emails', 'action' => 'send')),
      'List' => array('title' => 'List', 'refresh' => true, 'url' => array('controller' => 'emails', 'action' => 'index'), 'params' => array('event_id')),
      'AddEmail' => array('title' => 'Add Email', 'convert' => false, 'url' => array('controller' => 'emails', 'action' => 'add'), 'params' => array('event_id')),
      'AddCondition' => array('title' => 'Add Condition', 'convert' => false, 'url' => array('controller' => 'conditions', 'action' => 'add')),
      'MakeDefault' => array('title' => 'Add as Default', 'convert' => false, 'url' => array('controller' => 'emails', 'action' => 'make_default')),
      'Edit' => array('title' => 'edit', 'url' => array('controller' => 'emails', 'action' => 'edit')),
      'Delete' => array('title' => 'delete', 'url' => array('controller' => 'emails', 'action' => 'delete')),
      'AddDefaultEmails' => array('title' => 'Restore Default Emails', 'convert' => false, 
        'url' => array('controller' => 'events', 'action' => 'add_default_emails'), 'params' => array('event_id'),
        'confirm' => 'Are you sure want to reset this events emails to the defaults for the team?  Doing so will delete any emails currently assigned to this event.'
      ),
    ),
    'Player' => array(
      'title' => 'type',
      'ajaxDelete' => true,
      'Players' => array('title' => 'type', 'url' => array('controller' => 'players', 'action' => 'view'), 'linkClass' => ''),
      'List' => array('title' => 'List', 'refresh' => true, 'url' => array('controller' => 'players', 'action' => 'index'), 'params' => array('team_id')),
      'AddPlayer' => array('title' => 'Add Player', 'convert' => false, 'url' => array('controller' => 'players', 'action' => 'add'), 'params' => array('team_id')),
      'EditPlayers' => array('title' => 'Edit Players', 'convert' => false, 'url' => array('controller' => 'teams', 'action' => 'edit_players'), 'params' => array('team_id')),
      'Edit' => array('title' => 'edit', 'url' => array('controller' => 'players', 'action' => 'edit')),
      'Delete' => array('title' => 'delete', 'url' => array('controller' => 'players', 'action' => 'delete')),
    ),
    'Topic' => array(
      'title' => 'title',
      'ajaxDelete' => false,
      'Players' => array('title' => 'title', 'url' => array('controller' => 'topics', 'action' => 'view'), 'linkClass' => ''),
      'List' => array('title' => 'List', 'refresh' => true, 'url' => array('controller' => 'topics', 'action' => 'index'), 'params' => array('team_id')),
      'AddTopic' => array('title' => 'Add Topic', 'convert' => false, 'url' => array('controller' => 'topics', 'action' => 'add'), 'params' => array('team_id')),
      'Edit' => array('title' => 'edit', 'url' => array('controller' => 'players', 'action' => 'edit')),
      'Delete' => array('title' => 'delete', 'url' => array('controller' => 'players', 'action' => 'delete')),
    ),
  );
  
  function titleAddOn($model, $data, $divId = null)
  {
    return $this->output('');
  }
  
  function title($model, $data, $divId = null, $edit = false, $delete = false)
  {
    $title = '<span class="' . low($model) . 'TitleText">';
    $dataTitle = '--I could not figure it out, please contact admin@myezteam.com if you see this message--';
    $modelId = $this->modelId($model, null, $data);
    if ($modelId)
    {
      $url = array(
        'controller' => Inflector::pluralize(low($model)),
        'action' => 'view',
        $modelId
      );
      $dataTitle = $data[$model][$this->actions[$model]['title']];
      $title .= $this->Html->link($dataTitle, $url);
    }
    else if (isset($data[$model][$this->actions[$model]['title']]))
    {
      $dataTitle = $data[$model][$this->actions[$model]['title']];
      $title .= $dataTitle;
    }
    else
    {
      return;
    }
    $title .= '</span>';

    if ($divId || ($edit === true) || ($delete === true))
    {
      $title .= '<span class="titleActions reallySmall">';
    }

    if ($modelId && ($edit === true))
    {
      $controller = Inflector::pluralize(low($model));
      $title .= '<span class="edit">';
      $title .= $this->edit($model, $modelId, $data);
      $title .= '</span>';
    }
    
    if ($modelId && ($delete === true))
    {
      if ($edit === true)
      {
        $title .= ' | ';
      }
      $title .= '<span class="delete">';
      $msg = 'Are you sure you want to delete ' . low($model) . ' ' . $dataTitle . '?';
      if ($this->actions[$model]['ajaxDelete'])
      {
        $title .= $this->delete($model, $modelId, $data, $msg);
      }
      else
      {
        $title .= $this->Html->link('delete', array('controller' => $controller, 'action' => 'delete', $modelId), null, $msg);
      }
      $title .= '</span>';
    }

    if ($divId || ($edit === true) || ($delete === true))
    {
      $title .= '</span>';
    }
    
    return $title;
  }
  
  function edit($model, $modelId = null, $data = null)
  {
    $modelId = $this->modelId($model, $modelId, $data);
    $title = $this->MyAjax->link($model, 'Edit', $modelId);
    return $title;
  }
  
  function delete($model, $modelId = null, $data = null, $confirm = null)
  {
    $modelId = $this->modelId($model, $modelId, $data);
    //$title = $this->MyAjax->link($model, 'Edit', $modelId);
    $controller = Inflector::pluralize(low($model));
    $options = array(
      'loading' => 'document.getElementById("loader").innerHTML="deleting....";',
      'update' => 'mainFlash'
    );
    $title = $this->Ajax->link('delete', array('controller' => $controller, 'action' => 'delete', $modelId), $options, $confirm);
    return $title;
  }
  
  function action($model, $action)
  {
    return $this->actions[$model][$action];
  }
  
  function actionValue($model, $action, $field, $default = null)
  {
    $actionArray = $this->action($model, $action);
    if (isset($actionArray[$field]))
    {
      return $actionArray[$field];
    }
    return $default;
  }
  
  function actionConvert($model, $action)
  {
    return $this->actionValue($model, $action, 'convert', null);
  }
  
  function actionTitle($model, $action, $data = null)
  {
    $actionArray = $this->action($model, $action);
    $title = $this->actionValue($model, $action, 'title');
    if ($title == null)
    {
      $title = $action;
    }
    else if ($data && !empty($data) && isset($data[$model][$title]))
    {
      return $data[$model][$title];
    }
    return $title;
  }
  
  function actionUrl($model, $action, $modelId = null, $data = null, $params = null)
  {
    $url = $this->actionValue($model, $action, 'url');
    $modelId = $this->modelId($model, $modelId, $data);
    $actionArray = $this->action($model, $action);
    if ($params && isset($actionArray['params']))
    {
      foreach ($actionArray['params'] as $param)
      {
        if (isset($params[$param]))
        {
          array_push($url, $params[$param]);
        }
      }
    }
    else if ($modelId)
    {
      array_push($url, $modelId);
    }
    return $url;
  }
  
  function modelId($model, $modelId = null, $data = null)
  {
    if (!$modelId && $data && isset($data[$model]) && isset($data[$model]['id']))
    {
      $modelId = $data[$model]['id'];
    }
    return $modelId;    
  }
  
  function linkClass($model, $action)
  {
    return $this->actionValue($model, $action, 'linkClass', 'currentAction');
  }
  
  function confirm($model, $action)
  {
    return $this->actionValue($model, $action, 'confirm', null);
  }
  
  function baseDivId($model, $modelId = null, $data = null)
  {
    $modelId = $this->modelId($model, $modelId, $data);
    if ($modelId)
    {
      return low($model) . $modelId;
    }
    else if ($model == 'Event')
    {
      if (isset($data['Team']) && isset($data['Team']['id']))
      {
        return 'team' . $data['Team']['id'] . 'Events';
      }
    }
    else if ($model == 'Topic')
    {
      if (isset($data['Team']) && isset($data['Team']['id']))
      {
        return 'team' . $data['Team']['id'] . 'Topics';
      }
    }
    else if ($model == 'Player')
    {
      if (isset($data['Team']) && isset($data['Team']['id']))
      {
        return 'team' . $data['Team']['id'] . 'Players';
      }
    }
    else if ($model == 'Email')
    {
      if (isset($data['Event']) && isset($data['Event']['id']))
      {
        return 'event' . $data['Event']['id'] . 'Emails';
      }
    }
    return low($model) . rand() . $modelId;
  }
  
  function mainDivId($model)
  {
    return low($model) . 'Main';
  }
  
  function viewDivId($model, $modelId = null, $data = null)
  {
    return $this->baseDivId($model, $modelId, $data) . Inflector::pluralize($model);
  }
  
  function tabsDivId($model, $modelId = null, $data = null)
  {
    //return low($model) . $modelId . 'Tabs';
    return $this->baseDivId($model, $modelId, $data) . 'Tabs';
  }
  
  function actionsDivId($model, $modelId = null, $data = null)
  {
    return $this->baseDivId($model, $modelId, $data) . 'Actions';
  }
  
  function actionId($model, $action, $modelId = null, $data = null)
  {
    //return $this->actionDivId($model, $action, $modelId, $data) . 'HolderAction';
    return $this->actionDivId($model, $action, $modelId, $data) . 'Action';
  }
  
  function refreshId($model, $action, $modelId = null, $data = null)
  {
    return $this->actionId($model, $action, $modelId, $data) . 'Refresh';
  }
    
  function actionDivId($model, $action, $modelId = null, $data = null)
  {
    return $this->baseDivId($model, $modelId, $data) . $action . 'Holder';
  }
  
  function actionHolderDivId($model, $action, $modelId = null, $data = null)
  {
    return $this->actionDivId($model, $action, $modelId, $data);
  }
  
  function linkId($model, $action, $modelId = null, $data = null)
  {
    return $this->divId($model, $action, $modelId, $data) . 'Action';
  }
  
  function showDivAction($model, $action, $modelId = null, $data = null)
  {
    $title = $this->actionTitle($model, $action, $data);
    //$divId = $this->actionDivId($model, $action, $modelId, $data);
    $divId = $this->actionHolderDivId($model, $action, $modelId, $data);
    $linkClass = $this->linkClass($model, $action);
    return $this->Html->link($title, 'javascript:void(0)', array('title' => $title, 'onclick' => 'showTab("' . $divId . '", "' . $linkClass . '")'));
  }
}

?>