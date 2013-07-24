<?php

namespace Ill\Cache;

/**
* Tag implementation
* @author Denis Mosolov <denismosolov@gmail.com>
* @package Ill Cache
*/
class Tag {
	
    /**
     * default prefix for storing tags
     */
	const DEFAULT_TAG_PREFIX = 'Ill:tag::';

    /**
     * First part of key
     * @var string
     */
	private $_prefix;

    /**
     * Key is used to storring Tag in key-value storage
     * @var string
     */
	private $_key;

    /**
     * Tag version
     * @var float
     */
	private $_version;

	public function __construct($tag, $prefix = self::DEFAULT_TAG_PREFIX) {
		$this->_prefix = $prefix;
		$this->_key = $prefix . $tag;
	}

    /**
     * Check if the tag is equal to another tag. 
     * Attention plese! It compares only keys, versions doesn't matter here
     * @param \Ill\Cache\Tag $tag
     * @return bool TRUE - tags is equal, FALSE - tags is not equals
     */
	public function match(Tag $tag) {
		return (string) $this->key() === (string) $tag->key();
	}

    /**
     * @return string - key with prefix
     */
	public function key() {
		return $this->_key;
	}

    /**
     * @return string - prefix for tag
     */
	public function prefix() {
		return $this->_prefix;
	}

    /**
     * Setup version for the tag
     * @param \Ill\Cache\Version $version
     */
	public function setVersion(\Ill\Cache\Version $version) {
		$this->_version = $version;
	}

    /**
     * 
     * @return Ill\Cache\Version|NULL - tag version, NULL if version was not assigned to the tag
     */
	public function getVersion() {
		return $this->_version;
	}
}