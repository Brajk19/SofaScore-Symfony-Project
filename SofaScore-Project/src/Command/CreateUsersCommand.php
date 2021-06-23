<?php

namespace App\Command;

use App\Entity\Category\Category;
use App\Entity\Match\AbstractMatch;
use App\Entity\Match\FootballMatch;
use App\Entity\Sport\Sport;
use App\Entity\VrhovniSudija;
use App\Service\Listener\MatchScoreChangeListener;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SymfonyBundles\RedisBundle\Redis\ClientInterface;

class CreateUsersCommand extends Command
{

    private EntityManagerInterface $entityManager;
    private ClientInterface $redis;

    public function __construct(EntityManagerInterface $em, ClientInterface $redis)
    {
        $this->entityManager = $em;
        $this->redis = $redis;

        parent::__construct("create:users");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("\nCreating users...");

        $user = new VrhovniSudija();
        $user->setUsername("GospodinRobot");
        $user->setRoles(["ROLE_HAKERMAN"]);
        $user->setPassword("imIn");
        $user->setApiToken(uniqid());

        $output->writeln(["\nUsername: " . $user->getUsername(), "Role: " . $user->getRoles()[0], "Authentication Token: " . $user->getApiToken()]);


        $user2 = new VrhovniSudija();
        $user2->setUsername("StevoLopata");
        $user2->setRoles(["ROLE_POLJOPRIVREDNIK"]);
        $user2->setPassword("kravaMilka123");
        $user2->setApiToken(uniqid());

        $output->writeln(["\nUsername: " . $user2->getUsername(), "Role: " . $user2->getRoles()[0], "Authentication Token: " . $user2->getApiToken()]);

        $this->entityManager->persist($user);
        $this->entityManager->persist($user2);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}