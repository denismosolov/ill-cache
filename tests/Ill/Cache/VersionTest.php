<?php 

class VersionTest extends PHPUnit_Framework_TestCase {
	
	public function testVersionInit() {
		$version = new \Ill\Cache\Version();
		$this->assertTrue(is_float($version->getVersion()));
	}

	public function testVersionIncrement() {
		$version = new \Ill\Cache\Version();
		$this->assertTrue($version->expired(new \Ill\Cache\Version()));
	}
}