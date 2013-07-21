<?php

class TagsMemcachedTest extends PHPUnit_Framework_TestCase {

	const TEST_KEY_1 = 'hdjkasd';

	const TEST_VALUE_HTML = '<div class="ill-test">jpod</div>';

        const TEST_TAG_1 = 'Lesson';
        const TEST_TAG_2 = 'Transcript';

        public function testSet1() {
		$key = self::TEST_KEY_1;
		$tags = array();
		$value = self::TEST_VALUE_HTML;

		$memcached = $this->getMock('\Memcached', array('set'));
		$memcached->expects($this->once())->method('set')->with($this->equalTo($key), $this->equalTo(new \Ill\Cache\Container($value, $tags)))->will($this->returnValue(TRUE));
		$tagsMemcached1 = new \Ill\Cache\TagsMemcached($memcached);
		$this->assertTrue($tagsMemcached1->set($key, $value, 0, $tags));
		$this->assertEquals($key, $tagsMemcached1->lastKey());
		$this->assertEquals($value, $tagsMemcached1->lastValue());
		$this->assertEquals($tags, $tagsMemcached1->lastTags());
	}
        
        public function testTagExpired1() {            
            $tagStoraged = new \Ill\Cache\Tag(self::TEST_TAG_1);
            $tagStoraged->setVersion(new \Ill\Cache\Version());
            $newTag = new \Ill\Cache\Tag(self::TEST_TAG_1);
            $newTag->setVersion(new \Ill\Cache\Version());
            $memcached = $this->getMock('\Memcached', array('get'));
            $memcached->expects($this->once())->method('get')->with($this->equalTo($newTag->key()))->will($this->returnValue($tagStoraged));
            $tagsMemcached1 = new \Ill\Cache\TagsMemcached($memcached);
            $this->assertTrue($tagsMemcached1->tagExpired($newTag));
        }
        
        public function testTagExpired2() {
            $this->setExpectedException('\RuntimeException', Ill\Cache\TagsMemcached::RUNTIME_EX_MESSAGE_BAD_VERSION_CLASS, Ill\Cache\TagsMemcached::RUNTIME_EX_CODE_BAD_VERSION_STORAGED);
            $tagStoraged = new \Ill\Cache\Tag(self::TEST_TAG_1);
            $newTag = new \Ill\Cache\Tag(self::TEST_TAG_1);
            $newTag->setVersion(new \Ill\Cache\Version());
            $memcached = $this->getMock('\Memcached', array('get'));
            $memcached->expects($this->once())->method('get')->with($this->equalTo($newTag->key()))->will($this->returnValue($tagStoraged));
            $tagsMemcached1 = new \Ill\Cache\TagsMemcached($memcached);
            $tagsMemcached1->tagExpired($newTag);
        }
        
        public function testTagExpired3() {
            $this->setExpectedException('\RuntimeException', Ill\Cache\TagsMemcached::RUNTIME_EX_MESSAGE_BAD_VERSION_CLASS, Ill\Cache\TagsMemcached::RUNTIME_EX_CODE_BAD_VERSION_CHECKED);
            $tagStoraged = new \Ill\Cache\Tag(self::TEST_TAG_1);
            $tagStoraged->setVersion(new \Ill\Cache\Version());
            $newTag = new \Ill\Cache\Tag(self::TEST_TAG_1);
            $memcached = $this->getMock('\Memcached', array('get'));
            $memcached->expects($this->once())->method('get')->with($this->equalTo($newTag->key()))->will($this->returnValue($tagStoraged));
            $tagsMemcached1 = new \Ill\Cache\TagsMemcached($memcached);
            $tagsMemcached1->tagExpired($newTag);
        }
}