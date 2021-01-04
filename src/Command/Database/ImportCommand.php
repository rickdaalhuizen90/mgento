<?php

namespace App\Command\Database;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command
{
    private const CMD_WARDEN_IMPORT = 'cat %s | warden db import';

    private const CMD_MAGERUN_IMPORT = 'docker exec -it php-fpm /bin/bash php n98-magerun2.phar db:import --drop-tables %s';

    protected static $defaultName = 'db:import';

    protected function configure()
    {
        $this
            ->setDescription('Import project database')
            ->addArgument('file', InputArgument::REQUIRED, 'File to import')
            ->addOption('warden', null, InputOption::VALUE_OPTIONAL, 'Import db using Warden', true)
            ->addOption('magerun', null, InputOption::VALUE_NONE, 'Import db using Magerun');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');
        $cmd = sprintf(self::CMD_WARDEN_IMPORT, $file);

        if ($input->getOption('magerun')) {
            $cmd = sprintf(self::CMD_MAGERUN_IMPORT, $file);
        }

        $output->writeln(shell_exec($cmd));
        return Command::SUCCESS;
    }
}
