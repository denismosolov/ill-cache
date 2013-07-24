<?php

namespace Ill\Cache;

class Memcached {
	
	/**
	 * Default expired time for keys
	 * number of seconds in 30 days, read http://www.php.net/manual/en/memcached.expiration.php before change the value
	 */
	const DEFAULT_EXPIRED_TIME = 2592000;
	
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

	/**
	 * Stores the $value on a memcache server under the specified $key. 
	 * @param string $key The key under which to store the value
	 * @param mixed The value to store. Can be any valid PHP type except for resources, because those cannot be represented in a serialized form.
	 * @param array $tags can be set of string tags assigned to $data or empty array if no tags assigned
	 * @param int $expiration Can be used to control when the value is considered expired. Defaults to one month
	 * @return boolean Returns TRUE on success or FALSE on failure
	 */
	public function set($key, $value, $tags = array(), $expiration = self::DEFAULT_EXPIRED_TIME) {
		$_tags = array();
		foreach ($tags as $tag) {
			$_tag = new \Ill\Cache\Tag($tag);
			$this->_tagger->register($_tag);
			$_tags[] = $_tag;
		}
        if ($this->_m->set($key, new \Ill\Cache\Container($value, $_tags), $expiration)) {
			$this->_lastSavedValue = $value;
	        $this->_lastSavedKey = $key;
	        $this->_lastSavedTags = $tags;
	        return TRUE;
	    } else {
	    	return FALSE;
	    }
	}

	/**
	 * Returns the item that was previously stored under the key. 
	 * @param string $key The key of the item to retrieve.
	 * @return mixed Returns the value stored in the cache or FALSE otherwise.
	 */
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

	/**
	 * Flush all data which was previously stored with $tag assigned.
	 * @param string $tag
	 * @return boolean Returns TRUE on success or FALSE on failure
	 */
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