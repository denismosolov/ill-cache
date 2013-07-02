<?php

namespace Ill\Cache;

/**
* Incapsulate version and version comparsion. Version number is based on microtime() function.
* @author Denis Mosolov <denismosolov@gmail.com>
* @package Ill Cache
*/
class Version {

	private $_version;

	public function __construct() {
		$this->_version = microtime(true);
	}

	public function getVersion() {
		return $this->_version;
	}

	public function expired(Version $version) {
		return $version->getVersion() > $this->_version;
	}
}