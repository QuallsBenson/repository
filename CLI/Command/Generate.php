<?php namespace Designplug\Repository\CLI\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Designplug\Repository\FileSystem\Generator;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Finder\Finder;

class Generate extends Command{

  public function configure(){

    $this->setName('Repository:generate')
         ->setDescription('Generate a Repository')
         ->addArgument(
            'name',
            InputArgument::OPTIONAL,
            'What\'s the Name of the Repository'
         );

  }

  protected function confirmGeneration($name){

     return new ConfirmationQuestion('Confirm Generation of '.$name .'Repository ?', true);

  }

  protected function getConfigurationFileContents(){

    //search directories for configuration file

    $finder = new Finder;
    $finder->files()
           ->in(getcwd().'/config/repository')
           ->name('config.json');

    foreach($finder as $file){
      $json = $file->getContents();
    }

    return (array) json_decode($json);

  }

  protected function getConfigurationOptions($repositoryName, InputInterface $input, OutputInterface $output){


    //get the contents of the config file as an array of options
    $options = $this->getConfigurationFileContents();

    //add the name to the array
    $options['name'] = $repositoryName;

    return (array) $options;

  }

  protected function generateRepository(array $options = array()){

    $gen  = new Generator;
    $gen->generateRepository($options);

  }

  protected function execute(InputInterface $input, OutputInterface $output){

    $name    = $input->getArgument('name');
    $qhelper = $this->getHelper('question');

    //give error if name is not set
    if(!$name) throw new \Exception('missing argument name in Repository:generate {name}');

    $options = $this->getConfigurationOptions($name, $input, $output);

    //ask user to confirm generation, exit if not
    $confirm = $this->confirmGeneration($name);
    if(!$qhelper->ask($input, $output, $confirm)) return;

    //if user confirms generation attempt to generate files using options
    try{

      $this->generateRepository($options);

    } catch(\Exception $e) {

      $output->writeln("Repository Generation failed with the following message:\n\n");
      $output->writeln($e->getMessage());
      return;

    }

  }


}
