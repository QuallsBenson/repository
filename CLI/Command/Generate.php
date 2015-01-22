<?php namepspace Designplug\Repository\CLI\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Designplug\Repository\FileSystem\Generator;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class Generate extends Command{

  public function configure(){

    $this->setName('Repository:generate')
         ->setDescription('Generate a Repository')
         ->addArgument(
            'name',
            InputArgument::OPTIONAL,
            'What\'s the Name of the Repository'
         )
         ->addOption(
            'auto',
            null,
            InputOption::VALUE_NONE,
            'If set, Repository will be generated without dialog'
       );

  }

  protected function execute(InputInterface $input, OutputInterface $output){

    $name = $input->getArgument('name');
    $auto = $input->getOption('auto');
    $qhlp = $this->getHelper('question');
    $gen  = new Generator;

    if($name && $auto){

      $conf = new ConfirmationQuestion('Confirm Generation of '.$name .'Repository ?', true);
      
      if(!$qhlp->ask($input, $output, $conf)) return;

      try{

        $gen->generateRepository(array('name' => $name));
        $output->writeln("Repository " .$name ." was successfully created");

      } catch(\Exception $e) {

        $output->writeln("Repository Generation failed with the following message:\n\n");
        $output->writeln($e->getMessage());

      }
    }


    if(!$name){

    }


  }


}
