<?php

namespace App\Command;

use App\Service\ElasticSearch\ElasticSearchService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ElasticSearchInstallCommand extends Command
{
    protected static $defaultName = 'elastic-search:install';

    private $elastic_search;


    /**
     * ElasticSearchInstallCommand constructor.
     * @param $elasticsearch
     */
    public function __construct(ElasticSearchService $elasticsearch)
    {
        $this->elastic_search = $elasticsearch;

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('This command installs Elastic search');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $this->elastic_search->putMappings();
        $io->success('Elastic search installed successfully.');
    }
}
