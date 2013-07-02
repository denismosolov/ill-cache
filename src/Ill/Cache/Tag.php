<?php

namespace Ill\Cache;

/**
* Incapsulate tagging and tag matching. It match keys like a strings.
* @author Denis Mosolov <denismosolov@gmail.com>
* @package Ill Cache
*/
class Tag {
	
	const DEFAULT_TAG_PREFIX = 'Ill:tag::';

	private $_prefix;

	private $_key;

	public function __construct($tag, $prefix = self::DEFAULT_TAG_PREFIX) {
		$this->_prefix = $prefix;
		$this->_key = $prefix . $tag;
	}

	public function match(Tag $tag) {
		return (string) $this->key() === (string) $tag->key();
	}

	public function key() {
		return $this->_key;
	}

	public function prefix() {
		return $this->_prefix;
	}
}