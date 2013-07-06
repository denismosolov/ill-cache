<?php

class ContainerTest extends PHPUnit_Framework_TestCase {

	public function testContainer() {
		$data = 'blablablabla';
		$tags = array();
		$container = new \Ill\Cache\Container($data, $tags);
		$this->assertEquals($data, $container->data());
		$this->assertEquals($tags, $container->tags());
	}
}