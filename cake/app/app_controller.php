<?php
class AppController extends Controller
{
  var $cookieTime;
  var $feedKey = 'feed';
  
  var $uses = array('Error', 'User', 'SettingType');
//  var $helpers = array('MyHtml', 'Html', 'Form', 'Javascript', 'Paging', 'Time');
//  var $components = array('RequestHandler');
  var $helpers = array('Html', 'MyHtml', 'Form', 'Ajax', 'MyAjax', 'Javascript', 'Time', 'Paging');
  var $components = array('RequestHandler', 'AjaxValid');
  
  var $adminEmails = array(
    'junker37@gmail.com',
//    'mcju0003@umn.edu',
//    'jrmcjunk@us.ibm.com'
  );
  
  var $settingMap = array(
    'Daily Email' => 1
  );
  
  var $googleAPIKey = 'ABQIAAAAAIKHeFbHmFUp0pyc4Id1-hSBUhU42oMNgnaoXqRiHdWxYzv05RQrqOcRX-FTfWaGlt1VwdaVv3pPRg';
  var $googleEmail = 'myezteam@gmail.com';
  var $googlePswd = 'mit08rap';
  var $cal = null;
  
  function _getGoogleClient()
  {
    if (!$this->cal)
    {
      try
      {
        $client = Zend_Gdata_ClientLogin::getHttpClient($this->googleEmail, $this->googlePswd, 'cl');
      }
      catch (Zend_Gdata_App_CaptchaRequiredException $cre)
      {
        debug('URL of CAPTCHA image: ' . $cre->getCaptchaUrl());
        debug('Token ID: ' . $cre->getCaptchaToken());
      }
      catch (Zend_Gdata_App_AuthException $ae)
      {
        debug('Problem authenticating: ' . $ae->exception());
      }

      $this->cal = new Zend_Gdata_Calendar($client);
    }
    return $this->cal;
  }
  
  
  function _getSettingid($name)
  {
    return $this->settingMap[$name];
  }
  
  function __construct()
  {
    parent::__construct();
    $cookieTime = time() + strtotime('+2 weeks');
  }
  var $whitelist = array(
   'Users' => array('add', 'login', 'validator', 'login_validator', 'logout', 'activate', 'change_password', 'change_password_validator', 'forgot_password', 'forgot_password_validator'),
   'Emails' => array('cron_job'),
   'Events' => array('cron_job', 'rsvp_status'),
   'Messages' => array('rss', 'index', 'cron_job'),
   //'Players' => array('contacts'),
   'Responses' => array('rsvp', 'email_rsvp'),
   'Pages' => array('display'),
//   'Topics' => array('view', 'posts'),
//   'Posts' => array('view')   
  );
  
  function getUserObject()
  {
    switch ($this->name)
    {
      case 'Users' : return $this->User;
      case 'Posts' :
      case 'Topics' :
      case 'Messages' :
      case 'Players' : return $this->{Inflector::singularize($this->name)}->User;
      case 'Responses' :
        {
          return $this->Response->Player->User;
        }
      case 'Events' :
        {
          return $this->Event->Team->User;
        }
      case 'Teams' :
        {
          return $this->Team->Player->User;
        }
    }
    return null;
  }
  
  function beforeFilter()
  {
    if ($this->isAdminOn())
    {
      Configure::write('debug', 2);
    }
    $isAjax = $this->isAjax();
    $this->set(compact('isAjax'));
    $referer = '/teams';
    if ($isAjax)
    {
      $this->layout = 'ajax';
    }
    else if ($this->isAdminPage())
    {
      $this->layout = 'default';
    }
    else
    {
      $this->layout = 'new';
      $referer = $this->here;
    }
    if ($this->isDevEnv())
    {
      $this->hostname = 'localhost';
    }
    if ($this->Session->valid() && $this->Session->check('User'))
    {
      //$timeZone = $this->Session->read('timeZone');
      $user = $this->getUser();
      if (!isset($user['TimeZone']) || !isset($user['TimeZone']['value']) || !$user['TimeZone']['value'])
      {
        $userObject = $this->getUserObject();
        if ($userObject)
        {
          $userObject->TimeZone->expects();
          $timeZone = $userObject->TimeZone->findById($user['User']['time_zone_id']);
          $user['TimeZone'] = $timeZone['TimeZone'];
          $this->Session->write('User', $user);
          date_default_timezone_set($user['TimeZone']['value']);
        }
      }
      else
      {
        date_default_timezone_set($user['TimeZone']['value']);
      }
    }
    if ($this->name == 'App')
    {
      return;
    }
    if ($this->name == 'Topics')
    {
      $this->Topic->Session = $this->Session;
    }
    else if (isset($this->{Inflector::singularize($this->name)}->Topic))
    {
      $this->{Inflector::singularize($this->name)}->Topic->Session = $this->Session;
    }
    
    if (isset($this->name) && ($this->name) && isset($this->whitelist[$this->name]))
    {
      if ($this->name == 'Pages')
      {
        // don't need to be logged in for pages
        return;
      }
      $actions = $this->whitelist[$this->name];
      if (!is_array($actions))
      {
        $actions = array(0 => $actions);
      }
      foreach ($actions as $action)
      {
        if ($this->action && ($this->action === $action))
        {
          // don't need to be logged in for this action
          return;
        }
      }
    }
    // check if this is an admin action
    $index = strpos($this->action, 'admin_');
    // if the users is trying to get to an admin section, verify they are an admin
    if (is_int($index) && ($index == 0))
    {
      $this->verifyAdminAuthentication();
      // An admin is logged in
      return parent::beforeFilter();
    }
    // make sure the user is logged in
    $this->checkSession($referer);
    return parent::beforeFilter();
  }
  
  function isLoggedIn()
  {
    // If the session info hasn't been set...
    if (!$this->Session->check('User'))
    {
      // If the cookie is set, automatically log them in
      if(isset($_COOKIE['id']))
      {
        $userObject = null;
        if (isset($this->User))
        {
          $userObject = $this->User;
        }
        else if (isset($this->{Inflector::singularize($this->name)}->User))
        {
          $userObject = $this->{Inflector::singularize($this->name)}->User;
        }
        //$userObject->expects();
        $user = $userObject->findById($_COOKIE['id']);
        if (!empty($user))
        {
          $this->Session->write('User', $user);
          return true;
        }
        return false;
      }
      else
      {
        return false;
      }
    }
    return true;
  }
  
