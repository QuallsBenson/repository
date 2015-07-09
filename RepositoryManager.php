<?php namespace Quallsbenson\Repository;

use Quallsbenson\Utility\Object\ObjectResolver;
use Quallsbenson\Repository\Database\DatabaseManagerInterface;

class RepositoryManager{

  protected $modelNamespace,
            $repositories = array(),
            $repositoryResolver,
            $repositoryInitializerResolver,
            $modelResolver,
            $initializationServices = array(),
            $databaseManager;

  public function __construct($repositoryNamespace, $modelNamespace, $repositoryInitializerNamespace = null){

    //set namespace to load models from
    $this->getModelResolver()->addNamespace($modelNamespace);

    //resolve repositories from the given namespace
    $this->getRepositoryResolver()->addNamespace($repositoryNamespace);

    //resolve repository from the given namespace
    //but default to the current namespace if none found
    $this->getRepositoryInitializerResolver()->addNamespace($repositoryInitializerNamespace)
                                             ->addNamespace(__NAMESPACE__);
  }

  public function getRepositoryResolver(){

    return $this->repositoryResolver ?: $this->repositoryResolver = new ObjectResolver;

  }

  public function getRepositoryInitializerResolver(){

    return $this->repositoryInitializerResolver ?: $this->repositoryInitializerResolver = new ObjectResolver;

  }

  public function getModelResolver(){

    return $this->modelResolver ?: $this->modelResolver = new ObjectResolver;

  }

  public function get($repositoryName){

    if(isset($this->repositories[$repositoryName]))
      return $this->repositories[$repositoryName];

    $repo = $this->getRepository($repositoryName);

    $this->repositories[$repositoryName] = $this->initializeRepository($repositoryName, $repo);

    return $this->repositories[$repositoryName];

  }

  public function setInitializationServices(array $param){

    $this->initializationServices = $param;

  }

  public function getInitializationServices( $repoName = false )
  {

    return $this->initializationServices;

  }

  public function setDatabaseManager(DatabaseManagerInterface $manager){

    $this->databaseManager = $manager;

  }


  protected function getRepository($name){

    $repoWrapper  = $this->getRepositoryResolver()->resolve($name.'Repository');

    if(!$repoWrapper)
      throw new \Exception('Repository ' .$name .' was not found');

    $repoInstance = $repoWrapper->getInstance();

    if( ($repoInstance instanceof RepositoryInterface) === false )
      throw new \Exception('Repository must implement the Repository Interface');

    return $repoInstance;
  }


  protected function getRepositoryInitializer($name){

    $resolver = $this->getRepositoryInitializerResolver();
    $name     = $name .'RepositoryInitializer';
    $default  = 'repositoryInitializer';

    $repoInitializerWrapper = $resolver->resolve(array($name, $default));

    if(!$repoInitializerWrapper){
      throw new \Exception('Repository Initializer Not Found');
    }

    $repoInitializerInstance = $repoInitializerWrapper->getInstance();

    if(($repoInitializerInstance instanceof RepositoryInitializerInterface) === false){
      throw new \Exception('Repository Initializer must implement RepositoryInitializerInterface');
    }

    return $repoInitializerInstance;

  }


  protected function initializeRepository($name, RepositoryInterface $repository){

    $initializer = $this->getRepositoryInitializer($name);

    $repository->setModelResolver( $this->getModelResolver() );
    $repository->setDatabaseManager( $this->databaseManager );

    $initializer->initialize( $repository, $this->getInitializationServices() );

    return $repository;

  }



}
