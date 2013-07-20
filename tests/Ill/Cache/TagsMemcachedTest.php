<?php

class TagsMemcachedTest extends PHPUnit_Framework_TestCase {

	const TEST_KEY_1 = 'hdjkasd';

	const TEST_VALUE_HTML = '<div class="ill-test">jpod</div>';

        const TEST_TAG_1 = 'Lesson';
        const TEST_TAG_2 = 'Transcript';
        /*
        static public $version1;
        static public $version2;
        static public $tag1;
        static public $tag2;
        static public $tags;
        static public $tagStoraged1;
        static public $tagStoraged2;
*/
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
        /*
        static public function setUpBeforeClass() {
            self::$version1 = new Ill\Cache\Version();
            self::$version2 = new Ill\Cache\Version();
            
            self::$tagStoraged1 = new Ill\Cache\Tag(self::TEST_TAG_1);
            self::$tagStoraged1->setVersion(self::$version1);
            
            self::$tagStoraged2 = new Ill\Cache\Tag(self::TEST_TAG_2);
            self::$tagStoraged2->setVersion(self::$version2);
            
            self::$tag1 = new Ill\Cache\Tag(self::TEST_TAG_1);
            self::$tag1->setVersion(self::$version1);
            self::$tag2 = new Ill\Cache\Tag(self::TEST_TAG_2);
            self::$tag2->setVersion(self::$version2);
            self::$tags = array(
                self::$tag1,
                self::$tag2
            );
        }
        */
        public function testGet1() {

                        
            $memcached = $this->getMock('\Memcached', array('get'));
            /*
            $memcached->expects($this->exactly(3))
                      ->method('get')
                      ->with($this->logicalOr(
                              $this->equalTo(self::TEST_KEY_1),
                              $this->equalTo($tagStoraged1->key()),
                              $this->equalTo($tagStoraged2->key())
                      ))
                      ->will($this->returnCallback(array($this, 'mockMemcachedGetCallback')));
            */
            
            /*
            self::$version1 = new Ill\Cache\Version();
            self::$version2 = new Ill\Cache\Version();
            
            self::$tagStoraged1 = new Ill\Cache\Tag(self::TEST_TAG_1);
            self::$tagStoraged1->setVersion(self::$version1);
            
            self::$tagStoraged2 = new Ill\Cache\Tag(self::TEST_TAG_2);
            self::$tagStoraged2->setVersion(self::$version2);
            
            self::$tag1 = new Ill\Cache\Tag(self::TEST_TAG_1);
            self::$tag1->setVersion(self::$version1);
            self::$tag2 = new Ill\Cache\Tag(self::TEST_TAG_2);
            self::$tag2->setVersion(self::$version2);
            self::$tags = array(
                self::$tag1,
                self::$tag2
            );
            */
            
            $this->version1 = new Ill\Cache\Version();
            $this->version2 = new Ill\Cache\Version();
            
            $this->tagStoraged1 = new Ill\Cache\Tag(self::TEST_TAG_1);
            $this->tagStoraged1->setVersion($this->version1);
            
            $this->tagStoraged2 = new Ill\Cache\Tag(self::TEST_TAG_2);
            $this->tagStoraged2->setVersion($this->version2);
            
            $this->tag1 = new Ill\Cache\Tag(self::TEST_TAG_1);
            $this->tag1->setVersion($this->version1);
            $this->tag2 = new Ill\Cache\Tag(self::TEST_TAG_2);
            $this->tag2->setVersion($this->version2);
            $this->tags = array(
                $this->tag1,
                $this->tag2
            );
            
            $memcached->expects($this->any())
                     ->method('get')
                     ->will($this->returnCallback(array($this, 'mockMemcachedGetCallback')));
            $tagsMemcached = new Ill\Cache\TagsMemcached($memcached);
            $this->assertFalse($tagsMemcached->get(self::TEST_KEY_1));
            
        }
        
        public function mockMemcachedGetCallback($key) {
            switch($key) {
                case self::TEST_KEY_1:
                    return new Ill\Cache\Container(self::TEST_VALUE_HTML, $this->tags);
                default:
                    return FALSE;
            }
        }
}