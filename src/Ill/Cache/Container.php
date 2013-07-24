<?php 

namespace Ill\Cache;

/**
 * Container for storing data in key-value storage. 
 * Keeps data with related tags.
 * @author Denis Mosolov <denismosolov@gmail.com>
 * @package Ill Cache
 */
class Container {

	private $_data;

	private $_tags;

	public function __construct($data, $tags = array()) {
		$this->_data = $data;
		$this->_tags = $tags;
	}

    /**
     * 
     * @return mixed - stored data
     */
	public function data() {
		return $this->_data;
	}

    /**
     * 
     * @return array - tags assigned to data
     */
	public function tags() {
		return $this->_tags;
	}
}