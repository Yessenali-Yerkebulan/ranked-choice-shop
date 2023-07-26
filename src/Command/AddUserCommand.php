<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

class AddUserCommand extends Command
{
    protected static $defaultName = 'app:add-user';
    protected static $defaultDescription = 'Create user';

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addOption('email', 'em', InputArgument::REQUIRED, 'Email')
            ->addOption('password', 'p', InputArgument::REQUIRED, 'Password')
            ->addOption('isAdmin', '', InputArgument::OPTIONAL, 'If set the user is created as an administrator', 0);

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $stopwatch = new Stopwatch();
        $stopwatch->start('add-user-command');

        $email = $input->getOption('email');
        $password = $input->getOption('password');
        $isAdmin = $input->getOption('isAdmin');

        $io->title('Add User Command Wizard');
        $io->text([
            'Please, enter some information'
        ]);

        if(!$email){
            $email = $io->ask('Email');
        }

        if(!$password){
            $password = $io->askHidden('Password (your type will be hidden)');
        }

        if(!$isAdmin){
            $question = new Question('Is admin? (1 or 0)', 0);
            $isAdmin = $io->askQuestion($question);
        }

        $successMessage = sprintf('%s was successfully created: %s',
        $isAdmin ? 'Administrator user' : 'User', $email);

        $io->success($successMessage);
        $event = $stopwatch->stop('add-user-command');
        $stopwatchMessage = sprintf('New user\'s id: %s / Elapsed time: %.2f ms / Consumed memory: %.2f MB',
        123,
        $event->getDuration(),
        $event->getMemory() / 1000 / 1000);
        $io->comment($stopwatchMessage);
//        dd($email, $password, $isAdmin);
//
//        if ($arg1) {
//            $io->note(sprintf('You passed an argument: %s', $arg1));
//        }
//
//        if ($input->getOption('option1')) {
//            // ...
//        }
//
//        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
