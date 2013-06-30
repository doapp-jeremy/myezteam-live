<?php
class MessageHelper extends HtmlHelper
{
  var $helpers = array('Html', 'Time');
  

  function title($message)
  {
    return $message['Message']['title'];
  }
  
  function body($message)
  {
    return $message['Message']['msg'];
  }
  
  function date($message, $class = 'messageDate')
  {
    $date = $this->renderElement('date', array('datetime' => $message['created']));
    return $this->output(sprintf($this->tags['block'], $class, $date));
  }
  
  function link($message, $class = 'messageLink')
  {
    
  }
}
?>