<?php

namespace Ill\Cache;

/**
* Incapsulate tags version control operations.
* @author Denis Mosolov <denismosolov@gmail.com>
* @package Ill Cache
*/
class Tagger {
	
    const RUNTIME_EX_CODE_BAD_TAG_CLASS = '1';
    const RUNTIME_EX_CODE_BAD_VERSION_STORAGED = '2';
    const RUNTIME_EX_CODE_BAD_VERSION_CHECKED = '3';
    const RUNTIME_EX_MESSAGE_BAD_TAG_CLASS = '\Ill\Cache\Tag is expected.';
    const RUNTIME_EX_MESSAGE_BAD_VERSION_CLASS = '\Ill\Cache\Version is expected.';

	private $_m;

	public function __construct(\Memcached $memcached) {
		$this->_m = $memcached;
	}

	public function getRegistered(\Ill\Cache\Tag $tag) {
		return $this->_m->get($tag->key());
	}

	public function set(\Ill\Cache\Tag $tag) {
		$version = $tag->getVersion();
		if (! $version instanceof \Ill\Cache\Version) {
			throw new \RuntimeException(self::RUNTIME_EX_MESSAGE_BAD_VERSION_CLASS, self::RUNTIME_EX_CODE_BAD_VERSION_CHECKED);
		}
		return $this->_m->set($tag->key(), $tag);
	}

	public function expired(\Ill\Cache\Tag $checkedTag) {
        $storagedTag = $this->getRegistered($checkedTag);
        if ($storagedTag === FALSE) {
            return TRUE;
        }
        if (! $storagedTag instanceof \Ill\Cache\Tag) {
            throw new \RuntimeException(self::RUNTIME_EX_MESSAGE_BAD_TAG_CLASS, self::RUNTIME_EX_CODE_BAD_TAG_CLASS);
        }
        $storagedVersion = $storagedTag->getVersion();
        if (! $storagedVersion instanceof \Ill\Cache\Version) {
            throw new \RuntimeException(self::RUNTIME_EX_MESSAGE_BAD_VERSION_CLASS, self::RUNTIME_EX_CODE_BAD_VERSION_STORAGED);
        }
        $checkedVersion = $checkedTag->getVersion();
        if (! $checkedVersion instanceof \Ill\Cache\Version) {
            throw new \RuntimeException(self::RUNTIME_EX_MESSAGE_BAD_VERSION_CLASS, self::RUNTIME_EX_CODE_BAD_VERSION_CHECKED);
        }
        return $checkedVersion->expired($storagedVersion);
	}

	public function register(\Ill\Cache\Tag $tag) {
		$storaged = $this->getRegistered($tag);
		if ($storaged === FALSE) {
			$tag->setVersion(new \Ill\Cache\Version());
			return $this->set($tag);
		}
		if (! $storaged instanceof \Ill\Cache\Tag) {
			throw new \RuntimeException(self::RUNTIME_EX_MESSAGE_BAD_TAG_CLASS, self::RUNTIME_EX_CODE_BAD_TAG_CLASS);
		}
		if (! $storaged->getVersion() instanceof \Ill\Cache\Version) {
			throw new \RuntimeException(self::RUNTIME_EX_MESSAGE_BAD_VERSION_CLASS, self::RUNTIME_EX_CODE_BAD_VERSION_STORAGED);
		}
		$tag->setVersion($storaged->getVersion());
	}
}