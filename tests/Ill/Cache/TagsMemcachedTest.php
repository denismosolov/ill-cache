<?php

class TagsMemcachedTest extends PHPUnit_Framework_TestCase {

	const TEST_KEY_1 = 'hdjkasd';

	const TEST_VALUE_HTML = '<div class="ill-test">jpod</div>';

	public function testSet1() {
		$key = self::TEST_KEY_1;
		$tags = array();
		$value = self::TEST_VALUE_HTML;

		$memcached = $this->getMock('\Memcached', array('set'));
		$t = new \Ill\Cache\Container($value, $tags);
		$memcached->expects($this->once())->method('set')->with($this->equalTo($key), $this->equalTo(new \Ill\Cache\Container($value, $tags)))->will($this->returnValue(TRUE));
		$tagsMemcached1 = new \Ill\Cache\TagsMemcached($memcached);
		$this->assertTrue($tagsMemcached1->set($key, $value, 0, $tags));
		$this->assertEquals($key, $tagsMemcached1->lastKey());
		$this->assertEquals($value, $tagsMemcached1->lastValue());
		$this->assertEquals($tags, $tagsMemcached1->lastTags());
	}
}