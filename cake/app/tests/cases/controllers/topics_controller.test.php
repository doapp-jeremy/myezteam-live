<?php 

App::import('Controller', 'Topics');

class TopicsControllerTestCase extends CakeTestCase {
	var $TestObject = null;

	function setUp() {
		$this->TestObject = new TopicsController();
	}

	function tearDown() {
		unset($this->TestObject);
	}

	/*
	function testMe() {
		$result = $this->TestObject->index();
		$expected = 1;
		$this->assertEqual($result, $expected);
	}
	*/
}
?>