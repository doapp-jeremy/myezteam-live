<?php // views/elements/topic.ctp : renders a topic
//$topic : (required) :
?>

<?php
$oTopic = $topic;
if (isset($topic['Topic']))
{
  $topic = $topic['Topic'];
}
$id = $topic['id'];
$divId = $myHtml->baseDivId('Topic', $id, $oTopic);
$postsDivId = $divId . 'Posts';

$options = array(
  'update' => $postsDivId,
);
?>

<?php
$divId = 'topic' . $id;


$title = '<div class="' . $titleClass . 'Title">';//' class="' . $titleClass . '">';
$title .= '<span class="biggest topicTitle">' . $topic['title'] . '</span>';
if (isset($topic['newPosts']) && ($topic['newPosts'] > 0))
{
  $title .= '<span class="mar10 small">(' . $topic['newPosts'] . ')</span>';
}

$title .= '<span class="topicDate mar10">' . $time->niceShort($topic['modified']) . '</span>';
$title .= '</div>';
$url = array('controller' => 'topics', 'action' => 'posts', $id);
$class = 'topic mar10Top';
$loadingId = 'loader';
echo $this->element('toggleElement', compact('divId', 'title', 'url', 'class', 'loadingId'));
?>
