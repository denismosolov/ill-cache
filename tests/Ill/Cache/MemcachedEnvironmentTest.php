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
        $this->assertTrue($tagsMemcached->set(self::TEST_KEY_1, self::TEST_VALUE_1, 10));
        $this->assertEquals($tagsMemcached->lastKey(), self::TEST_KEY_1);
        $this->assertEquals($tagsMemcached->lastValue(), self::TEST_VALUE_1);
        $this->assertEquals($tagsMemcached->lastTags(), array());
        $this->assertEquals($tagsMemcached->get(self::TEST_KEY_1), self::TEST_VALUE_1);
    }
    
    public function test2() {
        $tagsMemcached = new Ill\Cache\Memcached($this->_memcached);
        $tags = array(self::TEST_TAG_1, self::TEST_TAG_2);
        $this->assertTrue($tagsMemcached->set(self::TEST_KEY_1, self::TEST_VALUE_1, 10, $tags));
        $this->assertEquals($tagsMemcached->lastKey(), self::TEST_KEY_1);
        $this->assertEquals($tagsMemcached->lastValue(), self::TEST_VALUE_1);
        $this->assertEquals($tagsMemcached->lastTags(), $tags);
        $this->assertEquals($tagsMemcached->get(self::TEST_KEY_1), self::TEST_VALUE_1);
        $this->assertEquals($tagsMemcached->get(self::TEST_KEY_1), self::TEST_VALUE_1);
    }
    
    public function test3() {
        $tagsMemcached = new Ill\Cache\Memcached($this->_memcached);
        $tags = array(self::TEST_TAG_1, self::TEST_TAG_2);
        $this->assertTrue($tagsMemcached->set(self::TEST_KEY_1, self::TEST_VALUE_1, 10, $tags));
        $this->assertEquals($tagsMemcached->lastKey(), self::TEST_KEY_1);
        $this->assertEquals($tagsMemcached->lastValue(), self::TEST_VALUE_1);
        $this->assertEquals($tagsMemcached->lastTags(), $tags);
        $this->assertEquals($tagsMemcached->get(self::TEST_KEY_1), self::TEST_VALUE_1);
        $tagsMemcached->incTagVersion(self::TEST_TAG_2);
        $this->assertFalse($tagsMemcached->get(self::TEST_KEY_1));
    }
}