  function checkSession($referer = '/teams')
  {
    // If the session info hasn't been set...
//    if (!$this->Session->check('User'))
//    {
//      // If the cookie is set, automatically log them in
//      if(isset($_COOKIE['id']))
//      {
//        $userObject = null;
//        if (isset($this->User))
//        {
//          $userObject = $this->User;
//        }
//        else if (isset($this->{Inflector::singularize($this->name)}->User))
//        {
//          $userObject = $this->{Inflector::singularize($this->name)}->User;
//        }
//        $userObject->expects();
//        $user = $userObject->findById($_COOKIE['id']);
//        if (!empty($user))
//        {
//          $this->Session->write('User', $user);
//          return true;
//        }
//      }
      if ($this->isLoggedIn() || $this->Session->check($this->feedKey))
      {
        return true;
      }
      // Force the user to login
      // store the page they came from
//      $referalUrl = $this->Session->read($this->referalUrlKey);
//      $this->Session->write('loginUrl', $referalUrl);
      $this->Session->write('afterLogin', $referer);
      $this->Session->setFlash('You must login to view the requested page');
      $isAjax = $this->isAjax();
      $this->redirect(array('controller' => 'users', 'action' => 'login', $isAjax));
  }
  
  function verifyAdminAuthentication()
  {
    if (!$this->isAdmin())
    {
//      $this->Session->setFlash('You must be an admin to view the requested page');
//      $this->redirect($this->referer(array('controller' => 'users', 'action' => 'login', 'admin' => false)), null, true);
      $this->invalidPage('/');
    }
  }
  
  //var $hostname = 'www.myeasyteam.com';
 var $hostname = 'myezteam.com';
// var $hostname = 'localhost';
  
  
  function _getHostname()
  {
    return $this->hostname;
  }
  
  function beforeRender()
  {
//    $isAjax = $this->isAjax();
//    $this->set(compact('isAjax'));
//    if ($isAjax)
//    {
//      $this->layout = 'ajax';
//    }
//    else if ($this->isAdminPage())
//    {
//      $this->layout = 'default';
//    }
//    else
//    {
//      $this->layout = 'new';
//    }
    
    $this->set('hostname', $this->hostname);
    
    $isAjax = $this->isAjax();
    $isAdmin = $this->isAdmin();
    $isDevelopment = $isDev = $this->isDevEnv();
    $this->set(compact('isAdmin', 'isAjax', 'isDev', 'isDevelopment', 'hostname'));

    return parent::beforeRender();
  }
  
  function isAjax()
  {
    $isAjax = $this->RequestHandler->isAjax();
    return $isAjax;
  }
  
  function isAdmin()
  {
    $user = $this->getUser();
    if (empty($user))
    {
      return false;
    }
    return (array_search($user['User']['email'], $this->adminEmails) !== false);
  }
  
  function isAdminPage()
  {
    // TODO: is this the best way to find out if it's an admin page -- I doubt it
    $route = Router::currentRoute();
    if (sizeof($route) >=3)
    {
      if (isset($route[3]['admin']) && ($route[3]['admin'] !== false) && ($this->isAdmin() !== false))
      {
        return true;
      }
    }
    return false;
  }
  
  function isLocal()
  {
    $url = Router::url('/', true);
    if (strstr($url, 'http://localhost/') != null)
    {
      return true;
    }
    return false;
  }
  
  function getData($modelObject, $data = null, $otherModel = null)
  {
    $model = $modelObject->name;
    $result = array();
    if ($data)
    {
      if (is_array($data))
      {
        if (!empty($data))
        {
          if (isset($data[$model]))
          {
            $result = $data;
          }
          else if (isset($data[$otherModel]) && isset($data[$otherModel][Inflector::underscore($model) . '_id']))
          {
            $result = $modelObject->findById($data[$otherModel][Inflector::underscore($model) . '_id']);
          }
        }
      }
      else
      {
        $result = $modelObject->findById($data);
      }
    }
    return $result;
  }
  
  function getUserAndData($modelObject, $userId = null, $model = null, $data = null, $otherModel = null)
  {
    $result = array();
    $user = array();
    if (is_array($data) && isset($data['LoggedInUser']))
    {
      $user['User'] = $data['LoggedInUser'];
    }
    else
    {
      $user = $this->getUser($userId);
    }
    if (!empty($user))
    {
      $result['User'] = $user['User'];
    }

    $tempData = $this->getData($modelObject, $data, $otherModel);
    if (!empty($tempData))
    {
      $tempData['LoggedInUser'] = $user['User'];
      $result = $tempData;
    }
    return $result;
  }
  
  function getUser($userId = null, $expects = array())
  {
    $user = array();
    if (!$userId)
    {
      // read session for user
      if ($this->Session->check('User'))
      {
        $user = $this->Session->read('User');
      }
      
      if ($this->Session->check($this->feedKey))
      {
        $user = $this->Session->read($this->feedKey);
      }
    }
    else
    {
      $userObject = $this->_getUserObject();
      $userObject->expects($expects);
      $user = $userObject->findById($userId);
    }
    return $user;
  }
  
  function _getUserObject()
  {
    if (isset($this->User))
    {
      return $this->User;
    }
    else
    {
      return $this->{Inflector::singularize($this->name)}->User;
    }
  }

  function getUserID($userId = null)
  {
    if (!$userId)
    {
      $user = $this->getUser();
      if (empty($user))
      {
        $userId = false;
      }
      else
      {
        $userId = $user['User']['id'];
      }
    }
    return $userId;
  }
  
  function getIP()
  {
//    $ip = $this->RequestHandler->getClientIP();
//    debug($ip);
//    return $ip;
    return $this->RequestHandler->getClientIP();
  }
  
