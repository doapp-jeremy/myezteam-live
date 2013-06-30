<?php
class Post extends AppModel {

	var $name = 'Post';
	var $useTable = 'posts';

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'Topic' => array('className' => 'Topic',
								'foreignKey' => 'topic_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			),
			'User' => array('className' => 'User',
								'foreignKey' => 'user_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);

	var $hasMany = array(
	      'Message' => array('className' => 'Message',
                'foreignKey' => 'post_id',
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
	
	 function afterSave($created)
  {
    // after a post has been saved, change the the post's topic's modification date
    $this->Topic->expects();
    $topic = $this->Topic->find(array('Topic.id' => $this->data['Post']['topic_id']));
    $topic['Topic']['modified'] = date('Y-m-d H:i:s');
    if (!$this->Topic->Save($topic))
    {
      $this->error('Post::afterSave', 'There was an error trying to update the topics modification time', $this->data[$this->name]['user_id']);
    }
    else
    {
      // delete topic cache
//      $cacheId = 'element_topic_cache_plugin_' . $this->data['Post']['topic_id'] . '_topic';
//      $this->_clearCache($cacheId);
      // create messages
      $this->data['Topic'] = $topic['Topic'];
      $this->data[$this->name]['id'] = $this->getLastInsertID();
      $this->User->Message->newPost($this->data);
    }
    return parent::afterSave($created);
  }

}
?>