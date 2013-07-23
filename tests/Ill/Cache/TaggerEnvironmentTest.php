<?php

class TaggerEnvironmentTest extends PHPUnit_Framework_TestCase {
    
    const TEST_TAG_1 = 'TAg::1';
    const TEST_TAG_2 = 'Tag::2';
    
    protected $_memcached;
    
    protected function setUp() {
        global $memcached_host, $memcached_port;
        $this->_memcached = new \Memcached();
        $this->_memcached->addServer($memcached_host, $memcached_port);
        $this->_memcached->flush();
    }
    
    protected function tearDown() {
        $this->_memcached->flush();
    }

    public function test1() {
        $tagger = new \Ill\Cache\Tagger($this->_memcached);
        $this->assertFalse($tagger->getRegistered(new \Ill\Cache\Tag(self::TEST_TAG_1)));
    }

    public function test2() {
        $tagger = new \Ill\Cache\Tagger($this->_memcached);
        $tag1 = new \Ill\Cache\Tag(self::TEST_TAG_1);
        $tag2 = new \Ill\Cache\Tag(self::TEST_TAG_2);
        $this->assertTrue($tagger->expired($tag1));
        $this->assertTrue($tagger->expired($tag2));
    }

    public function test3() {
        $tagger = new \Ill\Cache\Tagger($this->_memcached);
        $tag1 = new \Ill\Cache\Tag(self::TEST_TAG_1);
        $tagger->register($tag1);
        $this->assertFalse($tagger->expired($tag1));
    }

    public function test4() {
        $tagger = new \Ill\Cache\Tagger($this->_memcached);
        $tag1 = new \Ill\Cache\Tag(self::TEST_TAG_1);
        $tag1u = new \Ill\Cache\Tag(self::TEST_TAG_1);
        $tagger->register($tag1);
        $tagger->register($tag1u);
        $this->assertFalse($tagger->expired($tag1));
        $this->assertFalse($tagger->expired($tag1u));
        $tag1->setVersion(new \Ill\Cache\Version());
        $tagger->set($tag1);
        $this->assertFalse($tagger->expired($tag1));
        $this->assertTrue($tagger->expired($tag1u));
    }
}