  function getUserAndNotNull($userId = null, $data = null)
  {
    if (!$data)
    {
      return false;
    }
    
    if (is_array($data) && empty($data))
    {
      return false;
    }
    
    $user = $this->getUser($userid);
    if (empty($user))
    {
      return false;
    }
    return $user;
  }
  
  function isCronJob()
  {
    // Check the action is being invoked by the cron dispatcher
    if (!defined('CRON_DISPATCHER') || (CRON_DISPATCHER != 'McJunkinsCronJob'))
    {
      return false;
    }
    return true;
  }
  
  function isTeamCreator($teamObject, $teamId = null, $userId = null, $otherModel = null)
  {
    if (!$teamId)
    {
      return false;
    }
    
    if ($this->isAdminOn())
    {    
      return true;
    }
    
    if (is_array($teamId))
    {
      $teamId = $teamId[$otherModel]['team_id'];
    }
    
    $teamIds = $this->getTeamIdsUserCreated($teamObject, $userId);
    return (array_search($teamId, $teamIds) !== false);
  }
  
  function isTeamManager($teamObject, $teamId = null, $userId = null, $otherModel = null)
  {
    if (!$teamId)
    {
      return false;
    }
    
    if ($this->isAdminOn())
    {    
      return true;
    }
    
    if (is_array($teamId))
    {
      $teamId = $teamId[$otherModel]['team_id'];
    }
    $teamIds = $this->getTeamIdsUserManages($teamObject, $userId);
    $t = (array_search($teamId, $teamIds) !== false);
    //return (array_search($teamId, $teamIds) !== false);
    return $t;
  }
  
  function isTeamMember($teamObject, $teamId = null, $userId = null, $otherModel = null)
  {
    if (!$teamId)
    {
      return false;
    }
    
    if ($this->isAdminOn())
    {    
      return true;
    }
    
    $teamIds = $this->getTeamIds($teamObject, $userId);
    return (array_search($teamId, $teamIds) !== false);
  }

  function isCreator($modelObject, $modelId = null, $userId = null)
  {
    if (!$modelId)
    {
      return false;
    }
    
    if ($modelObject->name == 'Team')
    {
      return $this->isTeamCreator($modelObject, $modelId, $userId);
    }
    else if ($modelObject->name == 'Email')
    {
      $email = $this->getData($modelObject, $modelId);
      return $this->isCreator($modelObject->Event, $email['Email']['event_id'], $userId);
    }
    
    if (is_array($modelId))
    {
      $data = $modelId;
    }
    else
    {
      $data = $this->getData($modelObject, $modelId);
    }
    
    if (!$this->isTeamCreator($modelObject->Team, $modelId, $userId))
    {
      return false;
    }
    
    return $data;
  }
  
  function isManager($modelObject, $modelId = null, $otherModel = null, $userId = null)
  {
    if (!$modelId)
    {
      return false;
    }
    if ($modelObject->name == 'Team')
    {
      return $this->isTeamManager($modelObject, $modelId, $userId);
    }
    else if ($modelObject->name == 'Condition')
    {
      $condition = $this->getData($modelObject, $modelId);
      if (!empty($condition) && !$this->isManager($modelObject->Email, $condition['Condition']['email_id'], $otherModel, $userId))
      {
        return false;
      }
      return $condition;
    }
    else if ($modelObject->name == 'Email')
    {
//      debug($modelObject->name);
//      debug($modelId);
      $email = $this->getData($modelObject, $modelId);
//    debug($email); exit();
      if (!empty($email))
      {
        if ($email['Email']['team_id'])
        {
          if (!$this->isTeamManager($modelObject->Team, $email['Email']['team_id']))
          {
            return false;
          }
        }
        else if (!$this->isManager($modelObject->Event, $email['Email']['event_id'], $otherModel, $userId))
        {
          return false;
        }
      }
      return $email;
    }
    
    if (is_array($modelId))
    {
      $data = $modelId;
    }
    else
    {
      $data = $this->getData($modelObject, $modelId);
    }
    $t = !$this->isTeamManager($modelObject->Team, $data, $userId, Inflector::singularize($modelObject->name));
    //if (!$this->isTeamManager($modelObject->Team, $data, $userId, Inflector::singularize($modelObject->name)));
    if ($t)
    {
      return false;
    }
    
    return $data;
  }
  
  function isMember($modelObject, $modelId = null, $userId = null)
  {
    if (!$modelId)
    {
      return false;
    }
    
    if ($modelObject->name == 'Team')
    {
      return $this->isTeamMember($modelObject, $modelId, $userId);
    }
    else if ($modelObject->name == 'Email')
    {
      $email = $this->getData($modelObject, $modelId);
      if (!$this->isMember($modelObject->Event, $email['Email']['event_id'], $userId))
      {
        return false;
      }
      return $email;
    }
    
    if (is_array($modelId))
    {
      $data = $modelId;
    }
    else
    {
      $data = $this->getData($modelObject, $modelId);
    }

    if (($modelObject->name == 'Topic') && (!isset($data['Topic']['team_id']) || ~$data['Topic']['team_id']))
    {
      // if the topic doesn't have a team, anyone can view it
      return $data;
    }
    if (!$this->isTeamMember($modelObject->Team, $data[$modelObject->name]['team_id'], $userId))
    {
      return false;
    }
    
    return $data;
  }
  
  function isFriendOfUser($userObject, $friendId, $userId = null)
  {
    if (!$friendId)
    {
      return false;
    }
    
    if ($this->isAdminOn())
    {    
      return true;
    }
    
    $friendIds = $this->getFriendIds($userObject, $userId);
    return (array_search($friendId, $friendIds) !== false);
  }
  
  function isAdminOn()
  {
    if ($this->isAdmin())
    {
      return $this->Session->check('adminOn');
    }
    return false;
  }
  
  function fail($msg = 'Invalid id.')
  {
    //if ($this->isAdmin() || $this->isLocal())
    if ($this->isAdmin() || $this->isDevEnv())
    {
      $msg = $msg . ' (' . $this->name = '/' . $this->action . ')';
    }
    if ($this->isAjax())
    {
      $this->render('/errors/invalid', 'ajax');
      exit();
    }
    $this->Session->setFlash($msg);
    $this->redirect('/');
  }
  
