<?php
class UserEmail extends AppModel {

	var $name = 'UserEmail';
	var $useTable = 'user_emails';

  var $validate = array(
    'email' => array(
      'required' => array('rule' => VALID_EMAIL, 'message' => 'You must specify a valid email address'),
      'length' => array('rule' => array('maxLength', 32), 'message' => 'The email address must not exceed 32 characters'),
    )
  );
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'User' => array('className' => 'User',
								'foreignKey' => 'user_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);

}
?>