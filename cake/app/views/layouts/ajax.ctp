<?php
/* SVN FILE: $Id: ajax.ctp 6311 2008-01-02 06:33:52Z phpnut $ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *			1785 E. Sahara Avenue, Suite 490-204
 *			Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.cake.libs.view.templates.layouts
 * @since			CakePHP(tm) v 0.10.0.1076
 * @version			$Revision: 6311 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2008-01-02 00:33:52 -0600 (Wed, 02 Jan 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<?php echo $content_for_layout; ?>
<?php
//if (!isset($servingAds))
//{
//  $numAds = 2;
//  for ($i = 1; $i <= $numAds; ++$i)
//  {
//    $options = array(
//      'update' => 'ad' . $i,
//      'url' => array('controller' => 'messages', 'action' => 'ads', $i),
//      'evalScripts' => true
//    );
//    echo '<script type="text/javascript">';
//    echo $ajax->remoteFunction($options);
//    echo '</script>';
//  }
//}
?>

<?php if (isset($refresh)): ?>
  <script type="text/javascript">
    <?php
      echo $ajax->remoteFunction($refresh);
    ?>
  </script>
<?php endif; ?>