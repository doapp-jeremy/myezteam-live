<?php // views/elements/calendar.ctp 
//$link : (required) : the link to the google calendar
//$width : (optional) :
//$length : (optional) :

if (!isset($width))
{
  $width = 950;
}
if (!isset($length))
{
  $length = 600;
}
?>

<iframe src="<?php echo $link; ?>" style="border: 0" width="<?php echo $width; ?>" height="<?php echo $length; ?>" frameborder="0" scrolling="no"></iframe>
 