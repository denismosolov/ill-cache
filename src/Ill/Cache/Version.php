<?php

namespace Ill\Cache;

/**
* Incapsulate version and version comparison. Version implementation is based on
 *  microtime() function.
* @author Denis Mosolov <denismosolov@gmail.com>
* @package Ill Cache
*/
class Version {

	private $_version;

	public function __construct() {
		$this->_version = microtime(true);
	}

	public function get() {
		return $this->_version;
	}

	/**
     * Check if version is expired
	* @return bool - return true if $version is newer than current instance
	*/
	public function expired(Version $version) {
		return (float)$version->get() > (float)$this->get();
	}
}