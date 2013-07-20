<?php

namespace Ill\Cache;

class TagsMemcached {
	
	private $_m;

	private $_lastSavedKey;

	private $_lastSavedValue;
	
	private $_lastSavedTags;

        const RUNTIME_EX_CODE_BAD_TAG_CLASS = '1';
        const RUNTIME_EX_CODE_BAD_VERSION_STORAGED = '2';
        const RUNTIME_EX_CODE_BAD_VERSION_CHECKED = '3';
        const RUNTIME_EX_MESSAGE_BAD_TAG_CLASS = '\Ill\Cache\Tag is expected.';
        const RUNTIME_EX_MESSAGE_BAD_VERSION_CLASS = '\Ill\Cache\Version is expected.';
        
	public function __construct(\Memcached $memcached) {
		$this->_m = $memcached;
		$this->_lastSavedKey = NULL;
		$this->_lastSavedValue = NULL;
		$this->_lastSavedTags = NULL;
	}

	public function set($key, $value, $expired, $tags = array()) {
        if ($this->_m->set($key, new \Ill\Cache\Container($value, $tags), $expired)) {
			$this->_lastSavedValue = $value;
	        $this->_lastSavedKey = $key;
	        $this->_lastSavedTags = $tags;
	        return TRUE;
	    } else {
	    	return FALSE;
	    }
	}

	public function get($key) {
		$container = $this->_m->get($key);
                return FALSE;
		if ($container instanceof \Ill\Cache\Container) {
			foreach ($container->tags() as $tag) {
                                if ($this->tagExpired($tag)) {
                                    return FALSE;
                                }
			}
                        return $container->data();
		} else {
			return FALSE;
		}
	}

        public function tagExpired(\Ill\Cache\Tag $checkedTag) {
            $storagedTag = $this->_m->get($checkedTag->key());
            if (! $storagedTag instanceof \Ill\Cache\Tag) {
                throw new \RuntimeException(self::RUNTIME_EX_MESSAGE_BAD_TAG_CLASS, self::RUNTIME_EX_CODE_BAD_TAG_CLASS);
            }
            $storagedVersion = $storagedTag->getVersion();
            if (! $storagedVersion instanceof \Ill\Cache\Version) {
                throw new \RuntimeException(self::RUNTIME_EX_MESSAGE_BAD_VERSION_CLASS, self::RUNTIME_EX_CODE_BAD_VERSION_STORAGED);
            }
            $checkedVersion = $checkedTag->getVersion();
            if (! $checkedVersion instanceof \Ill\Cache\Version) {
                throw new \RuntimeException(self::RUNTIME_EX_MESSAGE_BAD_VERSION_CLASS, self::RUNTIME_EX_CODE_BAD_VERSION_CHECKED);
            }
            return $storagedVersion->expired($checkedVersion);
        }
        
	public function lastValue() {
		return $this->_lastSavedValue;
	}

	public function lastKey() {
		return $this->_lastSavedKey;
	}

	public function lastTags() {
		return $this->_lastSavedTags;
	}
}