<?php 

namespace Ill\Cache;

class Container {

	private $_data;

	private $_tags;

	public function __construct($data, $tags = array()) {
		$this->_data = $data;
		$this->_tags = $tags;
	}

	public function data() {
		return $this->_data;
	}

	public function tags() {
		return $this->_tags;
	}
}