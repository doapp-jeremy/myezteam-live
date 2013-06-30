<?php // views/elements/toggleElement.ctp : renders an element that will get it's data ajaxically at first, then javascript
//$divId : (optional - if not set, a random one will be created) :
//$title : (optional - if not set, will use a + sign) : 
//$url : (option - if not set, $data must be) :
?>

<?php
if (!isset($divId))
{
  $divId = 'toggleElement' . rand();
}
$bodyId = $divId . 'Body';
if (!isset($title))
{
  $title = '+';
}
if (!isset($class))
{
  $class = 'toggleElement';
}

$complete = "changeToToggle('" . $divId . "Title', '" . $bodyId . "', '" . $title . "');";
if (!isset($loadingId))
{
  $loadingId = $bodyId;
}
else
{
  $complete .= 'document.getElementById("' . $loadingId . '").innerHTML = "";';
}
$loading = 'document.getElementById("' . $loadingId . '").innerHTML = "loading...."';
?>
<!-- Toggle Element -->
<div id="<?php echo $divId; ?>" class="<?php echo $class; ?>">
  <!-- Title -->
  <div id="<?php echo $divId; ?>Title">
    <?php
    $link = $html->link($title, 'javascript:void(0)', array('onclick' => 'toggleElementId("' . $divId . '")'));
    //$escapedTitle = str_replace('"', '\"', $title);
    $options = array(
      'update' => $bodyId,
      //'loading' => 'document.getElementById("' . $bodyId . '").innerHTML = "loading...."',
      'loading' => $loading,
      //'complete' => "changeToToggle('" . $divId . "Title', '" . $bodyId . "', '" . $title . "')"
      'complete' => $complete
    );
    if (isset($url))
    {
      echo $ajax->link($title, $url, $options, null, false);
    }
    else
    {
      echo $link;
    }
    ?>
  </div>
  <!-- END Toggle Title -->
  <!-- Toggle Body -->
  <div id="<?php echo $bodyId; ?>">
    <?php
    if (isset($data))
    {
      echo $data;
    }
    ?>
  </div>
  <!-- END Toggle Body -->
</div>
<!-- END Toggle Element -->