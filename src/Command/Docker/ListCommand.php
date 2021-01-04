<?php

namespace App\Command\Docker;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputOption;

class ListCommand extends Command
{
    protected static $defaultName = 'docker:list';

    private $defaultColumns = [
        'ID',
        'Names',
        'Status',
        'Ports'
    ];

    protected function configure()
    {
        $this
            ->setDescription('List current running containers')
            ->addOption(
                'column',
                'c',
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'Filter columns to render',
                $this->defaultColumns
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $command = sprintf(
            'docker ps --format "table {{.ID}}%1$s{{.Names}}%1$s{{.Status}}%1$s{{.Ports}}%2$s" | %3$s',
            $columnSeperator = 'COLUMN',
            $rowSeperator = 'ROW',
            'tail -n +2',
        );

        $rows = explode($rowSeperator, str_replace(PHP_EOL, '', shell_exec($command)));

        $table = array_map(function ($row) use ($columnSeperator) {
            return explode($columnSeperator, $row);
        }, $rows);

        $io->table(
            ['ID', 'Names', 'Status', 'Ports'],
            $table
        );

        return Command::SUCCESS;
    }
}
