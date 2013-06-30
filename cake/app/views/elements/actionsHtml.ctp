<?php // views/elements/actionsHtml.ctp : renders actions with an HTML link
//$actions: the actions to render
?>

<?php foreach ($actions as $title => $url): ?>
  <li><?php echo $html->link($title, $url); ?></li>
<?php endforeach; ?>
