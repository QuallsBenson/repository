<?php namespace Quallsbenson\Repository\CLI\Command;

use Quallsbenson\Repository\FileSystem\RepositoryGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Finder\Finder;

class GenerateCommand extends Command{

  public function configure(){

    $this->setName('repository:generate')
         ->setDescription('Generate a Repository')
         ->addArgument(
            'name',
            InputArgument::OPTIONAL,
            'What\'s the Name of the Repository'
         );

  }

  protected function confirmGeneration($name){

     return new Question('Confirm Generation of '.$name .'Repository [yes]?', 'yes');

  }

  protected function getConfigurationFileDir(){

    return getcwd().'/config/repository';

  }

  protected function getConfigurationFileName(){

    return 'config.json';

  }

  protected function getConfigurationFileContents(){

    //search directories for configuration file

    $finder = new Finder;
    $finder->files()
           ->in( $this->getConfigurationFileDir() )
           ->name( $this->getConfigurationFileName() );

    foreach($finder as $file){
      $json = $file->getContents();
    }

    return (array) json_decode($json);

  }

  protected function getConfigurationOptions(){

    //get the contents of the config file as an array of options
    $options = $this->getConfigurationFileContents();

    return is_array( $options ) ? $options : (array) $options;
  }

  protected function validateConfigurationOptions(array $options){

    return (isset($options['generationPath'])      &&
            isset($options['templatePath'])        &&
            isset($options['modelNamespace'])      &&
            isset($options['repositoryNamespace']) &&
            isset($options['name']));

  }

  protected function generateRepository(array $options = array()){

    $gen  = new RepositoryGenerator;
    $gen->generate($options);

  }

  protected function execute(InputInterface $input, OutputInterface $output){

    $name    = $input->getArgument('name');
    $qhelper = $this->getHelper('question');

    //give error if name is not set
    if(!$name) throw new \Exception('missing argument name in Repository:generate {name}');

    $options = array("name" => $name);
    $options = array_merge( $options, $this->getConfigurationOptions() );

    //give an error if configuration not set
    if(!$this->validateConfigurationOptions( $options ))
       throw new \Exception("Required Configuration Parameters Not Set");

    //ask user to confirm generation, exit if not
    $confirm = $this->confirmGeneration($name);

    if($qhelper->ask($input, $output, $confirm) !== 'yes'){

      $output->writeln("\n\nRepository Generation Cancelled\n\n");
      return;

    }
    //if user confirms generation attempt to generate files using options
    try{

      $this->generateRepository($options);
      $output->writeln("\n\nRepository {$name} was successfully Generated \n\n");

    } catch(\Exception $e) {

      $output->writeln("Repository Generation failed with the following message:\n\n");
      $output->writeln($e->getMessage());
      return;

    }

  }


}
