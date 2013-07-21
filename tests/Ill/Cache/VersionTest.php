<?php 

class VersionTest extends PHPUnit_Framework_TestCase {
	
	public function testVersionInit() {
		$version = new \Ill\Cache\Version();
		$this->assertTrue(is_float($version->get()));
	}

	public function testVersionIncrement() {
		$version1 = new \Ill\Cache\Version();
        $version2 = new Ill\Cache\Version();
		$this->assertTrue($version1->expired($version2));
        $this->assertFalse($version2->expired($version1));
	}
}