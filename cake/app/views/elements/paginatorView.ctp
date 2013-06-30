<?php // views/elements/paginatorView.ctp : renders a paginator view
//$loadingDivId : (optional) : if set, will use that id as the loading div id
//$loadingData : (optional) : if set, will use it as the innerHTMl of the loading div -- ignored if $loadingDivId is set
//$ajaxOptions : (optional -- if not set, $url must be) : paginator options
//$url : (optional -- if not set, $ajaxOptions must be) : url to paginate, if not set, pa
//$pagingDivId: (optional) : if set, won't create a div for the paging
//$elementName : (required) : name of element to render
//$elementOptions : (required) : element options
?>

<?php
echo $javascript->link('prototype');

//$defaultLoadingDivId = 'loadingDiv' . rand();
$defaultLoadingDivId = 'loader';
$defaultPagingDivId = 'pagingData' . rand();

$createLoadingDiv = false;
if (!isset($loadingDivId))
{
  $createLoadingDiv = true;
  $loadingDivId = $defaultLoadingDivId;
}
$createPagingDiv = false;
if (!isset($pagingDivId))
{
  $createPagingDiv = true;
  $pagingDivId = $defaultPagingDivId;
}
$defaultOptions = array('update' => $pagingDivId, 'indicator' => $loadingDivId);
?>

<?php if (false): ?>
<?php if ($createLoadingDiv): ?>
<div id="<?php echo $loadingDivId; ?>1" style="display: none;">
<?php
if (isset($loadingDivData))
{
  echo $loadingDivData;
}
else
{
  echo 'loading...';
}
?>
</div>
<?php endif; ?>
<?php endif; ?>
<?php
if (isset($ajaxOptions))
{
  $ajaxOptions = array_merge($defaultOptions, $ajaxOptions);
}
else
{
  $ajaxOptions = $defaultOptions;
  $ajaxOptions['url'] = $url;
}

$paginator->options($ajaxOptions);
?>

<?php if ($createPagingDiv): ?>
<div id=<?php echo $pagingDivId; ?>>
<?php endif; ?>

<?php
echo $paging->counter();
echo $paging->links();
echo $this->element($elementName, $elementOptions);
echo $paging->links();
echo $paging->counter();
?>

<?php if ($createPagingDiv): ?>
</div>
<?php endif; ?>
