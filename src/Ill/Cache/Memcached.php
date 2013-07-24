<?php

namespace Ill\Cache;

class Memcached {
	
	private $_m;

	private $_lastSavedKey;

	private $_lastSavedValue;
	
	private $_lastSavedTags;

	private $_tagger;
        
	public function __construct(\Memcached $memcached) {
		$this->_m = $memcached;
		$this->_lastSavedKey = NULL;
		$this->_lastSavedValue = NULL;
		$this->_lastSavedTags = NULL;
		$this->_tagger = new \Ill\Cache\Tagger($memcached);
	}

	public function set($key, $value, $expired, $tags = array()) {
		$_tags = array();
		foreach ($tags as $tag) {
			$_tag = new \Ill\Cache\Tag($tag);
			$this->_tagger->register($_tag);
			$_tags[] = $_tag;
		}
        if ($this->_m->set($key, new \Ill\Cache\Container($value, $_tags), $expired)) {
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
		if ($container instanceof \Ill\Cache\Container) {
			foreach ($container->tags() as $tag) {
                if ($this->_tagger->expired($tag)) {
                    return FALSE;
                }
			}
            return $container->data();
		} else {
			return FALSE;
		}
	}

	public function incTagVersion($tag) {
		$_tag = new \Ill\Cache\Tag($tag);
		$_tag->setVersion(new \Ill\Cache\Version()); // inc version
		return $this->_tagger->set($_tag);
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