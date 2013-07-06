<?php

namespace Ill\Cache;

interface IVersioned {
	public function setVersion(\Ill\Cache\Version $version);
	public function getVersion();
}