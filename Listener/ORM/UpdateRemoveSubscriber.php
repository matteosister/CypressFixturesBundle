<?php
/**
 * User: matteo
 * Date: 04/10/13
 * Time: 9.47
 */
namespace Cypress\FixturesBundle\Listener\ORM;

use Cypress\FixturesBundle\Staleness\StorageInterface;
use Doctrine\Common\EventArgs;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

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
        return ['preRemove', 'preUpdate', 'prePersist'];
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $this->storage->setForceReload();
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->storage->setForceReload();
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->storage->setForceReload();
    }
}