  function invalidPage($url = '/', $page = null)
  {
    $this->Session->setFlash('You have requested an invalid page: ' . $page);
    $this->redirect($url);
  }
  
  function verifyNotNull($id = null)
  {
    if (!$id)
    {
      $this->fail();
    }
  }
  
  function verifyFriendOfUser($userObject, $friendId, $userId = null)
  {
    $this->verifyNotNull($friendId);
    if (!$this->isFriendOfUser($userObject, $friendId, $userId))
    {
      $this->fail('You must be a friend of the user to perform the requested action');
    }
  }
  
  function verifyCreator($modelObject, $modelId = null, $userId = null)
  {
    $this->verifyNotNull($modelId);
    $data = $this->isCreator($modelObject, $modelId, $userId);
    if ($data === false)
    {
      $this->fail('You must be a creator of the team to perform the requested action');
    }
    return $data;
  }
  
  function verifyManager($modelObject, $modelId = null, $otherModel = null, $userId = null)
  {
    $this->verifyNotNull($modelId);
    $data = $this->isManager($modelObject, $modelId, $otherModel, $userId);
    if ($data === false)
    {
      $this->fail('You must be a manager of the team to perform the requested action');
    }
    return $data;
  }
  
  function verifyMember($modelObject, $modelId = null, $userId = null)
  {
    $this->verifyNotNull($modelId);
    $data = $this->isMember($modelObject, $modelId, $userId);
    if ($data === false)
    {
      $this->fail('You must be a member of the team to perform the requested action');
    }
    return $data;
  }

  function ownerActions($id = null)
  {
    $model = Inflector::singularize($this->name);
    if (isset($this->params['requested']))
    {
      $edit = false;
      $delete = false;
      if ($id)
      {
        $data = $this->isCreator($this->{$model}, $id);
        $delete = !empty($data);
        $edit = $delete;
        if (!$edit)
        {
          $data = $this->isManager($this->{$model}, $id);
          $edit = !empty($data);
        }
        if ($model != 'Team')
        {
          $delete = $edit;
        }
      }
      return compact('edit', 'delete');
    }

    $this->invalidPage(array('controller' => low($this->name), 'action' => 'index'), $this->here . $id);
  }

  function isDevEnv()
  {
    $ip = $this->getIP();
    return ($ip && ($ip != '') && ($ip == '127.0.0.1'));
  }
  
  function error($function, $internalErrorMessage, $userId = null, $userErrorMessage = null, $redirect = false)
  {
    //if (isset($this->Error))
    {
      $userId = $this->getUserID($userId);
      $error = array('Error' => array('function' => $function, 'message' => $internalErrorMessage, 'user_id' => $userId, 'ip' => $this->getIP()));
      $this->Error->save($error);
      $this->log('Error in ' . $function . ': ' . $internalErrorMessage . '(' . $userId . ')');
    }
    if ($userErrorMessage)
    {
      $this->Session->setFlash($userErrorMessage);
    }
    if ($redirect && !$this->isAjax())
    {
      $this->redirect($redirect);
    }
  }

