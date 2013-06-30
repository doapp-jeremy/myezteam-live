<?php // views/elements/delete.ctp
//$defaultTab : (required) :
?>

<?php
if (!isset($flash))
{
  $flash = '';
}

if (isset($showTab) && $showTab)
{
  echo $javascript->codeBlock('showTabBeforeEdit("' . $defaultTab . '", "' . $flash . '")');
}
if (isset($refresh))
{
  foreach ($refresh as $options)
  {
    $codeBlock = '
        var element = document.getElementById("' . $options['update'] . '");
        if (element)
        {
          ' . $ajax->remoteFunction($options) . '
        }
        else
        {
          
        }
      ';
    echo $javascript->codeBlock($codeBlock);
  }
}
// for some reason, if the exit isn't here, a second call gets made to validator
exit();
?>
