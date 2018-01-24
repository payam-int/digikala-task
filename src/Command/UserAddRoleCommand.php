<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserAddRoleCommand extends Command
{
    protected static $defaultName = 'user:add-role';

    protected $entity_manager;

    /**
     * UserAddRoleCommand constructor.
     * @param $entity_manager
     */
    public function __construct(EntityManagerInterface $entity_manager)
    {
        $this->entity_manager = $entity_manager;
        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Add a role to a user.')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('role', InputArgument::REQUIRED, 'Role')
            ->addOption('remove', InputOption::VALUE_NONE, null, 'Remove role');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $role = $input->getArgument('role');

        // Find user
        $users = $this->entity_manager->getRepository(User::class);

        $critiria = ['username' => $username];
        $found_users = $users->findBy($critiria);

        // exit if user not found
        if (count($found_users) < 1) {
            $io->error("User not found!");
            return;
        }

        // add new role
        $user = $found_users[0];
        if (!$input->getOption('remove'))
            $user->addRole($role);
        else
            $user->removeRole($role);

        $this->entity_manager->persist($user);
        $this->entity_manager->flush();

        $io->success(sprintf("Changes commited to %s.", $username));
    }
}
