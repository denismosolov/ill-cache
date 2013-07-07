<?php

namespace Ill\Cache;

class TagsMemcached {
	
	private $_m;

	private $_lastSavedKey;

	private $_lastSavedValue;
	
	private $_lastSavedTags;

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
		$data = $this->_m->get($key);
		if ($data instanceof \Ill\Cache\Container) {
			foreach ($data->tags() as $tag) {
				// todo: check tags
				$tv = $tag->getTagVersion();
				if ($this->_m->get($tag->key())) {
					return FALSE;
				}
			}
		} else {
			return FALSE;
		}
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