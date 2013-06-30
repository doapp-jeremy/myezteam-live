<?php // views/elements/message/topicMessage.ctp
//$topic : (required) :

?>

<?php
if (!isset($topic['Topic']))
{
  $topic = array('Topic' => $topic);
}
if (isset($topic['User']))
{
  $topic['Topic']['User'] = $topic['User'];
}
?>

<?php
$title = $topic['Topic']['title'];
$link = $html->link($title, 'http://' . $hostname . '/topics/posts/' . $topic['Topic']['id'], array('style' => 'color: #66FF00;'));
$title = 'A new topic was started: <span class="topicMessageTitle">' . $link . '</span>';
$title .= ' by <span style="color: #996600;">' . $topic['Topic']['User']['nameOrEmail'] . '</span> at';
$title .= '<span style="margin-left: 5px; font-size: 75%; color: #333333;">' . $time->niceShort($topic['Topic']['created']) . '</span>';
echo $title;
?>
