<?php
class Error extends AppModel {

	var $name = 'Error';
	var $useTable = 'errors';

	var $hasMany = array(
	      'Message' => array('className' => 'Message',
                'foreignKey' => 'error_id',
                'dependent' => true,
                'conditions' => '',
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'exclusive' => '',
                'finderQuery' => '',
                'counterQuery' => ''
      ),
	
	);
}
?>