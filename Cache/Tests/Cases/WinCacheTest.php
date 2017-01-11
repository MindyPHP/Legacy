<?php

namespace Mindy\Cache;


/**
 * Class for testing wincache backend
 * @group wincache
 * @group caching
 */
class WinCacheTest extends CacheTestCase
{
    private $_cacheInstance = null;

    /**
     * @return \Mindy\Cache\WinCache
     */
    protected function getCacheInstance()
    {
        if (!extension_loaded('wincache')) {
            $this->markTestSkipped("Wincache not installed. Skipping.");
        }

        if (!ini_get('wincache.ucenabled')) {
            $this->markTestSkipped("Wincache user cache disabled. Skipping.");
        }

        if ($this->_cacheInstance === null) {
            $this->_cacheInstance = new WinCache();
        }
        return $this->_cacheInstance;
    }
}
