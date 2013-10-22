<?php
/**
 * User: matteo
 * Date: 03/10/13
 * Time: 23.41
 * Just for fun...
 */

namespace Cypress\FixturesBundle\Staleness;

interface StorageInterface
{
    /**
     * string representing the last modification time
     *
     * @return string
     */
    public function getLastModificationTime();

    /**
     * set ad modified
     *
     * @return void
     */
    public function setAsModified();

    /**
     * force the fixture reloading for the next iteration
     *
     * @return mixed
     */
    public function setForceReload();
}
