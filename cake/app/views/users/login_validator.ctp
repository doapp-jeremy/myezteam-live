<?php // views/users/validator.ctp : user validator
//$valid : (required) : 
//$data : (required) : 
?>

<?php 
if ($valid)
{
  echo "$data";
}
else
{
  //echo "<div class='error'>";
  print_r($data);
  //echo "</div>";
}
?>