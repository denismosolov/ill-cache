<?php

class TagTest extends PHPUnit_Framework_TestCase {
	
	const TEST_KEY_1 = 'lalala';
	const TEST_KEY_2 = 'haha';
	const TEST_PREFIX_1 = 'jejeje';
	const TEST_PREFIX_2 = 'hehe';

	public function testPrefix() {
		$tag1 = new \Ill\Cache\Tag(self::TEST_KEY_1);
		$this->assertEquals(\Ill\Cache\Tag::DEFAULT_TAG_PREFIX, $tag1->prefix());

		$tag2 = new \Ill\Cache\Tag(self::TEST_KEY_1, self::TEST_PREFIX_1);
		$this->assertEquals(self::TEST_PREFIX_1, $tag2->prefix());
	}

	public function testKey() {
		$tag1 = new \Ill\Cache\Tag(self::TEST_KEY_1);
		$this->assertNotEquals($tag1->key(), self::TEST_KEY_1);
		$this->assertEquals($tag1->key(), \Ill\Cache\Tag::DEFAULT_TAG_PREFIX . self::TEST_KEY_1);

		$tag2 = new \Ill\Cache\Tag(self::TEST_KEY_1, self::TEST_PREFIX_1);
		$this->assertEquals($tag2->key(), self::TEST_PREFIX_1 . self::TEST_KEY_1);
	}

	public function testMatching() {
		$tag1 = new \Ill\Cache\Tag(self::TEST_KEY_1);
		$tag2 = new \Ill\Cache\Tag(self::TEST_KEY_1);
		$this->assertTrue($tag1->match($tag2));
		$this->assertTrue($tag2->match($tag1));

		$tag3 = new \Ill\Cache\Tag(self::TEST_KEY_1, self::TEST_PREFIX_1);
		$this->assertFalse($tag3->match($tag1));
		$this->assertFalse($tag3->match($tag2));
		$this->assertFalse($tag1->match($tag3));
		$this->assertFalse($tag2->match($tag3));

		$tag4 = new \Ill\Cache\Tag(self::TEST_KEY_2);
		$this->assertFalse($tag4->match($tag1));
		$this->assertFalse($tag1->match($tag4));
	}
}