  var $modelInfo = array(
    'Team' => array(
      'add' => array(
        'tab' => array(
          'teams' => array('prefix' => 'team', 'model' => 'Team', 'key' => 'id', 'suffix' => 'TopicsHolder'),
        )
      ),
      'edit' => array(
        'tab' => array(
          'teams' => array('prefix' => 'team', 'model' => 'Team', 'key' => 'id', 'suffix' => 'TeamsHolder'),
        ),
        'refresh' => array(
          'teams' => array(
            array(
              'update' => array('prefix' => 'team', 'model' => 'Team', 'key' => 'id', 'suffix' => 'TeamsHolder'),
              'url' => array('controller' => 'teams', 'action' => 'view', 'model' => 'Team', 'key' => 'id')
            ),
            array(
              'update' => array('prefix' => 'team', 'model' => 'Team', 'key' => 'id', 'suffix' => 'DropHolder'),
              'url' => array('controller' => 'teams', 'action' => 'drop_down', 'model' => 'Team', 'key' => 'id')
            ),
          )
        )
      ),
      'redirect' => '/teams',
      'title_key' => 'name',
    ),
    'Event' => array(
      'delete' => array(
        'tab' => array(
          'teams' => array('prefix' => 'team', 'model' => 'Event', 'key' => 'team_id', 'suffix' => 'EventsUpcomingHolder'),
          'events' => array('prefix' => 'event', 'model' => 'Event', 'key' => 'id', 'suffix' => 'InfoHolder'),
        ),
        'refresh' => array(
          'teams' => array(
            array(
              'update' => array('prefix' => 'team', 'model' => 'Event', 'key' => 'team_id', 'suffix' => 'EventsUpcomingHolder'),
              'url' => array('controller' => 'teams', 'action' => 'events_upcoming', 'model' => 'Event', 'key' => 'team_id')
            ),
            array(
              'update' => array('prefix' => 'team', 'model' => 'Event', 'key' => 'team_id', 'suffix' => 'DropHolder'),
              'url' => array('controller' => 'teams', 'action' => 'drop_down', 'model' => 'Event', 'key' => 'team_id')
            ),
            array(
              'update' => array('prefix' => 'team', 'model' => 'Event', 'key' => 'team_id', 'suffix' => 'CalendarHolder'),
              'url' => array('controller' => 'teams', 'action' => 'calendar', 'model' => 'Event', 'key' => 'team_id'),
            ),
          )
        )
      ),
      'add' => array(
        'tab' => array(
          'teams' => array('prefix' => 'team', 'model' => 'Event', 'key' => 'team_id', 'suffix' => 'EventsUpcomingHolder'),
          'events' => array('prefix' => 'event', 'model' => 'Event', 'key' => 'id', 'suffix' => 'InfoHolder'),
        ),
        'refresh' => array(
          'teams' => array(
            array(
              'update' => array('prefix' => 'team', 'model' => 'Event', 'key' => 'team_id', 'suffix' => 'EventsUpcomingHolder'),
              'url' => array('controller' => 'teams', 'action' => 'events_upcoming', 'model' => 'Event', 'key' => 'team_id'),
//              'url' => array('controller' => 'teams', 'action' => 'events_upcoming', 'model' => 'Event', 'key' => 'team_id')
            ),
            array(
              'update' => array('prefix' => 'team', 'model' => 'Event', 'key' => 'team_id', 'suffix' => 'DropHolder'),
              'url' => array('controller' => 'teams', 'action' => 'drop_down', 'model' => 'Event', 'key' => 'team_id')
            ),
            array(
              'update' => array('prefix' => 'team', 'model' => 'Event', 'key' => 'team_id', 'suffix' => 'CalendarHolder'),
              'url' => array('controller' => 'teams', 'action' => 'calendar', 'model' => 'Event', 'key' => 'team_id'),
            ),
          )
        )
      ),
      'edit' => array(
        'tab' => array(
          'teams' => array('prefix' => 'team', 'model' => 'Event', 'key' => 'team_id', 'suffix' => 'EventsUpcomingHolder'),
          'events' => array('prefix' => 'event', 'model' => 'Event', 'key' => 'id', 'suffix' => 'InfoHolder'),
        ),
        'refresh' => array(
          'teams' => array(
            array(
              'update' => array('prefix' => 'team', 'model' => 'Event', 'key' => 'team_id', 'suffix' => 'EventsUpcomingHolder'),
//              'url' => array('controller' => 'events', 'action' => 'view', 'model' => 'Event', 'key' => 'id'),
              'url' => array('controller' => 'teams', 'action' => 'events_upcoming', 'model' => 'Event', 'key' => 'team_id'),
            ),
            array(
              'update' => array('prefix' => 'team', 'model' => 'Event', 'key' => 'team_id', 'suffix' => 'DropHolder'),
              'url' => array('controller' => 'teams', 'action' => 'drop_down', 'model' => 'Event', 'key' => 'team_id')
            ),
            array(
              'update' => array('prefix' => 'team', 'model' => 'Event', 'key' => 'team_id', 'suffix' => 'CalendarHolder'),
              'url' => array('controller' => 'teams', 'action' => 'calendar', 'model' => 'Event', 'key' => 'team_id'),
            ),
          ),
          'events' => array(
            array(
              'update' => array('prefix' => 'event', 'model' => 'Event', 'key' => 'id', 'suffix' => 'DropHolder'),
              'url' => array('controller' => 'events', 'action' => 'drop_down', 'model' => 'Event', 'key' => 'id')
            ),
            array(
              'update' => array('prefix' => 'event', 'model' => 'Event', 'key' => 'id', 'suffix' => 'EventsHolder'),
              'url' => array('controller' => 'teams', 'action' => 'events_upcoming', 'model' => 'Event', 'key' => 'team_id'),
//              'url' => array('controller' => 'events', 'action' => 'view', 'model' => 'Event', 'key' => 'id'),
            ),
          )
        )
      ),
      'redirect' => '/teams',
      'title_key' => 'name',
    ),
    'Email' => array(
      'delete' => array(
        'tab' => array('all' => array('prefix' => 'event', 'model' => 'Email', 'key' => 'event_id', 'suffix' => 'EmailsListHolder')),
        'refresh' => array(
          'all' => array(
            array(
              'update' => array('prefix' => 'event', 'model' => 'Email', 'key' => 'event_id', 'suffix' => 'EmailsEmails'),
              'url' => array('controller' => 'events', 'action' => 'emails', 'model' => 'Email', 'key' => 'event_id')
            )
          )
        )
      ),
      'add' => array(
        'tab' => array('all' => array('prefix' => 'event', 'model' => 'Email', 'key' => 'event_id', 'suffix' => 'EmailsListHolder')),
        'refresh' => array(
          'all' => array(
            array(
              'update' => array('prefix' => 'event', 'model' => 'Email', 'key' => 'event_id', 'suffix' => 'EmailsEmails'),
              'url' => array('controller' => 'events', 'action' => 'emails', 'model' => 'Email', 'key' => 'event_id')
            )
          )
        )
      ),
      'edit' => array(
        'tab' => array('all' => array('prefix' => 'event', 'model' => 'Email', 'key' => 'event_id', 'suffix' => 'EmailsListHolder')),
        'refresh' => array(
          'all' => array(
            array(
              'update' => array('prefix' => 'email', 'model' => 'Email', 'key' => 'id', 'suffix' => ''),
              'url' => array('controller' => 'emails', 'action' => 'view', 'model' => 'Email', 'key' => 'id')
            )
          )
        )
      ),
      'redirect' => '/teams',
      'title_key' => 'title',
    ),
    'Player' => array(
      'delete' => array(
        'tab' => array('all' => array('prefix' => 'team', 'model' => 'Player', 'key' => 'team_id', 'suffix' => 'PlayersListHolder')),
        'refresh' => array(
          'all' => array(
            array(
              'update' => array('prefix' => 'team', 'model' => 'Player', 'key' => 'team_id', 'suffix' => 'PlayersPlayers'),
              'url' => array('controller' => 'teams', 'action' => 'players', 'model' => 'Player', 'key' => 'team_id')
            )
          )
        )
      ),
      'add' => array(
        'tab' => array('all' => array('prefix' => 'team', 'model' => 'Player', 'key' => 'team_id', 'suffix' => 'PlayersListHolder')),
        'refresh' => array(
          'all' => array(
            array(
              'update' => array('prefix' => 'team', 'model' => 'Player', 'key' => 'team_id', 'suffix' => 'PlayersPlayers'),
              'url' => array('controller' => 'teams', 'action' => 'players', 'model' => 'Player', 'key' => 'team_id')
            )
          )
        )
      ),
//      'edit' => array(
//        'tab' => array('prefix' => 'team', 'model' => 'Player', 'key' => 'team_id', 'suffix' => 'PlayersListHolder'),
//        'url' => array('controller' => 'teams', 'action' => 'players', 'model' => 'team', 'key' => 'team_id')
//      ),
      'redirect' => '/teams'
    ),
    'Topic' => array(
      'add' => array(
        'tab' => array('all' => array('prefix' => 'team', 'model' => 'Topic', 'key' => 'team_id', 'suffix' => 'TopicsListHolder')),
        'refresh' => array(
          'all' => array(
            array(
              'update' => array('prefix' => 'team', 'model' => 'Topic', 'key' => 'team_id', 'suffix' => 'TopicsTopics'),
              'url' => array('controller' => 'teams', 'action' => 'topics', 'model' => 'Topic', 'key' => 'team_id')
            )
          )
        )
      ),
//      'edit' => array(
//        'tab' => array('prefix' => 'team', 'model' => 'Topic', 'key' => 'id', 'suffix' => 'ListHolder'),
//        'url' => array('controller' => 'topics', 'action' => 'index', 'model' => 'Topic', 'key' => 'team_id')
//      ),
      'redirect' => '/teams'
    ),
    'Post' => array(
      'add' => array(
        'tab' => array('all' => array('prefix' => 'topic', 'model' => 'Post', 'key' => 'topic_id', 'suffix' => '')),
       ),
  //      'edit' => array(
  //        'tab' => array('prefix' => 'team', 'model' => 'Post', 'key' => 'topic_id', 'suffix' => ''),
  //       ),
      'redirect' => '/teams'
    ),
    'User' => array(
      'redirect' => '/'
    ),
    'Response' => array(
      'add' => array(
        'tab' => array('all' => array('prefix' => 'event', 'model' => 'Response', 'key' => 'event_id', 'suffix' => 'InfoHolder')),
        'refresh' => array(
          'all' => array(
            array(
              'update' => array('prefix' => 'event', 'model' => 'Response', 'key' => 'event_id', 'suffix' => 'InfoHolder'),
              'url' => array('controller' => 'events', 'action' => 'info', 'model' => 'Response', 'key' => 'event_id')
            ),
            array(
              'update' => array('prefix' => 'event', 'model' => 'Response', 'key' => 'event_id', 'suffix' => 'RSVPSHolder'),
              'url' => array('controller' => 'players', 'action' => 'responses', 'model' => 'Response', 'key' => 'event_id')
            ),
          )
        )
      ),
      'redirect' => '/'
    ),
    'Condition' => array(
      'add' => array(
        'tab' => array('prefix' => 'email', 'model' => 'Condition', 'key' => 'email_id', 'suffix' => 'InfoHolder'),
        'url' => array('controller' => 'emails', 'action' => 'info', 'model' => 'Condition', 'key' => 'email_id')
      ),
      'redirect' => '/'
    ),
  );
  
