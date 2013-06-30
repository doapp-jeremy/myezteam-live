<?php
class UsersController extends AppController
{
	var $name = 'Users';
	var $components = array('AjaxValid', 'Mailer');
	var $uses = array('User', 'SettingType');
	
	function on()
	{
	  if ($this->isAdmin())
	  {
	    $this->Session->write('adminOn', true);
	    $this->Session->setFlash('Admin mode has been turned on.');
	    $this->redirect($this->referer('/'));
	  }
	  $this->invalidPage('/', '/users/admin_on');
	}
	
  function off()
  {
    $this->Session->delete('adminOn');
    if ($this->isAdmin())
    {
      $this->Session->setFlash('Admin mode has been turned off.');
      $this->redirect($this->referer('/'));
    }
    $this->invalidPage('/', '/users/admin_on');
  }
  
	function index()
	{
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

	function view($userId = null) 
	{
	  $this->verifyFriendOfUser($this->User, $userId);
	  $this->User->expects(array('UserEmail', 'UserIp', 'TimeZone'));
	  $user = $this->User->findById($userId);
    $this->User->Player->expects('Team');
	  $user['Player'] = $this->User->Player->findAllByUserId($userId);
    $isManager = false;
	  if (!empty($user['Player']) && !$this->userIsActivated($user))
	  {
	    $teamIds = Set::extract($user['Player'], '{n}.Team.id');
	    foreach ($teamIds as $teamId)
	    {
	      if ($this->isTeamManager($this->User->Team, $teamId))
	      {
	        $isManager = true;
	        break;
	      }
	    }
	  }
	  $teamIds = $this->getTeamIdsUserManages($this->User->Team);
    $this->set(compact('user', 'isManager', 'teamIds'));
	}
	
	function info($userId = null)
	{
    if (!$this->isAjax())
    {
      $this->redirect(array('controller' => 'users', 'action' => 'view', $userId));
    }
    $this->User->expects();
    //$user = $this->verifyFriendOfUser($teamId);
		$user = $this->getUser($userId);
    if (isset($this->params['requested']))
    {
      return $user;
    }
    $this->set(compact('user'));
	}

	function add()
	{
	  $this->_setFormData();
	}

	function _setFormData()
	{
	  $association['name'] = 'time_zones';
	  $this->User->TimeZone->expects();
	  $time_zones = $this->User->TimeZone->find('list');
	  $this->set(compact('association', 'time_zones'));
	  return parent::_setFormData();
	}
	
	function edit($userId = null)
  {
    if ($userId && ($userId != $this->getUserID()))
    {
      if (!$this->isAdminOn())
      {
        if ($this->isFriendOfUser($this->User, $userId))
        {
          if ($this->userIdActivated($userId, true))
          {
            $this->Session->setFlash('User has been activated. You can no longer edit the account.');
            $this->redirect(array('controller' => 'users', 'action' => 'view', $userId));
          }
        }
        else
        {
          $this->Session->setFlash('You are not authorized to access the requested page.');
          $this->redirect('/');
        }
      }
    }
    else
    {
      $userId = $this->getUserID();
    }
    $this->User->expects('UserEmail', 'UserSetting');
    $user = $this->User->findById($userId);
    $this->data = $user;
    $this->_setFormData();
    
    $settingTypes = $this->SettingType->findAll();
//    $settings = array();
//    if (!empty($user['UserSetting']))
//    {
//      $settings = Set::combine($user['UserSetting'], '{n}.setting_type_id', '{n}.value');
//    }
    foreach ($settingTypes as $settingType)
    {
      $id = $settingType['SettingType']['id'];
      $this->data['Settings'][$id] = $this->_getSettingValue($id, $user);
//      if (isset($settings[$id]))
//      {
//        $this->data['Settings'][$id] = $settings[$id]['value'];
//      }
//      else
//      {
//        // use default value
//        $this->data['Settings'][$id] = $settingType['SettingType']['default'];
//      }
    }
    $this->set(compact('settingTypes'));
  }

	function login($isAjax = false)
	{
	  if ($isAjax)
	  {
	    $this->layout = '';
	  }
	  // destroy previous session
	  if ($this->Session->check('User'))
	  {
	    $user = $this->Session->read('User');
	    $this->Session->delete('User');
	    $this->Session->delete('teams');
	    $this->Session->destroy();	    
      $this->Session->setFlash($user['User']['nameOrEmail'] . ' has been logged out.  Please login again.');
	  }
	}
	
	function logout()
	{
    // Unset the cookies
    $time = time() - 60*60*24*70;
    if ($this->isDevEnv())
    {
      setcookie("id","", $time, "/");
    }
    else
    {
      // had to remove trailing slash for production server, not sure why
      setcookie("id", "", $time, "/");
    }

    $this->Session->delete('User');
	  //$this->Session->delete('timeZone');
	  $this->Session->delete('adminOn');
	  $this->Session->destroy();
	  $this->Session->setFlash('You are logged out.');
	  $this->redirect('/');
	}
	
	function activate($email = null)
	{
	  if ($email)
	  {
	    $this->User->expects();
	    $user = $this->User->findByEmail($email);
	    if (!empty($user))
	    {
	      if ($this->userIsActivated($user, false))
	      {
	        $this->Session->setFlash('Your account has already been activated.  If you forgot your password, click on Send Email to send an email with a link for you to change your password.');
	        $this->redirect(array('controller' => 'users', 'action' => 'forgot_password', $email));
	      }
	      else
	      {
	        $user = $this->__createChangePasswordKey($user);
          if ($user && $this->__sendEmail($user, 'Activate Account', 'Here is a link to activate your account.'))
          {
            $this->Session->setFlash('An email has been sent to ' . $email . '.  Click on the link in the email to set your password.');
          }
          else
          {
            $this->Session->setFlash('There was an error trying to send an email to ' . $email);
          }
	      }
	    }
	  }
	  else
	  {
	    $this->invalidPage(array('controller' => 'users', 'action' => 'forgot_password'), 'You have requested an invalid page.');
	  }
	  $this->redirect('/');
	}
	
	function validator()
	{
	  if (!isset($this->data['User']['id']))
	  {
	    $this->AjaxValid->setForm($this->data);
	    $this->AjaxValid->unique(array(array('User.email')));
	    if (!empty($this->AjaxValid->errors))
	    {
	      // check if the user has been activated
	      if (!$this->userEmailActivated($this->data['User']['email']))
	      {
	        $this->layout = '';
	        $this->AjaxValid->return = 'html';
	        $this->AjaxValid->changeClass('errors');

	        $error = 'An account has already been created for ' . $this->data['User']['email'] . ' but it has not been activated yet.';
	        $error .= '<br><a href="/users/activate/' . $this->data['User']['email'] . '">Activate your account now.</a>';

	        $this->AjaxValid->errors['User']['email'] = $error;
	        $this->set('data', $this->AjaxValid->validate());
	        $this->set('valid', false);
	        return;
	      }
	      else
	      {
	        $error = ' <a href="/users/activate/' . $this->data['User']['email'] . '">Click here if you forgot your password.</a>';
	        $this->AjaxValid->errors['User']['email'] .= $error;
	      }
	    }
	  }
	  else
	  {
      $user_id = $this->data['User']['id'];
	    // remove all current settings
	    if (!$this->User->UserSetting->deleteAll(array('UserSetting.user_id' => $user_id)))
	    {
        $this->error('Users::validator', 'Could not delete User Settings for user: '. $user_id, $user_id);
	    }
	    if (isset($this->data['Settings']))
	    {
  	    foreach ($this->data['Settings'] as $setting_type_id => $value)
  	    {
  	      $userSetting = array('UserSetting' => compact('user_id', 'setting_type_id', 'value'));
  	      if (!$this->User->UserSetting->save($userSetting))
  	      {
  	        $this->error('Users::validator', 'Could not create User Setting: '. $setting_type_id, $user_id);
  	      }
  	    }
	    }
	  }
    parent::validator();
    
    // re-login user if user edited their own profile or a new users registered
    if (($this->getUserID() === false) || (isset($this->data['User']['id']) && ($this->data['User']['id'] == $this->getUserId())))
    {
      if ($this->AjaxValid->valid)
      {
        $this->__login($this->data);
      }
    }
	}
	
	function __login($user)
	{
	  // set users timezone
	  if (!isset($user['TimeZone']))
	  {
	    $this->User->TimeZone->expects();
	    $timeZone = $this->User->TimeZone->findById($user['User']['time_zone_id']);
	    $user['TimeZone'] = $timeZone['TimeZone'];
	  }
	  date_default_timezone_set($user['TimeZone']['value']);
	  $this->Session->write('timeZone', $user['TimeZone']['value']);
    
	  $this->User->expects('TimeZone');
    $user = $this->User->findById($user['User']['id']);
    $this->Session->write('User', $user);
	  
	  //unset($user['User']['password']);
	  if (isset($user['User']['ip']))
	  {
	    $user['User']['ip'] = $user['User']['ip'];
	    $this->User->createUserIp($user);
	  }
    // check to see if a user has an rss feed id, if not, create it
    if (!isset($user['User']['feed_id']) || (strlen($user['User']['feed_id']) <= 0))
    {
      $user['User']['feed_id'] = $this->User->createRSSFeedId($user);
    }
    // change last_login for user
    $user['User']['last_login'] = date('Y-m-d H:i:s');
    if (!$this->User->save($user, false, array('last_login', 'ip')))
	  {
	    $this->error('Users::__login', 'Could not save last login or ip', $user['User']['id']);
	  }
	  $this->cookie_time = time() + 60*60*24*14;
	  if ($this->isDevEnv())
	  {
  	  setcookie("id", $user['User']['id'], $this->cookie_time, "/");
	  }
	  else
	  {
  	  // had to remove trailing slash for production server, not sure why
      setcookie("id", $user['User']['id'], $this->cookie_time, "/");
	  }
	  // delete old messages
	  $this->__deleteMessages($user['User']['id']);
	}
	
	function __deleteMessages($userId)
	{
	  $query = 'DELETE FROM messages WHERE del=1 AND user_id=' . $userId;
	  if ($this->User->query($query) === false)
	  {
	    $this->error('Users::__deleteMessages', 'Could not delete old messages', $userId);
	  }
	}
	
	function login_validator()
	{
	  // sanitize data
	  $this->sanitize($this->data);
	  // save the user's ip
    $this->data['User']['ip'] = $this->RequestHandler->getClientIP();
	  $this->layout = '';
    $this->AjaxValid->return = 'html';
    $this->AjaxValid->changeClass('errors');
    $referer = array('controller' => 'messages', 'action' => 'index');
    if ($this->Session->check('afterLogin'))
    {
      $referer = $this->Session->read('afterLogin');
      $this->Session->delete('afterLogin');
    }
    $this->AjaxValid->setForm($this->data, $referer, 'redirect');
    $this->set('data', $this->AjaxValid->validate('User'));
    $this->set('valid', $this->AjaxValid->valid);
    if ($this->AjaxValid->valid)
    {
      // login user
      $user = $this->User->validateLogin($this->data);
      if (!$user)
      {
        $this->AjaxValid->errors['User']['email'] = 'Invalid email or password.';
        $this->set('data', $this->AjaxValid->validate());
        $this->set('valid', false);
      }
      else if (!is_array($user))
      {
        $error = 'Your account has not been activated yet.<br>';
        $error .= '<a href="/users/activate/' . $this->data['User']['email'] .'">Click here to activate your account.</a>';
        $this->AjaxValid->errors['User']['email'] = $error;
        $this->set('data', $this->AjaxValid->validate());
        $this->set('valid', false);
      }
      else
      {
        $this->__login($user);
      }
    }
	}

	function change_password($key, $undo = false)
  {
    $this->User->expects();
    $user = $this->User->findByPasswordChangeKey($key);
    if (isset($user['User']['email']))
    {
      $pcKey = md5($user['User']['email'] . ($user['User']['password_forgotten'] - 1) . 'EMAIL_SALT');
      if ($pcKey == $key)
      {
        if ($undo)
        {
          $user['User']['password_change_key'] = 'NULL';
          if ($this->User->save($user))
          {
            $this->Session->setFlash('The password change request on your account was successfully removed.');
          }
          else
          {
            $this->error('Users::change_password', 'Could not undo password change key', $user['User']['email']);
          }
          $this->redirect(array('controller' => 'users', 'action' => 'forgot_password'));
        }
        else
        {
          $this->data['User']['password_change_key'] = $key;
        }
      }
      else
      {
        $this->Session->setFlash('Invalid password change key.  You may have already used this key to change your password. You will need to submit the request again.');
        $this->redirect(array('controller' => 'users', 'action' => 'forgot_password'));
      }
    }
    else
    {
      $this->Session->setFlash('Invalid password change key.  You may have already used this key to change your password. You will need to submit the request again.');
      $this->redirect(array('controller' => 'users', 'action' => 'forgot_password'));
    }
  }
  
  function change_password_validator()
  {
    // remove any malicious data that may have been entered in the form
    $this->sanitize($this->data['User']['email']);
    $this->sanitize($this->data['User']['password']);
    
    $this->layout = '';
    $this->AjaxValid->return = 'html';
    $this->AjaxValid->changeClass('errors');
    $this->AjaxValid->setForm($this->data, '/', 'redirect');
    //$this->AjaxValid->required(array('User/email', 'User/password'));
    $this->AjaxValid->confirm('User/password', array('User/confirm'), 'Your passwords do not match.  Please re-enter');
    $this->set('data', $this->AjaxValid->validate('User'));
    $this->set('valid', $this->AjaxValid->valid);

    if ($this->AjaxValid->valid)
    {
      $this->User->expects();
      $user = $this->User->findByPasswordChangeKey($this->data['User']['password_change_key']);
      if ($user['User']['email'] == $this->data['User']['email'])
      {
        $this->data['User']['id'] = $user['User']['id'];
        $this->data['User']['password_change_key'] = 'NULL';
        //$this->data['User']['last_login'] = date('Y-m-d H:i:s');
        if ($this->User->save($this->data))
        {
          $this->Session->write('User', $user);
          $this->Session->setFlash('Your password was changed succesfully and your are now logged in.');
        }
        else
        {
          $this->error('Users::change_password_validator', 'Could not save reset users password change key', $user['User']['id']);
        }
      }
      else
      {
        $this->AjaxValid->errors['User']['email'] = 'The email entered does not match our records.  Please verify the link and try again.';
        $this->set('data', $this->AjaxValid->validate());
        $this->set('valid', false);
      }
    }
  }
  
	function forgot_password($email = null)
	{
	  if ($email)
	  {
	    //$this->data['User']['email'] = $email;
	    $this->User->expects();
	    $this->data = $this->User->findByEmail($email);
	  }
	  $this->_setFormData();
	}
	
	function __createChangePasswordKey($user)
	{
	  // create a password change key
	  $user['User']['password_change_key'] = md5($user['User']['email'] . $user['User']['password_forgotten'] . 'EMAIL_SALT');

	  // add 1 to password_forgotten
	  $user['User']['password_forgotten']++;

	  // save the change key and the password forgotten
	  unset($user['User']['password']);
	  if ($this->User->save($user))
	  {
	    return $user;
	  }
	  else
	  {
	    $this->error('Users::__createChangePasswordKey', 'Could not save user after creating password change key', $this->data['User']['email']);
	  }
	  return false;
	}
	
	function add_email_validator()
	{
    $this->layout = '';
    $this->AjaxValid->return = 'html';
    $this->AjaxValid->changeClass('errors');
    $this->AjaxValid->setForm($this->data);
    $this->set('data', $this->AjaxValid->validate('UserEmail'));
    $this->set('valid', $this->AjaxValid->valid);
	  if ($this->AjaxValid->valid)
	  {
	    if (!$this->User->UserEmail->save($this->data))
	    {
	      $this->error('Users::add_email_validator', 'Could not create UsersEmail', $this->data['UserEmail']['user_id']);
	    }
	  }
//	  $this->User->expects('UserEmail');
//	  $user = $this->User->findById($this->data['UserEmail']['user_id']);
    $email = $this->data['UserEmail']['email'];
	  $this->set(compact('email'));
	}
	
	function forgot_password_validator()
	{
	  // sanitize data
    $this->sanitize($this->data);
    $this->layout = '';
    $this->AjaxValid->return = 'html';
    $this->AjaxValid->changeClass('errors');
    $this->AjaxValid->setForm($this->data, '/', 'redirect');
    $this->set('data', $this->AjaxValid->validate('User'));
    $this->set('valid', $this->AjaxValid->valid);
    if ($this->AjaxValid->valid)
    {
      $this->User->expects();
      $user = $this->User->findByEmail($this->data['User']['email']);
      if (empty($user))
      {
        $error = 'Cound not find the email in our system.  Have you ';
        $error .= '<a href="/users/add">registered</a> yet?';
        $this->AjaxValid->errors['User']['email'] = $error;
        $this->set('data', $this->AjaxValid->validate());
        $this->set('valid', false);
      }
      else
      {
        $user = $this->__createChangePasswordKey($user);
        
        if ($this->__sendEmail($user))
        {
          $this->Session->setFlash('An email has been sent to ' . $user['User']['email']. '  Click on the link in the email to change your password');
          //$this->redirect('/');
        }
        else
        {
          $this->error('Users::forgot_password_validator', 'Could not created change password key', $user['User']['id'], 'Could not send email, an thes system admin has been notified', '/');
        }
      }
    }
	}
	
  function __sendEmail($user, $subject = 'Change Password Link', $message = 'Here is the link to change your password. ')
  {
    $this->Mailer->init();
    $this->Mailer->Subject = $subject;
    $this->Mailer->AddAddress($user['User']['email']);
    $plainBody = $message;
    $plainBody .= 'http://' . $this->_getHostname() . '/users/change_password/' . $user['User']['password_change_key'];
    $this->Mailer->Body = $plainBody;
    if (($user['User']['email'] == 'junker37@gmail.com') || ($user['User']['email'] == 'jrmcjunk@us.ibm.com'))
    {
      Configure::write('debug', 2);
      debug($plainBody);
    }
//    if ($this->isDevEnv())
//    {
//      return true;
//    }
    return $this->Mailer->send();
  }
  
//  function admin_index() {
//		$this->User->recursive = 0;
//		$this->set('users', $this->paginate());
//	}
//
//	function admin_view($id = null) {
//		if (!$id) {
//			$this->Session->setFlash(__('Invalid User.', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		$this->set('user', $this->User->read(null, $id));
//	}
//
//	function admin_add() {
//		if (!empty($this->data)) {
//			$this->User->create();
//			if ($this->User->save($this->data)) {
//				$this->Session->setFlash(__('The User has been saved', true));
//				$this->redirect(array('action'=>'index'));
//			} else {
//				$this->Session->setFlash(__('The User could not be saved. Please, try again.', true));
//			}
//		}
//		$players = $this->User->Player->find('list');
//		$pictures = $this->User->Picture->find('list');
//		$this->set(compact('players', 'pictures'));
//	}
//
//	function admin_edit($id = null) {
//		if (!$id && empty($this->data)) {
//			$this->Session->setFlash(__('Invalid User', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		if (!empty($this->data)) {
//			if ($this->User->save($this->data)) {
//				$this->Session->setFlash(__('The User has been saved', true));
//				$this->redirect(array('action'=>'index'));
//			} else {
//				$this->Session->setFlash(__('The User could not be saved. Please, try again.', true));
//			}
//		}
//		if (empty($this->data)) {
//			$this->data = $this->User->read(null, $id);
//		}
//		$players = $this->User->Player->find('list');
//		$pictures = $this->User->Picture->find('list');
//		$this->set(compact('players','pictures'));
//	}
//
//	function admin_delete($id = null) {
//		if (!$id) {
//			$this->Session->setFlash(__('Invalid id for User', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		if ($this->User->del($id)) {
//			$this->Session->setFlash(__('User deleted', true));
//			$this->redirect(array('action'=>'index'));
//		}
//	}

}
?>
