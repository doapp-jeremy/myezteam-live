<?php // views/elements/dropDown.ctp : renders a drop down element
// $options: options
?>

<?php
$id = 'dropDown' . rand();
if (isset($options['id']))
{
  $id = $options['id'];
  unset($options['id']);
}

$dataId = $id . 'Data';

$styleOptions = array('width' => '200px');
if (isset($options['style']))
{
  $styleOptions = array_merge($styleOptions, $options['style']);
}
$styleString = '';
foreach ($styleOptions as $style => $value)
{
  $styleString .= $style . ': ' . $value .'; ';
}
?>

<div class="dropDown" style="<?php echo $styleString; ?>">
  <!-- Title div -->
  <div>
  <?php // only need drop down if data or url is set ?>
  <?php if ((isset($options['data']) && (strlen($options['data']) > 0)) || isset($options['url'])): ?>
    <?php // if url is set, use ajax call ?>
    <?php if (isset($options['url'])): ?>
      <span id="<?php echo $id; ?>Arrow" class="arrow"><?php echo $ajax->link('+', $options['url'], array('title' => 'Show Info', 'update' => $dataId, 'complete' => 'completeData("' . $id . '")', 'loading' => 'loadingData("' . $id . '")')); ?></span>
    <?php endif; ?>

    <?php // if url is not set, just toggle the data ?>
    <?php if (!isset($options['url'])): ?>
      <?php $plusId = 'plus' . rand(); ?>
     <span id="<?php echo $id; ?>Arrow" class="arrow" onclick="toggleElement('<?php echo $id; ?>Bottom', 'block'); toggleArrow('<?php echo $plusId; ?>')">
      <a id="<?php echo $plusId; ?>" title="Show Info" href="javascript:void(0)">+</a>
     </span>
    <?php endif; ?>
  <?php endif; ?>
  <?php
  $titleId = $id . 'Title';
  if (isset($options['action']))
  {
    $titleId = $id . $options['action'] . 'HolderAction';
  }
  ?>
    <span class="bigger" id="<?php echo $titleId; ?>"><?php echo $options['title']; ?></span>
  <?php if (isset($options['url'])): ?>
    <!-- refresh link -->
    <span id="<?php echo $id; ?>Refresh" class="hidden smaller mar5 dropActions">
      <?php echo $ajax->link('refresh', $options['url'], 
        array('loading' => 'refreshing("' . $id . 'Data")', 'update' =>  $dataId)); ?>
    </span>
  <?php endif; ?>

  </div> <!-- END Title div -->
  
  <!-- Actions and Data div -->
  <div id="<?php echo $id; ?>Bottom" class="hidden">
    <!-- Actions div -->
    <?php if (isset($options['actions'])): ?>
      <?php if (is_array($options['actions'])): ?>
        <?php foreach ($options['actions'] as $title => $url): ?>
          <li><?php echo $html->link($title, $url); ?></li>
        <?php endforeach; ?>
      <?php endif; ?>
      <?php if (!is_array($options['actions'])): ?>
        <?php echo $options['actions']; ?>
      <?php endif; ?>
    <?php endif; ?>

    <!-- Data div -->
    <div id="<?php echo $dataId; ?>" class="dropDownData">
      <?php if ((isset($options['data']) && (strlen($options['data']) > 0))): ?>
        <?php echo $options['data']; ?>
      <?php endif; ?>
    </div>
  </div> <!-- END Bottom -->
</div>
