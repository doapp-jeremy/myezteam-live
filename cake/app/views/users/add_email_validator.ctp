<?php // views/users/validator.ctp : user validator
//$valid : (required) : 
//$data : (required) : 
?>

<?php 
if ($valid)
{
  //echo "$data";
  //echo $this->element('user/emails');
  echo '<li>' . $email . '<li>';
  echo '<div id="udpater"></div>';
}
else
{
  //echo "<div class='error'>";
  print_r($data);
  //echo "</div>";
}
?>