<?php // views/elements/message/topicMessage.ctp
//$topic : (required) :

?>

<?php
if (!isset($topic['Topic']))
{
  $topic = array('Topic' => $topic);
}
?>

<?php
$title = $topic['Topic']['title'];
$link = $html->link($title, array('controller' => 'topics', 'action' => 'posts', $topic['Topic']['id']), array('title' => $title));
$title = 'A new topic was started: <span class="topicMessageTitle">' . $link . '</span>';
$options = array(
  'title' => $title,
  'style' => array('width' => '800px')
);
echo $this->element('dropDown', compact('options'));
?>
