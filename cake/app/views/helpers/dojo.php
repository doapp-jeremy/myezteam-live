<?php 

/**
 * Helper for AJAX operations with Dojo.
 *
 * Helps doing AJAX using the Dojo toolkit.
 *
 * PHP version 5
 *
 * Copyright (c) 2006, Christian Trummer
 * http://blog.cws-trummer.biz
 * http://get-the-answer.info
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */
class DojoHelper extends Helper
{
	var $helpers = array('Html', 'Javascript');
	
	var $tags = array(
   'accordionContainerStart' => '<div dojoType="dijit.layout.AccordionContainer" duration="%s" style="%s">',
	 'accordionContainerEnd' => '</div>',
   'accordionContainer' => '<div dojoType="dijit.layout.AccordionContainer" duration="%s" style="%s">%s</div>',
	 'accordionPaneStart' => '<div dojoType="dijit.layout.AccordionPane" %s>', 
   'accordionPaneEnd' => '</div>', 
	 'accordionPane' => '<div dojoType="dijit.layout.AccordionPane" %s>%s</div>', 
	);
	var $accordionStyle = array('margin-right' => "30px", "width" => "650px", "height" => "500px", "overflow" => "hidden");

	function accordionPaneStart($options = array())
	{
	  return $this->output(sprintf($this->tags['accordionPaneStart'], $this->_parseAttributes($options, null, ' ', '')));
	}
	
	function accordionPane($options = array(), $paneBody = '')
	{
	  if (isset($options['url']))
	  {
	    $options['href'] = $this->url($options['url']); 
	    unset($options['url']);
	  }
	  return $this->output(sprintf($this->tags['accordionPane'], $this->_parseAttributes($options, null, ' ', ''), $paneBody));
	}
	
	function accordionPaneEnd()
	{
	  return $this->output($this->tags['accordionPaneEnd']);
	}
	
	function accordionContainerStart($duration = '200', $styles = array('margin-right' => '30px', 'width' => '650px', 'height' => '500px', 'overflow' => 'hidden'))
	{
	  $styleString = '';
	  foreach ($styles as $styleKey => $styleValue)
	  {
	    $styleString .= $styleKey . ': ' . $styleValue . ';';
	  }
	  
	  return $this->output(sprintf($this->tags['accordionContainerStart'], $duration, $styleString));
	}
	
  function accordionContainer($containerbody, $duration = '200', $sytles = array('margin-right' => '30px', 'width' => '650px', 'height' => '500px', "overflow" => "hidden"))
  {
    return $this->output($this->accordionContainerStart($duration, $styles) . $this->output($containerbody) . $this->accordionContainerEnd());
  }
  
  function accordionContainerEnd()
  {
    return $this->output($this->tags['accordionContainerEnd']);
  }
	
	var $base = null;
/**
 * URL to current action.
 *
 * @var string
 */
	var $here = null;
/**
 * Parameter array.
 *
 * @var array
 */
	var $params = array();
/**
 * Current action.
 *
 * @var string
 */
	var $action = null;
/**
 * Enter description here...
 *
 * @var array
 */
	var $data = null;
/**
 * Name of model this helper is attached to.
 *
 * @var string
 */
	var $model = null;
/**
 * Enter description here...
 *
 * @var string
 */
	var $field = null;
	
	

	function _buildAjaxParams($params = null)
	{
		if ($params == null)
		{
			$params = array('update'		=>'',
							'url' 			=> '',
							'type' 			=> 'asynchronous',
							'mimetype'		=> 'text/html',
							'complete' 	=> '',
							'before'		=> '',
							'after' 		=> '');
		}
		else
		{
			if (empty($params['update'])) 	$params['update']	= '';
			if (empty($params['url']))			$params['url'] 		= '';
			if (empty($params['type']))		$params['type'] 	= 'asynchronous';
			if (empty($params['mimetype']))	$params['mimetype'] = 'text/html';
			if (empty($params['complete']))	$params['complete'] = '';
			if (empty($params['before']))		$params['before'] 	= '';
			if (empty($params['after']))		$params['after'] 	= ''; 
		}
		
		// for the requesthandler component to recognize that this call is a ajax call
		$params['headers'] = "headers:{'X-Requested-With': 'XMLHttpRequest'}";
		
		return $params;
	}
 
 
 	// function copied from ajax helper
   function __getHtmlOptions($options, $extra = array())
    {
        foreach($this->ajaxOptions as $key)
        {
            if (isset($options[$key]))
            {
                unset($options[$key]);
            }
        }
        foreach($extra as $key)
        {
            if (isset($extra[$key]))
            {
                unset($options[$key]);
            }
        }
        return $options;
    }
   
   
   
   function remoteFunction($params = null)
   {
   		$params = $this->_buildAjaxParams($params);
   		$url = $this->Html->url($params['url']);
   		
   		$func = "";
   		
   		if ($params['update'] == '')
   		{
   			$func = "dojo.io.bind({url: '".$url."',".
									"load: function(type, data, event) {".$params['complete']."},".
									"mimetype: '".$params['mimetype']."'," .
									$params['headers']." });";
   		}
		else
		{
			$func = "dojo.io.updateNode(dojo.byId('".$params['update']."'), {url: '".$url."',mimetype: '".$params['mimetype']."', transport: 'XMLHTTPTransport', ".$params['headers']."});";
		}
		
		if ($params['before'] != '') 	$func = "{$params['before']}; $func";
		if ($params['after'] != '')	$funct = "$func {$params['after']};";
		
		return $this->output($func);
   }
   
   function contentPaneLink($contentPaneId, $url)
   {
   		$url = $this->Html->url($url);
   		$func = "dojo.widget.byId('".$contentPaneId."').setUrl('".$url."');";
   		
   		return $this->output($func);
   }
   