  function getDefaultTab($model, $action)
  {
    $base = 'all';
    if (!isset($this->modelInfo[$model][$action]['tab']['all']))
    {
      $base = $this->getBase();
    }
    $tabStuff = $this->modelInfo[$model][$action]['tab'][$base];
    return $tabStuff['prefix'] . $this->data[$tabStuff['model']][$tabStuff['key']] . $tabStuff['suffix'];
  }
  
  function getBase()
  {
    $url = explode('/', $this->referer());
    return $url[1];
  }
  
  function getUrl($urlInfo, $model, $action)
  {
    $param = $this->data[$urlInfo['model']][$urlInfo['key']];
    unset($urlInfo['model']);
    unset($urlInfo['key']);
    array_push($urlInfo, $param);
    return $urlInfo;
  }
  
  function _setFormData()
  {
    $model = Inflector::singularize($this->name);
    $action = $this->action;
    $this->set(compact('model', 'action'));
  }
  
  function getRefreshUpdate($updateInfo, $model, $action)
  {
    return $updateInfo['prefix'] . $this->data[$updateInfo['model']][$updateInfo['key']] . $updateInfo['suffix'];
  }
  
  function _setAjaxUpdateOptions($model, $action, $tab = null, $url = null)
  {
    if (isset($this->modelInfo[$model]) && isset($this->modelInfo[$model][$action]))
    {
      $modelInfo = $this->modelInfo[$model][$action];
      if (isset($modelInfo['refresh']))
      {
        $base = 'all';
        if (!isset($modelInfo['refresh']['all']))
        {
          $base = $this->getBase();
        }
        if (isset($modelInfo['refresh'][$base]))
        {
          $refresh = array();
          foreach ($modelInfo['refresh'][$base] as $refreshInfo)
          {
            $update = $this->getRefreshUpdate($refreshInfo['update'], $model, $action);
            $url = $this->getUrl($refreshInfo['url'], $model, $action);
            $options = compact('update', 'url');
            $refresh[] = $options;
          }
          $this->set(compact('refresh'));
        }
      }
    }
  }
  
  function delete($flash = '')
  {
    if ($this->isAjax())
    {
      $model = Inflector::singularize($this->name);
      $defaultTab = $this->getDefaultTab($model, 'delete');
      $this->set(compact('defaultTab'));
      $this->set('showTab', true);
      $this->set('flash', $flash);
      $this->_setAjaxUpdateOptions($model, 'delete');
    }
  }
  
  function validator()
  {
    if (empty($this->data)) exit();
    if (!$this->isAjax())
    {
      $this->invalidPage('/' . low($this->name) . '/validator');
    }
    $model = Inflector::singularize($this->name);
    $action = 'add';
    if (isset($this->data[$model]['id']))
    {
      $action = 'edit';
    }

    $this->layout = 'ajax';
    $this->AjaxValid->return = 'html';
    $this->AjaxValid->changeClass('errors');
    $defaultTab = null;
    if (isset($this->data[$model]) && ($this->data[$model]['isAjax'] == 1))
    {
      $defaultTab = $this->getDefaultTab($model, $action);
      $this->set(compact('defaultTab'));
      $this->set('showTab', true);
      $this->AjaxValid->setForm($this->data);
    }
    else
    {
      $this->AjaxValid->setForm($this->data, $this->modelInfo[$model]['redirect'], 'redirect');
    }
    $this->set('data', $this->AjaxValid->validate($model));
    $this->set('valid', $this->AjaxValid->valid);

    if ($this->AjaxValid->valid)
    {
      if (!$this->{$model}->save($this->data))
      {
        $this->set('valid', false);
        $this->Session->setFlash('There was a problem saving your ' . $model);
        $this->error('AppController::validator' . $model, 'Could not save data', $this->getUserID());
      }
      else
      {
        if (!isset($this->data[$model]['id']))
        {
          $this->data[$model]['id'] = $this->{$model}->getLastInsertID();
        }
        $this->_setAjaxUpdateOptions($model, $action);
        $flash = $model . ' ';
        if (isset($this->modelInfo[$model]['title_key']))
        {
          $flash .= $this->data[$model][$this->modelInfo[$model]['title_key']];
        }
        $flash .= ' has been saved.';
        if ($this->data[$model]['isAjax'] == 1)
        {
          $this->set(compact('flash'));
        }
        else
        {
          $this->Session->setFlash($flash);
        }
      }
    }
  }

