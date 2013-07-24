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

	public function update(\Ill\Cache\Tag $tag) {
		return $this->_tagger->set($tag);
	}

	public function register(\Ill\Cache\Tag $tag) {
		return $this->_tagger->register($tag);
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