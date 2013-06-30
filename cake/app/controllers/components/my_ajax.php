<?php
class MyAjaxComponent extends Object
{
  var $name ='MyAjax';
  var $controller = true;
   
  function startup(&$controller)
  {
    // This method takes a reference to the controller which is loading it.
    $this->controller = $controller;
  }
  
  function options($updateId, $linkId, $linkName)
  {  
    $options = array(
      'update' => $updateId, 
      'loading' => 'loading("' . $updateId . '");',
      'complete' => 'convertToShow("' . $linkId . '", "' . $updateId . '", "' . $linkName . '"); hideAllSiblingsExcept("' . $updateId . '");'
    );
    return $options;
  }
  
  function teamAjaxOptions($teamId, $link)
  {
    $humanLink = Inflector::humanize($link);
    $updateId = 'team' . $teamId . $humanLink;
    $linkId = 'team' . $teamId . $humanLink;
    return $this->options($updateId, $linkId, $humanLink);
  }
    
  function teamPlayersOptions($teamId)
  {
    return $this->options('team' . $teamId . 'Players', 'team' . $teamId . 'PlayersAction', 'Players');
  }
  
  function teamEventsOptions($teamId)
  {
    return $this->options('team' . $teamId . 'Events', 'team' . $teamId . 'EventsAction', 'Events');
  }
  
  function teamInfoOptions($teamId)
  {
    return $this->options('team' . $teamId . 'Info', 'team' . $teamId . 'InfoAction', 'Info');
  }
}
?>