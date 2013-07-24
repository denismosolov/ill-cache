<?php

class MemcachedEnvironmentTest extends PHPUnit_Framework_TestCase {
    
    const TEST_KEY_1 = 'Key::1';
    const TEST_TAG_1 = 'TAg::1';
    const TEST_TAG_2 = 'Tag::2';
    const TEST_VALUE_1 = '<span>Hello</span>';
    
    protected $_memcached;
    
    protected function setUp() {
        global $memcached_host, $memcached_port;
        $this->_memcached = new \Memcached();
        $this->_memcached->addServer($memcached_host, $memcached_port);
    }
    
    protected function tearDown() {
        $this->_memcached->flush();
    }

        public function test1() {
        $tagsMemcached = new Ill\Cache\Memcached($this->_memcached);
        $r = $tagsMemcached->set(self::TEST_KEY_1, self::TEST_VALUE_1, 10);
        $this->assertTrue($r);
        $this->assertEquals($tagsMemcached->lastKey(), self::TEST_KEY_1);
        $this->assertEquals($tagsMemcached->lastValue(), self::TEST_VALUE_1);
        $this->assertEquals($tagsMemcached->lastTags(), array());
        $this->assertEquals($tagsMemcached->get(self::TEST_KEY_1), self::TEST_VALUE_1);
    }
    
    public function test2() {
        $tagsMemcached = new Ill\Cache\Memcached($this->_memcached);
        $tag1 = new Ill\Cache\Tag(self::TEST_TAG_1);
        $tag2 = new Ill\Cache\Tag(self::TEST_TAG_2);
        $tags = array($tag1, $tag2);
        $this->assertTrue($tagsMemcached->set(self::TEST_KEY_1, self::TEST_VALUE_1, 10, $tags));
        $this->assertEquals($tagsMemcached->lastKey(), self::TEST_KEY_1);
        $this->assertEquals($tagsMemcached->lastValue(), self::TEST_VALUE_1);
        $this->assertEquals($tagsMemcached->lastTags(), $tags);
        $this->assertFalse($tagsMemcached->get(self::TEST_KEY_1));
        $this->assertFalse($tagsMemcached->get(self::TEST_KEY_1));
        $tagsMemcached->register($tag1);
        $tagsMemcached->register($tag2);
        $this->setExpectedException('RuntimeException', Ill\Cache\Tagger::RUNTIME_EX_MESSAGE_BAD_VERSION_CLASS);
        $tagsMemcached->get(self::TEST_KEY_1);
    }
    
    public function test3() {
        $tagsMemcached = new Ill\Cache\Memcached($this->_memcached);
        $version1 = new Ill\Cache\Version();
        $version2 = new Ill\Cache\Version();
        $this->assertTrue($version1->expired($version2));
        $this->assertFalse($version2->expired($version1));
        $tag1 = new Ill\Cache\Tag(self::TEST_TAG_1);
        $tagsMemcached->register($tag1);
        $tag2 = new Ill\Cache\Tag(self::TEST_TAG_2);
        $tagsMemcached->register($tag2);
        $tags = array($tag1, $tag2);
        $this->assertTrue($tagsMemcached->set(self::TEST_KEY_1, self::TEST_VALUE_1, 10, $tags));
        $this->assertEquals($tagsMemcached->lastKey(), self::TEST_KEY_1);
        $this->assertEquals($tagsMemcached->lastValue(), self::TEST_VALUE_1);
        $this->assertEquals($tagsMemcached->lastTags(), $tags);
        $tagsMemcached->register($tag1);
        $tagsMemcached->register($tag2);
        $this->assertEquals($tagsMemcached->get(self::TEST_KEY_1), self::TEST_VALUE_1);
        $version3 = new Ill\Cache\Version();
        $this->assertTrue($version1->expired($version3));
        $this->assertTrue($version2->expired($version3));
        $tag1->setVersion($version3);
        $tagsMemcached->update($tag1);
        $this->assertFalse($tagsMemcached->get(self::TEST_KEY_1));
    }
}