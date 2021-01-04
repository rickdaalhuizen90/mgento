<?php

namespace App\Command\Docker;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExecCommand extends Command
{
    protected static $defaultName = 'docker:exec';

    protected function configure()
    {
        $this
            ->setDescription('Execute a command in a docker container')
            ->addArgument('cmd', InputOption::VALUE_REQUIRED, 'Command to be executed');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cmd = $input->getArgument('cmd');

        $result = shell_exec(sprintf('docker exec -it php-fpm /bin/bash %s', $cmd));

        $output->writeln($result);
        return Command::SUCCESS;
    }
}
