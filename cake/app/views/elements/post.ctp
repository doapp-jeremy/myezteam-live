<?php // views/elements/post.ctp : renders a post
//$post : (required) : the post to render
?>

<?php
$id = $post['Post']['id'];
$divId = $myHtml->baseDivId('Post', $id, $post);

if (!isset($class))
{
  $class = 'post';
}
?>

<div id="<?php echo $divId; ?>" class="<?php echo $class; ?>">
  <div id="<?php echo $divId; ?>Title" class="borderBottom">
    <span class="postName bigger bold"><?php echo $post['User']['nameOrEmail']; ?></span>
    <span class="postDate mar10"><?php echo $time->niceShort($post['Post']['created']); ?></span>
  </div>
  <div id="<?php echo $divId; ?>Text" class="mar10">
    <?php echo nl2br($post['Post']['text']); ?>
  </div>
</div>
