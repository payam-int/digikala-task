<?php

namespace App\Command;

use App\Service\ElasticSearch\ElasticSearchService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ElasticSearchIndexCommand extends Command
{
    protected static $defaultName = 'elastic-search:index';


    protected $entityManager;
    protected $elasticSearch;

    /**
     * ElasticSearchIndexCommand constructor.
     * @param $entityManager
     * @param $elasticSearch
     */
    public function __construct(EntityManagerInterface $entityManager, ElasticSearchService $elasticSearch)
    {
        $this->entityManager = $entityManager;
        $this->elasticSearch = $elasticSearch;

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Index all selected entities in database')
            ->addArgument('entity_class', InputArgument::REQUIRED, 'Entity class');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $entity_class = $input->getArgument('entity_class');

        $all = $this->entityManager->getRepository($entity_class)->findAll();

        foreach ($all as $product) {
            $this->elasticSearch->index($product);
        }

        $io->success(sprintf('%d entities have been indexed!', count($all)));
    }
}
