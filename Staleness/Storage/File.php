<?php
/**
 * User: matteo
 * Date: 03/10/13
 * Time: 23.41
 * Just for fun...
 */

namespace Cypress\FixturesBundle\Staleness\Storage;

use Cypress\FixturesBundle\Staleness\Checker;

class File extends Checker
{
    /**
     * @var string
     */
    private $path;

    /**
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * string representing the last modification time
     *
     * @return string
     */
    public function getLastModificationTime()
    {
        if (file_exists($this->path)) {
            return filemtime($this->path);
        }
        $this->setAsModified();

        return 0;
    }

    /**
     * set ad modified
     *
     * @return void
     */
    public function setAsModified()
    {
        touch($this->path);
    }

    /**
     * force the fixture reloading for the next iteration
     *
     * @return mixed
     */
    public function setForceReload()
    {
        if (file_exists($this->path)) {
            unlink($this->path);
        }
    }
}
