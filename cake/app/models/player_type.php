<?php
class PlayerType extends AppModel
{

	var $name = 'PlayerType';
	var $useTable = 'player_types';

  var $actsAs = array('AutoField' => array(
   'humanName' => array('fields' => array('name'), 'function' => 'humanize'),
   'humanNamePlural' => array('fields' => array('name'), 'function' => 'humanizePlural'),
  ));

  function humanize($data)
  {
    $humanName = Inflector::humanize($data[0]);
    return $humanName;
  }

  function humanizePlural($data)
  {
    $humanNamePlural = Inflector::pluralize(Inflector::humanize($data[0]));
    return $humanNamePlural;
  }

  function afterFind($results, $primary = false)
  {
    if (!$primary && !empty($this->behaviors))
    {
      $b = array_keys($this->behaviors);
      $c = count($b);

      for ($i = 0; $i < $c; $i++)
      {
        $return = $this->behaviors[$b[$i]]->afterFind($this, $results, $primary);
        if (is_array($return))
        {
          $results = $return;
        }
      }
    }
    return parent::afterFind($results, $primary);
  }  
}
?>