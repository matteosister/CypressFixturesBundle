<?php
/**
 * User: matteo
 * Date: 03/10/13
 * Time: 22.35
 * Just for fun...
 */


namespace Cypress\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\Common\DataFixtures\SharedFixtureInterface;


abstract class AbstractFixture implements CallableFixtureInterface
{
    /**
     * @var array
     */
    private $references;

    /**
     * Set the reference repository
     *
     * @param array $references
     */
    public function setReferences(&$references)
    {
        $this->references = $references;
    }

    /**
     * Get References
     *
     * @return array
     */
    public function getReferences()
    {
        return $this->references;
    }

    /**
     * @param $name
     * @param $obj
     */
    public function setReference($name, $obj)
    {
        $this->references[$name] = $obj;
    }

    /**
     * @param $name
     *
     * @throws \InvalidArgumentException
     * @return
     */
    public function getReference($name)
    {
        if (!array_key_exists($name, $this->references)) {
            throw new \InvalidArgumentException(
                sprintf('You don\'t have a reference named "%s" in your fixtures', $name)
            );
        }
        return $this->references[$name];
    }
}
