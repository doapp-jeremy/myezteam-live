<?php // views/elements/message/postMessage.ctp
//$post : (required) :

?>

<?php
if (!isset($post['Post']))
{
  $post = array('Post' => $post);
}
if (isset($post['User']))
{
  $post['Post']['User'] = $post['User'];
}
if (isset($post['Topic']))
{
  $post['Post']['Topic'] = $post['Topic'];
}
?>

<?php
$title = '<span style="color: #996600;">' . $post['Post']['User']['nameOrEmail'] . '</span> posted to ';
$title .= '<span style="color: #66FF00;">';
$title .= $html->link($post['Post']['Topic']['title'], 'http://' . $hostname . '/topics/posts/' . $post['Post']['topic_id'], array('style' => 'color: #66FF00;'));
$title .= '</span>';
$title .= '<span style="margin-left: 10px; font-size: 75%; color: #333333;">' . $time->niceShort($post['Post']['created']) . '</span>';


$data = nl2br($post['Post']['text']);
$maxPostSize = 500;
if (strlen($data) > $maxPostSize)
{
  $data = nl2br(substr($data, 0, $maxPostSize)) . '...' . $html->link('View the rest of the post', 'http://' . $hostname . '/topics/posts/' . $post['Post']['topic_id']);
}

echo $title;
echo '<div style="margin-top: 10px; margin-left: 20px; font-size: 90%;">';
echo $data;
echo '</div>';
?>
