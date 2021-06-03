<?php

namespace App\Command;

use App\Entity\Sport\Sport;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitDatabaseCommand extends Command
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;

        parent::__construct("init:database");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("\nCreating entities...");

        try{
            $football = new Sport();
            $football->setName("Football");

            $basketball = new Sport();
            $basketball->setName("Basketball");

            $this->entityManager->persist($football);
            $this->entityManager->persist($basketball);

            $this->entityManager->flush();

            $output->writeln("Successfully added two sports (basketball and football) to database");

            return Command::SUCCESS;
        }
        catch (\Exception $e){
            $output->writeln(["Error ocurred while adding sports to database.", "\n------------------------------------",
                $e->getMessage(), "-------------------------------------\n"]);
            return Command::FAILURE;
        }
    }
}