<?php
/**
 * User: matteo
 * Date: 03/10/13
 * Time: 22.21
 * Just for fun...
 */

namespace Cypress\FixturesBundle\Fixture;

/**
 * Interface CallableFixtureInterface
 */
interface CallableFixtureInterface
{
    /**
     * @return the name of the service to inject to the load method
     */
    public function getObjectManager();
}
