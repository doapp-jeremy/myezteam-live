<?php 
if ($valid)
{
  echo "$data";
}
else
{
  echo "<div class='error'>";
  print_r($data);
  echo "</div>";
}
?>

<script type="text/javascript">
  disableForm(false);
</script>
