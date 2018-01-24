<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserChangePasswordCommand extends Command
{
    protected static $defaultName = 'user:change-password';

    protected $entity_manager;
    protected $password_encoder;

    /**
     * UserAddRoleCommand constructor.
     * @param $entity_manager
     * @param $passwordEncoder
     */
    public function __construct(EntityManagerInterface $entity_manager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entity_manager = $entity_manager;
        $this->password_encoder = $passwordEncoder;


        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('password', InputArgument::REQUIRED, 'New password');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

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
        $encoded_password = $this->password_encoder->encodePassword($user, $password);
        $user->setPassword($encoded_password);

        $this->entity_manager->persist($user);
        $this->entity_manager->flush();

        $io->success(sprintf("Changes commited to %s.", $username));
    }
}
