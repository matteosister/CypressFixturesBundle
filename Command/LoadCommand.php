<?php
/**
 * User: matteo
 * Date: 03/10/13
 * Time: 22.06
 * Just for fun...
 */

namespace Cypress\FixturesBundle\Command;

use Cypress\FixturesBundle\Staleness\StorageInterface;
use Doctrine\Common\DataFixtures\Purger\MongoDBPurger;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

/**
 * Class LoadCommand
 */
class LoadCommand extends ContainerAwareCommand
{
    /**
     * configure
     */
    public function configure()
    {
        $this
            ->setName('cypress:fixtures')
            ->addOption('force', 'f', InputOption::VALUE_NONE)
            ->setDescription('fixtures anti wtf');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('force') && !$this->needsReload()) {
            $output->writeln('<comment>done!</comment>');
            return;
        }
        $ormPurger = new ORMPurger($this->getContainer()->get('doctrine.orm.entity_manager'));
        $output->writeln('<info>Purging mysql</info>');
        $ormPurger->purge();
        /*$odmPurger = new MongoDBPurger($this->getContainer()->get('doctrine_mongodb.odm.document_manager'));
        $output->writeln('<info>Purging mongodb</info>');
        $odmPurger->purge();*/
        $fixtureNames = $this->getContainer()->getParameter('cypress_fixtures.fixtures');
        $lastUpdate = $this->getLastUpdate();
        $references = array();

        foreach ($fixtureNames as $fixtureName) {
            $output->write(str_pad(sprintf('<info>%s</info>', $fixtureName), 100, ".", STR_PAD_RIGHT));
            $fixtureInstance = new $fixtureName;
            $this->checkFixtureClass($fixtureInstance);
            $fixtureInstance->setReferences($references);
            $fixtureInstance->load($this->getContainer()->get($fixtureInstance->getObjectManager()));
            $references = $fixtureInstance->getReferences();
            $output->writeln('<comment>done</comment>');
        }
        $this->getStalenessChecker()->setAsModified();
        $output->writeln('<comment>done!</comment>');
    }

    /**
     * @param $fixtureInstance
     *
     * @throws \InvalidArgumentException
     */
    protected function checkFixtureClass($fixtureInstance)
    {
        $refl = new \ReflectionClass($fixtureInstance);
        if (!array_key_exists('Cypress\FixturesBundle\Fixture\CallableFixtureInterface', $refl->getInterfaces())) {
            $error = 'The fixture class %s must implement the interface
                "Cypress\FixturesBundle\Fixture\CallableFixtureInterface"';
            throw new \InvalidArgumentException(sprintf($error, $refl->getName()));
        }
    }

    /**
     * last update time
     */
    protected function getLastUpdate()
    {
        $watches = $this->getContainer()->getParameter('cypress_fixtures.watches');
        $finder = new Finder();
        $maxDate = 0;
        foreach ($finder->files()->in($watches) as $file) {
            if ($file->getMTime() > $maxDate) {
                $maxDate = $file->getMTime();
            }
        }

        return $maxDate;
    }

    protected function needsReload()
    {
        $lastImport = $this->getStalenessChecker()->getLastModificationTime();
        return $lastImport <= $this->getLastUpdate();
    }

    /**
     * @return StorageInterface
     */
    protected function getStalenessChecker()
    {
        return $this->getContainer()->get('cypress_fixtures.staleness_checker');
    }
}
