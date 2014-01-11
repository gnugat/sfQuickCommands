<?php

/*
 * This file is part of the Fossil project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Fossil\Model;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Wraps documentation files, generated using skeleton files.
 *
 * @author Loïc Chardonnet <loic.chardonnet@gmail.com>
 */
class Documentation
{
    const DIRECTORY_MODE = 0755;

    const FILE_MODE = 0644;

    /** @var Filesystem */
    private $filesystem;

    /** @var string */
    private $absolutePathname;

    /** @var string */
    private $content;

    /**
     * @param Filesystem $filesystem
     * @param string     $absolutePathname
     * @param string     $content
     * @param boll       $shouldOverwrite
     */
    public function __construct(Filesystem $filesystem, $absolutePathname, $content)
    {
        $this->filesystem = $filesystem;
        $this->absolutePathname = $absolutePathname;
        $this->content = $content;
    }

    /** @param bool $shouldOverwrite */
    public function write($shouldOverwrite = false)
    {
        $absolutePath = $this->getAbsolutePath();

        if (!$this->filesystem->exists($absolutePath)) {
            $this->filesystem->mkdir($absolutePath, self::DIRECTORY_MODE);
        }

        if (!$this->filesystem->exists($this->absolutePathname) || $shouldOverwrite) {
            $this->filesystem->dumpFile($this->absolutePathname, $this->content, self::FILE_MODE);
        }
    }

    /** @return string */
    private function getAbsolutePath()
    {
        $absolutePathname = $this->absolutePathname;
        $pathPieces = explode('/', $absolutePathname);
        array_pop($pathPieces);
        $absolutePath = implode('/', $pathPieces);

        return $absolutePath;
    }
}
