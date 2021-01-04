<?php

namespace App\Command\Docker;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class PurgeCommand extends Command
{
    private const CMD_KILL_RUNNING_CONTAINERS   = 'docker kill $(docker ps -q)';

    private const CMD_REMOVE_LOCAL_CONTAINERS   = 'docker rm $(docker ps -a -q)';

    private const CMD_REMOVE_LOCAL_IMAGES       = 'docker rmi $(docker images -a -q) -f';

    private const CMD_SYSTEM_PRUNE              = 'docker system prune --all - -volumes --force';

    protected static $defaultName = 'docker:purge';

    protected function configure()
    {
        $this
            ->setDescription('Prune containers, images and volumes');
    }

    private function killRunningContainers(InputInterface $input, OutputInterface $output): bool
    {
        $helper = $this->getHelper('question');
        $answer = $helper->ask(
            $input,
            $output,
            new ChoiceQuestion('Kill running containers?', ['No', 'Yes'], 1)
        );

        if ($answer === 'No') {
            return false;
        }

        return (bool) $output->writeln(shell_exec(self::CMD_KILL_RUNNING_CONTAINERS));
    }

    private function removeLocalContainers(InputInterface $input, OutputInterface $output): bool
    {
        $helper = $this->getHelper('question');
        $answer = $helper->ask(
            $input,
            $output,
            new ChoiceQuestion('Remove local containers?', ['No', 'Yes'], 1)
        );

        if ($answer === 'No') {
            return false;
        }

        return (bool) $output->writeln(shell_exec(self::CMD_REMOVE_LOCAL_CONTAINERS));
    }

    private function removeLocalImages(InputInterface $input, OutputInterface $output): bool
    {
        $helper = $this->getHelper('question');
        $answer = $helper->ask(
            $input,
            $output,
            new ChoiceQuestion('Remove local images?', ['No', 'Yes'], 1)
        );

        if ($answer === 'No') {
            return false;
        }

        return (bool) $output->writeln(shell_exec(self::CMD_REMOVE_LOCAL_IMAGES));
    }

    private function systemPrune(InputInterface $input, OutputInterface $output): bool
    {
        $helper = $this->getHelper('question');
        $answer = $helper->ask(
            $input,
            $output,
            new ChoiceQuestion('Remove all unuased containers, images and networks?', ['No', 'Yes'], 1)
        );

        if ($answer === 'No') {
            return false;
        }

        if (!$helper->ask(
            $input,
            $output,
            new ConfirmationQuestion('Continue with this action? (type yes to conintue)', false)
        )) {
            return false;
        }

        return (bool) $output->writeln(shell_exec(self::CMD_SYSTEM_PRUNE));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->killRunningContainers($input, $output)) {
            return Command::FAILURE;
        }

        if ($this->removeLocalContainers($input, $output)) {
            return Command::FAILURE;
        }

        if ($this->removeLocalImages($input, $output)) {
            return Command::FAILURE;
        }

        if ($this->systemPrune($input, $output)) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
