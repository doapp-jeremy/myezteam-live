<?php
class PagingHelper extends AppHelper 
{
  var $name = 'Paging';
  
  var $helpers = array('Html', 'Paginator');
  
  var $tags = array(
    'paging' => ''
  );
  
  function links()
  {
    if ($this->Paginator->hasPrev() || $this->Paginator->hasNext())
    {
      $output = '<div class="paging">';
      $output .= $this->Paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));
      $output .= ' | ';
      $output .= $this->Paginator->numbers();
      $output .= ' | ';
      $output .= $this->Paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));
      $output .= '</div>';
      return $this->output($output);
    }
  }
  
  function counter()
  {
    if ($this->Paginator->hasPrev() || $this->Paginator->hasNext())
    {
      $output = $this->Paginator->counter(array(
        'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)));
      return $this->output($output);
    }
  }
}
?>