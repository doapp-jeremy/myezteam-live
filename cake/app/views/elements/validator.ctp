<?php // views/elements/validator.ctp
//$valid : (required) : 
//$data : (required) :
//$defaultTab : (required) :
//$flash : (optional) : flash message
?>

<?php
if (!isset($flash))
{
  $flash = '';
}

if ($valid)
{
  if (isset($showTab) && $showTab)
  {
    echo $javascript->codeBlock('showTabBeforeEdit("' . $defaultTab . '", "' . $flash . '")');
  }
  else
  {
    echo $data;
  }
  if (isset($refresh))
  {
    foreach ($refresh as $options)
    {
      $codeBlock = '
        var element = document.getElementById("' . $options['update'] . '");
        if (element)
        {
          try
          {
            ' . $ajax->remoteFunction($options) . '
          }
          catch(err)
          {
            element.innerHTML = "There was a problem refreshing the page.  Try using the refresh link to update the tab";
          }
        }
        else
        {
          
        }
      ';
      echo $javascript->codeBlock($codeBlock);
    }
  }
}
else
{
  echo $javascript->codeBlock('document.getElementById("submitButton").disabled = false');
  print_r($data);
}
// for some reason, if the exit isn't here, a second call gets made to validator
exit();
?>
