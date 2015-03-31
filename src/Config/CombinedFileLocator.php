<?php

/**
 * This file is part of Contao.
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao\CoreBundle\Config;

use Symfony\Component\Config\FileLocatorInterface;

/**
 * Tries to locate a combined file.
 *
 * @author Andreas Schempp <https://github.com/aschempp>
 */
class CombinedFileLocator implements FileLocatorInterface
{
    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @var FileLocatorInterface
     */
    private $locator;

    /**
     * Constructor.
     *
     * @param string               $cacheDir The path where combined files are stored
     * @param FileLocatorInterface $locator  A file locator to locate files if the cache is not found
     */
    public function __construct($cacheDir, FileLocatorInterface $locator = null)
    {
        $this->cacheDir = $cacheDir;
        $this->locator  = $locator;
    }

    /**
     * {@inheritdoc}
     */
    public function locate($name, $currentPath = null, $first = false)
    {
        // FIXME: we should inject this configuration
        if (!\Config::get('bypassCache') && file_exists($cacheFile = $this->cacheDir . "/$name")) {
            return $first ? $cacheFile : [$cacheFile];
        }

        if (null === $this->locator) {
            throw new \RuntimeException('No file locator given');
        }

        if ($first) {
            // FIXME: what if the cache file does not exists?
            // Why don't we call $this->locator->locate($name, $currentPath, true)?
            throw new \InvalidArgumentException("The file $name does not exist in {$this->cacheDir}");
        }

        return $this->locator->locate($name, $currentPath, false);
    }
}