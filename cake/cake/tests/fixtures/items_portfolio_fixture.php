<?php
/* SVN FILE: $Id: items_portfolio_fixture.php 6311 2008-01-02 06:33:52Z phpnut $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <https://trac.cakephp.org/wiki/Developement/TestSuite>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				https://trac.cakephp.org/wiki/Developement/TestSuite CakePHP(tm) Tests
 * @package			cake.tests
 * @subpackage		cake.tests.fixtures
 * @since			CakePHP(tm) v 1.2.0.4667
 * @version			$Revision: 6311 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2008-01-02 00:33:52 -0600 (Wed, 02 Jan 2008) $
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/**
 * Short description for class.
 *
 * @package		cake.tests
 * @subpackage	cake.tests.fixtures
 */
class ItemsPortfolioFixture extends CakeTestFixture {
	var $name = 'ItemsPortfolio';
	var $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary', 'extra'=> 'auto_increment'),
		'item_id' => array('type' => 'integer', 'null' => false),
		'portfolio_id' => array('type' => 'integer', 'null' => false)
	);
	var $records = array(
		array ('id' => 1, 'item_id' => 1, 'portfolio_id' => 1),
		array ('id' => 2, 'item_id' => 2, 'portfolio_id' => 2),
		array ('id' => 3, 'item_id' => 3, 'portfolio_id' => 1),
		array ('id' => 4, 'item_id' => 4, 'portfolio_id' => 1),
		array ('id' => 5, 'item_id' => 5, 'portfolio_id' => 1),
		array ('id' => 6, 'item_id' => 6, 'portfolio_id' => 2)
	);
}
?>