<?php
class UsersUser extends AppModel
{
  var $name = 'UsersUser';
  
//  var $belongsTo = array(
//    'User' => array(
//      'className' => 'User',
//      'foreignKey' => 'user_id'
//    )
//  );
  
  function afterSave($created)
  {
    if ($created)
    {
      // create the other users_user
      $userId = $this->data['UsersUser']['contact_id'];
      $contactId = $this->data['UsersUser']['user_id'];
      $usersUser = $this->findByUserIdAndContactId($userId, $contactId);
      if (empty($usersUser))
      {
        $this->create();
        $usersUser = array('UsersUser' => array('user_id' => $userId, 'contact_id' => $contactId));
        if (!$this->save($usersUser))
        {
          $this->error('UsersUsersModel::afterSave', 'Could not save users_user with user_id: ' . $userId . ' contact_id: ' . $contactId);
        }
      }
    }
  }

//  function beforeDelete($cascade = true)
//  {
//    $usersUser = $this->findById($this->id);
//    $contactId = $usersUser['UsersUser']['contact_id'];
//    if ($cascade)
//    {
//      $this->deletePlayers($usersUser['UsersUser']['user_id'], $contactId);
//    }
//    return parent::beforeDelete($cascade);
//  }
}
?>