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
    
    public function test1() {
        $tagsMemcached = new Ill\Cache\TagsMemcached($this->_memcached);
        $r = $tagsMemcached->set(self::TEST_KEY_1, self::TEST_VALUE_1, 600);
        $this->assertTrue($r);
        $this->assertEquals($tagsMemcached->lastKey(), self::TEST_KEY_1);
        $this->assertEquals($tagsMemcached->lastValue(), self::TEST_VALUE_1);
        $this->assertEquals($tagsMemcached->lastTags(), array());
        $this->assertEquals($tagsMemcached->get(self::TEST_KEY_1), self::TEST_VALUE_1);
    }
    
    public function test2() {
        $tagsMemcached = new Ill\Cache\TagsMemcached($this->_memcached);
        $tag1 = new Ill\Cache\Tag(self::TEST_TAG_1);
        $tag2 = new Ill\Cache\Tag(self::TEST_TAG_2);
        $tags = array($tag1, $tag2);
        $r = $tagsMemcached->set(self::TEST_KEY_1, self::TEST_VALUE_1, 600, $tags);
        $this->assertTrue($r);
        $this->assertEquals($tagsMemcached->lastKey(), self::TEST_KEY_1);
        $this->assertEquals($tagsMemcached->lastValue(), self::TEST_VALUE_1);
        $this->assertEquals($tagsMemcached->lastTags(), $tags);
        $this->assertFalse($tagsMemcached->get(self::TEST_KEY_1));
    }
}