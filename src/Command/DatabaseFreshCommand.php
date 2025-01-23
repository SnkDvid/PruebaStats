<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class DatabaseFreshCommand extends Command
{
    protected static $defaultName = 'app:database:fresh';

    protected function configure(): void
    {
        $this->setDescription('Limpia y recrea la base de datos');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $commands = [
            'php bin/console doctrine:database:drop --force',
            'php bin/console doctrine:database:create',
            'php bin/console doctrine:schema:update --force',
            'php bin/console doctrine:fixtures:load --append',
        ];

        foreach ($commands as $command) {
            $process = new Process(explode(' ', $command));
            $process->run();

            if (!$process->isSuccessful()) {
                $io->error($process->getErrorOutput());
                return Command::FAILURE;
            }
        }

        $io->success('La base de datos ha sido recreado correctamente');
        
        return Command::SUCCESS;
    }
}