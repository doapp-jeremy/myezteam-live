<?php // views/elements/post/postList.ctp : renders a list of posts

?>


<?php
$topicId = $posts['Topic']['id'];
$divId = 'topic' . $topicId . 'PostsHolder';
$url = array('controller' => 'topics', 'action' => 'posts', $topicId);
//$loading = 'document.getElementById("' . $divId . '").innerHTML = "refreshing...";';
$loading = 'document.getElementById("loader").innerHTML = "refreshing...";';
$complete = 'document.getElementById("loader").innerHTML = "";';
?>

<?php if (!$ajax->isAjax()): ?>
  <div>
    <span class="biggest"><?php echo $posts['Topic']['title']; ?></span>
    <span class="date smaller"><?php echo $time->niceShort($posts['Topic']['created']); ?></span>
    <?php if (isset($posts['Topic']['User'])): ?>
    <span><?php echo $posts['Topic']['User']['nameOrEmail']; ?></span>
    <?php endif; ?>
  </div>
<?php endif; ?>

<div id="<?php echo $divId; ?>" class="">
<div class="postList" id="topic<?php echo $topicId; ?>PostList">
<?php
//echo $ajax->link('refresh', $url, array('update' => $divId, 'loading' => $loading));
if (!isset($posts))
{
  $posts = $post;
}
$formId = 'topic' . $topicId . 'AddPost';
?>

<div id="<?php echo $formId; ?>">
  <div class="mar5">
    <span>
      <?php echo $ajax->link('refresh', $url, array('update' => $divId, 'loading' => $loading, 'complete' => $complete)); ?>
    </span>
    <span class="mar10">
      <?php echo $ajax->link('Add Post', array('controller' => 'posts', 'action' => 'add', $topicId), array('update' => $formId)); ?>
    </span>
  </div>
</div>

<?php
if (!empty($posts['Post']))
{
  //$url = array('controller' => 'topics', 'action' => 'posts', $topicId);
  $elementName = 'posts';
  $elementOptions = array('posts' => $posts['Post'], 'topicId' => $topicId);
  $pagingDivId  = $divId;
  echo $this->element('paginatorView', compact('url', 'elementName', 'elementOptions', 'pagingDivId'));
}
else
{
  echo 'There are no posts';
}
?>
</div>
</div>