  function sanitize(&$arrayToClean)
  {
    uses('sanitize');
    $mrClean = new Sanitize();
    $arrayToClean = $mrClean->clean($arrayToClean);
  }
  
  function findUsersTeams($teamObject, $expects = array(), $userId = null)
  {
    $teams = array();
    $teamIds = $this->getTeamIds($teamObject, $userId, true);
    if (!empty($teamIds))
    {
//      $condition = 'Team.id IN (' . implode(', ', $teamIds) . ')';
//      $teamObject->expects($expects);
//      $teams = $teamObject->findAll($condition);
      foreach ($teamIds as $teamId)
      {
        $teamObject->expects();
        $teams[] = $teamObject->find(array('Team.id' => $teamId));
      }
    }
    return $teams;
  }
  
  function findUsersTeamsTheyManage($teamObject, $expects = array(), $userId = null)
  {
    $teams = array();
    $teamIds = $this->getTeamIdsUserManages($teamObject, $userId);
    if (!empty($teamIds))
    {
      $condition = 'Team.id IN (' . implode(', ', $teamIds) . ')';
      $teamObject->expects($expects);
      $teams = $teamObject->findAll($condition);
    }
    return $teams;
  }
  
  function getTeamIdsUserCreated($teamObject, $userId = null)
  {
    $userId = $this->getUserID($userId);
    $teamIds = array();
    if ($userId !== false)
    {
      $query = 'SELECT Team.id FROM teams AS Team WHERE Team.user_id=' . $userId;
      $result = $teamObject->query($query);
      if (!empty($result))
      {
        $teamIds = Set::extract($result, '{n}.Team.id');
      }
    }
    return $teamIds;
  }
  
  var $teamIds = null;
  
  function getTeamIdsUserManages($teamObject, $userId = null)
  {
    $userId = $this->getUserID($userId);
    if (isset($teamIds[$userId]))
    {
      return $teamIds[$userId];
    }
    $teamIds = array();
    if ($userId !== false)
    {
      $query = 'SELECT Team.id FROM teams AS Team LEFT JOIN teams_users AS TeamsUser ON';
      $query .= ' TeamsUser.team_id=Team.id WHERE';
      $query .= ' Team.user_id=' . $userId . ' OR TeamsUser.user_id=' . $userId;
      $query .= ' GROUP BY id';
      $result = $teamObject->query($query);
      if (!empty($result))
      {
        $teamIds = Set::extract($result, '{n}.Team.id');
      }
      $this->teamIds[$userId] = $teamIds;
    }
    return $teamIds;
  }
  
  function getFriendIds($userObject, $userId = null)
  {
    $userId = $this->getUserID($userId);
    $friendIds = array();
    if ($userId !== false)
    {
      $query = 'SELECT User.id FROM users AS User LEFT JOIN users_users AS MyFriend ON MyFriend.contact_id=User.id';
      $query .= ' LEFT JOIN users_users AS Friend ON Friend.user_id=User.id LEFT JOIN players AS Player ON Player.user_id=User.id';
      $query .= ' LEFT JOIN teams AS Team ON Team.id=Player.team_id WHERE Friend.contact_id=' . $userId. ' OR MyFriend.user_id=' . $userId;
      $teamIds = $this->getTeamIds($userObject->Team, $userId);
      if (!empty($teamIds))
      {
        $query .= ' OR Team.id IN (' . implode(', ', $teamIds) . ')';
      }
      $query .= ' GROUP BY User.id';
      $result = $this->{Inflector::singularize($this->name)}->query($query);
      if (!empty($result))
      {
        $friendIds = Set::extract($result, '{n}.User.id');
      }
    }
    return $friendIds;
  }
  

  function getTeamIds($teamObject, $userId = null, $order = false)
  {
    if (!$order && $this->teamIds)
    {
      return $this->teamIds;
    }
    $userId = $this->getUserID($userId);
    $teamIds = array();
    if ($userId !== false)
    {
      if (!$order)
      {
        $query = 'SELECT Team.id FROM teams AS Team LEFT JOIN teams_users AS TeamsUser ON';
        $query .= ' TeamsUser.team_id=Team.id LEFT JOIN players AS Player ON Player.team_id=Team.id WHERE';
        $query .= ' Team.user_id=' . $userId . ' OR TeamsUser.user_id=' . $userId . ' OR Player.user_id=' . $userId;
        $query .= ' GROUP BY id';
        $result = $teamObject->query($query);
        if (!empty($result))
        {
          $teamIds = Set::extract($result, '{n}.Team.id');
        }
      }
      else
      {
        $query = 'SELECT DISTINCT Team.id FROM users AS User LEFT JOIN players AS Player ON Player.user_id=User.id';
        $query .= ' LEFT JOIN teams_users AS Manager ON Manager.user_id=User.id';
        $query .= ' JOIN teams AS Team ON (Team.id=Player.team_id OR Team.id=Manager.team_id OR Team.user_id=User.id)';
        $query .= ' LEFT JOIN events AS Event ON Event.team_id=Team.id';
        $query .= ' JOIN (SELECT DISTINCT id, team_id FROM events WHERE start > "' . date('Y-m-d H:i:s') . '" ORDER BY start) E ON E.team_id=Event.team_id';
        $query .= ' WHERE User.id=' . $userId;
        $result = $teamObject->query($query);
        $condition = '';
        if (!empty($result))
        {
          $teamIds = Set::extract($result, '{n}.Team.id');
          $condition = ' AND Team.id NOT IN (' . implode(', ', $teamIds) . ')';
        }
        // now get teamIds for non-upcoming events
        $query = 'SELECT Team.id FROM users AS User LEFT JOIN players AS Player ON Player.user_id=User.id';
        $query .= ' LEFT JOIN teams_users AS Manager ON Manager.user_id=User.id';
        $query .= ' JOIN teams AS Team ON (Team.id=Player.team_id OR Team.id=Manager.team_id OR Team.user_id=User.id)';
        $query .= ' WHERE User.id=' . $userId;
        $query .= $condition;
        $query .= ' GROUP BY Team.id';
        $result = $teamObject->query($query);
        if (!empty($result))
        {
          $teamIds2 = Set::extract($result, '{n}.Team.id');
          $teamIds = array_merge($teamIds, $teamIds2);
        }
      }
    }
    $this->teamIds = $teamIds;
    return $teamIds;
  }
  