   function link($title, $params)
   {
   		$params = $this->_buildAjaxParams($params);
   		
   		if (isset($params['updateContentPane']))
   		{
   			$link =  "<a href='#' onClick=\"".$this->contentPaneLink($params['updateContentPane'], $params['url'])."\">".$title."</a>";
   		}
   		else
   		{
   			$link = "<a href='#' onClick=\"".$this->remoteFunction($params)."\">".$title."</a>";
   		}
   		
   		return $this->output($link);
   }
   
   function form($formParams = array(), $type = "post", $params = null)
   {
   		$params = $this->_buildAjaxParams($params);
   		$action = isset($formParams['action']) ? $formParams['action'] : null;
   		$action = $this->Html->url($action);

        if(!isset($formParams['id']))
        {
            $formParams['id'] = 'form'.intval(rand());
        }
        
        if(!isset($formParams['accept-charset']))
        {
        	$formParams['accept-charset'] = 'UTF-8';
        }
        
        
		$function = "function(type, data, evt)";
		if ($params['mimetype'] == "text/javascript")
			$function = "function(type, data)";

		if (isset($params['updateContentPane']))
		{
			$func = "dojo.io.bind({url: '".$action."',".
										"load: $function {dojo.widget.byId('".$params['updateContentPane']."').setContent(data);},".
										"mimetype: '".$params['mimetype']."'," .
										"formNode: dojo.byId('".$formParams['id']."'),".
										$params['headers']." });";
		}
		else
		{
			$func = "dojo.io.bind({url: '".$action."',".
										"load: function() {".$params['complete']."},".
										"mimetype: '".$params['mimetype']."'," .
										"formNode: dojo.byId('".$formParams['id']."'),".
										$params['headers']." });";
		}
		
		$formParams['onsubmit'] = $func." return false;";
        
        //$formParams['with'] = $func;
        $formParams['url'] = $action;

        return $this->Html->formTag($action, $type, $formParams);
   }
   
   
   
   
   
   function spinner($fieldName, $htmlAttributes = null, $return = false) {
		$this->Html->setFormTag($fieldName);
		
		if (!isset($htmlAttributes['value'])) {
			$htmlAttributes['value'] = $this->Html->tagValue($fieldName);
		}

		if (!isset($htmlAttributes['dojoType'])) {
			$htmlAttributes['dojoType'] = 'SpinnerIntegerTextBox';
		}

		if (!isset($htmlAttributes['id'])) {
			$htmlAttributes['id'] = $this->Html->model . Inflector::camelize($this->Html->field);
		}

		if ($this->Html->tagIsInvalid($this->Html->model, $this->Html->field)) {
			if (isset($htmlAttributes['class']) && trim($htmlAttributes['class']) != "") {
				$htmlAttributes['class'] .= ' form_error';
			} else {
				$htmlAttributes['class'] = 'form_error';
			}
		}
		
		$tag = '<div name="data[%s][%s]" %s></div>';
		return $this->output(sprintf($tag, $this->Html->model, $this->Html->field, $this->Html->_parseAttributes($htmlAttributes, null, ' ', ' ')), $return);
	}
	
	function spinnerTime($fieldName, $htmlAttributes = null, $return = false) {
		$this->Html->setFormTag($fieldName);
		
		if (!isset($htmlAttributes['value'])) {
			$htmlAttributes['value'] = $this->Html->tagValue($fieldName);
		}

		if (!isset($htmlAttributes['dojoType'])) {
			$htmlAttributes['dojoType'] =  'SpinnerTimeTextBox';
		}

		if (!isset($htmlAttributes['id'])) {
			$htmlAttributes['id'] = $this->Html->model . Inflector::camelize($this->Html->field);
		}
		
		if (!isset($htmlAttributes['format'])) {
			$htmlAttributes['format'] = "HH:mm:ss";
		}
		
		if (!isset($htmlAttributes['delta'])) {
			$htmlAttributes['delta'] = "1:01:01";
		}
		
		if ($this->Html->tagIsInvalid($this->Html->model, $this->Html->field)) {
			if (isset($htmlAttributes['class']) && trim($htmlAttributes['class']) != "") {
				$htmlAttributes['class'] .= ' form_error';
			} else {
				$htmlAttributes['class'] = 'form_error';
			}
		}
		
		$tag = '<input name="data[%s][%s]" %s>';
		return $this->output(sprintf($tag, $this->Html->model, $this->Html->field, $this->Html->_parseAttributes($htmlAttributes, null, ' ', ' ')), $return);
	}
	
	function dropDownColorPicker($fieldName, $htmlAttributes = null, $return = false)
	{
		$this->Html->setFormTag($fieldName);
		
		if (!isset($htmlAttributes['value'])) {
			$htmlAttributes['value'] = $this->Html->tagValue($fieldName);
		}
		
		$htmlAttributes['defaultColor'] = $htmlAttributes['value'];

		if (!isset($htmlAttributes['dojoType'])) {
			$htmlAttributes['dojoType'] = 'DropDownColorPicker';
		}
		
		if (!isset($dojoAttributes['id'])) {
			$htmlAttributes['id'] = $this->Html->model . Inflector::camelize($this->Html->field);
		}

		if ($this->Html->tagIsInvalid($this->Html->model, $this->Html->field)) {
			if (isset($htmlAttributes['class']) && trim($htmlAttributes['class']) != "") {
				$htmlAttributes['class'] .= ' form_error';
			} else {
				$htmlAttributes['class'] = 'form_error';
			}
		}
		
		$tag = '<div inputName="data[%s][%s]" %s></div>';
		return $this->output(sprintf($tag, $this->Html->model, $this->Html->field, $this->Html->_parseAttributes($htmlAttributes, null, ' ', ' ')), $return);
	}
   
}
?>