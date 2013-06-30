<?php // views/elements/posts.ctp : renders posts
// $posts: topics to render
// $options: various options
?>

<?php
$baseId = rand();
if (isset($options))
{
  if (isset($options['baseId']))
  {
    $baseId = $options['baseId'];
  }
}
$divId = $baseId . 'Posts';
$loading = 'document.getElementById("' . $divId . '").innerHTML = "refreshing...";';
$url = array('controller' => 'topics', 'action' => 'posts', $topicId);
?>

<div id="<?php echo $baseId; ?>Posts" class="data topicPosts mar20Top">
  <?php //echo $ajax->link('refresh', $url, array('update' => $divId, 'loading' => $loading)); ?>
  <?php if (isset($title)): ?>
  <h3 id="<?php echo $baseId; ?>PostsActionLeft"><?php echo $title; ?></h3>
  <?php endif; ?>

  <?php $i = 0; ?>
  <?php foreach ($posts as $post): ?>
    <?php
    $class = 'evenPost';
    if (($i++ % 2) == 1)
    {
      $class = 'oddPost';
    }
    $class .= ' mar5Top';
    ?>
    <?php echo $this->element('post', compact('post', 'class')); ?>
  <?php endforeach; ?>
</div>
