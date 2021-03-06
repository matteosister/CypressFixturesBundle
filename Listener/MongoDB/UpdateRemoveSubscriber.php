<?php
/**
 * User: matteo
 * Date: 04/10/13
 * Time: 9.47
 */
namespace Cypress\FixturesBundle\Listener\MongoDB;

use Cypress\FixturesBundle\Staleness\StorageInterface;
use Doctrine\Common\EventArgs;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;

/**
 * Class DoctrineListener
 */
class UpdateRemoveSubscriber implements EventSubscriber
{
    /**
     * @var \Cypress\FixturesBundle\Staleness\StorageInterface
     */
    private $storage;

    /**
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return ['preRemove'];
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $this->storage->setForceReload();
    }
}
