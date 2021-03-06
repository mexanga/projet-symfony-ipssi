<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BuildCommand extends Command
{
    protected static $defaultName = 'app:build';

    protected function configure()
    {
        $this
            ->setDescription(
              'Compile les fichiers sources dans le répertoire '
            . 'public.'
          );
#            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
#            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        ini_set('max_execution_time', 5120);
        ini_set('memory_limit', -1);

        $io = new SymfonyStyle($input, $output);
//        $arg1 = $input->getArgument('arg1');

/*        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        } */

/*        if ($input->getOption('option1')) {
            // ...
        } */

        $commands = [
          'yarn encore production' => 'Compilation des fichiers sources via yarn'
        ];

        $numberOfCommands = count($commands);

        try {
          $io->progressStart($numberOfCommands);

          foreach ($commands as $command => $description) {

            $io->text($description);

            $explodedCommand = explode(' ', $command, 2);
            $commandName = $explodedCommand[0];
            $commandArguments = $explodedCommand[1];

            $process = new Process( [$commandName, $commandArguments] );
            $process->run();

            // executes after the command finishes
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

  //          echo $process->getOutput();

            $io->progressAdvance();

          }
        }
        catch (Exception $exception) {
          // Lancer l'exeception
          // $io->progressFinish(); ?
        }
        $io->progressFinish();

        $io->success('Succès !');

        ini_set('max_execution_time', 60);

        return 0;
    }
}
