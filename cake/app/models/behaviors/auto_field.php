<?php
/**
 * Autofield behavior for cakePHP
 * comments, bug reports are welcome skie AT mail DOT ru
 * @author Yevgeny Tomenko aka SkieDr
 * @version 1.0.0.0
 * configuration is
 * mask is sprintf like mask
 *
 * array (
 *        'newFieldName' => array('fields'=>array('field1','field2'), 'mask'=>'%s,%s'),
 *        'newFieldName2' => array('fields'=>array('field1','field3'), 'mask'=>'%s: %s')
 *      );
 */
class AutoFieldBehavior extends ModelBehavior{
   
  var $settings = null;
  //var $_model = null;
   
  function setup(&$model, $config = array())
  {
    $this->autoFieldSetup($model, $config);
  }
   
  function autoFieldSetup(&$model, $config = array())
  {
    //$config = array('enabled'=>true);
    foreach ($config as $newField => $fieldDsc)
    {
      if (isset($fieldDsc['fields']))
      {
        foreach ($fieldDsc['fields'] as $field)
        {
          if(!$model->hasField($field))
          {
            //user_error(''Model {$model->name} does not have a field called {$field}'', E_USER_ERROR);
          }
        }
      }
       
      if (!isset($config[$newField]['fields']))
      {
        $config[$newField]['fields']=array();
      }

      if (!isset($config[$newField]['mask']) && !isset($config[$newField]['function']))
      {
        $config[$newField]['mask']='';
      }
    }
    $this->settings[$model->name]['config'] = $config;
    
    $this->settings[$model->name]['otherNames'] = array();
    if (isset($model->otherNames))
    {
      $this->settings[$model->name]['otherNames'] = $model->otherNames;
    }
  }
   
  /**
   * After  find method. Called after all find
   *
   * Add aditional fields
   *
   * @param AppModel $model
   * @return boolean True to continue, false to abort the save
   */
  function afterFind(&$model,  $results )
  {
    $config=$this->settings[$model->name]['config'];
    if (is_array($results))
    {
      $found = false;
      foreach ($config as $newField => $fieldDsc)
      {
        foreach ($fieldDsc['fields'] as $field)
        {
          if (isset($results[$field]))
          {
            $found = true;
            break;
          }
        }
      }
      if ($found)
      {
        $data = &$results;
        foreach ($config as $newField => $fieldDsc)
        {
          $someExist = false;
          $fieldVals=array();
          foreach ($fieldDsc['fields'] as $field)
          {
            if (isset($data[$field]))
            {
              $fieldVals[] = $data[$field];
              $someExist = true;
            }
            else
            {
              $fieldVals[]='';
            }
          }

          if (!$someExist)
          {
            break;
          }

          if (isset($fieldDsc['mask']))
          {
            $data[$newField]=vsprintf($fieldDsc['mask'],$fieldVals);
          }
          else if (isset($fieldDsc['function']))
          {
            $data[$newField] = $model->{$fieldDsc['function']}($fieldVals);
          }
        }
        unset($data);
        return $results;
      }

      $i = 0;
      while ($i < count($results))
      {
        $found = false;
        if (!isset($results[$i][$model->name]))
        {
          foreach ($this->settings[$model->name]['otherNames'] as $otherName)
          {
            if (isset($results[$i][$otherName]))
            {
              $found = true;
              break;
            }
          }
          if (!$found)
          {
            foreach ($config as $newField => $fieldDsc)
            {
              foreach ($fieldDsc['fields'] as $field)
              {
                if (isset($results[$i][$field]))
                {
                  $found = true;
                  break;
                }
              }
            }
          }
          if (!$found)
          {
            $i++;
            continue;
          }
        }
        
        $data = &$results[$i];
        if (isset($results[$i][$model->name]) || isset($results[$i]['Managers']) || isset($results[$i]['Creator']))
        {
          $n = $model->name;
          if (isset($results[$i]['Managers']))
          {
            $n = 'Managers';
          }
          else if (isset($results[$i]['Creator']))
          {
            $n = 'Creator';
          }
          $data = &$results[$i][$n];
        }
        if (count($data) > 0)
        {
          foreach ($config as $newField => $fieldDsc)
          {
            $someExist = false;
            $fieldVals=array();
            foreach ($fieldDsc['fields'] as $field)
            {
              if (isset($data[$field]))
              {
                $fieldVals[] = $data[$field];
                $someExist = true;
              }
              else
              {
                $fieldVals[]='';
              }
            }
            
            if (!$someExist)
            {
              break;
            }
            
            if (isset($fieldDsc['mask']))
            {
              $data[$newField]=vsprintf($fieldDsc['mask'],$fieldVals);
            }
            else if (isset($fieldDsc['function']))
            {
              $data[$newField] = $model->{$fieldDsc['function']}($fieldVals);
            }
          }
        }
        unset($data);
        $i++;
      }
    }
    return $results;
  }
}
?>