  function userIsActivated($user, $default = false)
  {
    if (!empty($user))
    {
      return (isset($user['User']['password']) && (strlen($user['User']['password']) > 0));
    }
    return $default;
  }
  
  function userIdActivated($userId = null, $default = false)
  {
    if ($userId)
    {
      $this->User->expects();
      return $this->userIsActivated($this->User->findById($userId), $default);
    }
    return $default;
  }
  
  function userEmailActivated($email = null, $default = false)
  {
    if ($email)
    {
      $this->User->expects();
      return $this->userIsActivated($this->User->findByEmail($email), $default);
    }
    return $default;
  }


  var $responseKeySalt = 'ResponseKeySalt';
  function generateResponseKey($eventId = null, $playerId = null)
  {
    $this->verifyNotNull($eventId);
    $this->verifyNotNull($playerId);
    
    $responseKey = md5($eventId . $this->responseKeySalt . $playerId);
    return $responseKey;
  }

  function actions()
  {
    return array();
  }

  function _getEmails($userId = null)
  {
    if (!is_array($userId))
    {
      $user = $this->getUser($userId);
    }
    else
    {
      $user = $userId;
    }
    $emails = array();
    $userObject = $this->getUserObject();
    if ($userObject)
    {
      $userEmails = $userObject->UserEmail->findAllByUserId($user['User']['id']);
      if (!empty($userEmails))
      {
        $emails = Set::extract($userEmails, '{n}.UserEmail.email');
      }
    }
    else
    {
      $this->error('App::_getEmails', 'Could not get user object: ' . $this->name);
    }
    return $emails;
  }

  function _getSettingValue($settingTypeId, $userId = null)
  {
    if (!is_array($userId))
    {
      $user = $this->getUser($userId, array('UserSetting'));
    }
    else
    {
      $user = $userId;
    }
    $settingType = $this->SettingType->findById($settingTypeId);
    $settings = array();
    if (!empty($user['UserSetting']))
    {
      $settings = Set::combine($user['UserSetting'], '{n}.setting_type_id', '{n}.value');
    }
    if (isset($settings[$settingTypeId]))
    {
      return $settings[$settingTypeId]['value'];
    }
    else
    {
      // use default value
      return $settingType['SettingType']['default'];
    }
  }

  function _getRSVPS($eventObject, $eventId = null)
  {
    $this->Event = $eventObject;
    $this->Event->recursive = 0;
    $this->Event->expects('ResponseType');
    if (!$this->isCronJob())
    {
      $event = $this->verifyMember($this->Event, $eventId);
    }
    else
    {
      $event = $this->Event->findById($eventId);
    }
    $query = 'SELECT ResponseType.name, Player.id FROM response_types AS ResponseType';
    $query .= ' JOIN responses AS Response ON ResponseType.id=Response.response_type_id';
    $query .= ' JOIN players AS Player ON Response.player_id=Player.id JOIN player_types AS PlayerType ON PlayerType.id=Player.player_type_id';
    $query .= ' INNER JOIN (SELECT MAX(id) AS id FROM responses WHERE event_id=' . $eventId  . ' GROUP BY player_id) Responses';
    $query .= ' ON Response.id=Responses.id';
    $query .= ' WHERE PlayerType.name!="member"';
    $results = $this->Event->query($query);
    $rsvps = array();
    $playerIds = array();
    if (!empty($results))
    {
      $rsvps = Set::extract($results, '{n}.ResponseType.name');
      $playerIds = Set::extract($results, '{n}.Player.id');
    }
    $responses = array_count_values($rsvps);
    // get # of people who haven't responded, don't include members or subs
    $playerTypesNotInDefault = array('"member"', '"sub"');
    $conditions = ' WHERE Event.id=' . $eventId;
    if (!empty($playerIds))
    {
      $conditions .= ' AND Player.id NOT IN (' . implode(', ', $playerIds) . ')';
    }
    $conditions .= ' AND PlayerType.name NOT IN (' . implode(', ', $playerTypesNotInDefault) . ')';
    
    // get default responses
    $default = $event['ResponseType']['name'];
    $query = 'SELECT COUNT(*) AS COUNT FROM players AS Player LEFT JOIN teams AS Team ON Team.id=Player.team_id';
    $query .= ' LEFT JOIN player_types AS PlayerType ON PlayerType.id=Player.player_type_id';
    $query .= ' LEFT JOIN events AS Event ON Event.team_id=Team.id' . $conditions;
    $result = $this->Event->query($query);
    if (!isset($responses[$default]))
    {
      $responses[$default] = 0;
    }
    $responses[$default] += $result[0][0]['COUNT'];
    
    $conditions = 'TRUE';
    if ($default != 'no_response')
    {
      $conditions = 'ResponseType.name!="no_response"';
    }
    $responseTypes = $this->Event->Response->ResponseType->findAll($conditions);
    $responseTypes = array_values($responseTypes);
    $data['responses'] = $responses;
    $data['default'] = $default;
    $data['responseTypes'] = $responseTypes;    
    return $data;
  }
}
?>