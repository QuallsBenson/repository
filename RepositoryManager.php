<?php namespace Designplug\Repository;

use Designplug\Utility\Object\ObjectResolver;
use Designplug\Repository\Database\DatabaseManagerInterface;

class RepositoryManager{

  protected $modelNamespace,
  $repositories = array(),
  $repositoryResolver,
  $repositoryInitializerResolver,
  $modelResolver,
  $initializationServices = array(),
  $databaseManager;

  public function __construct($repositoryNamespace, $modelNamespace, $repositoryInitializerNamespace = null){

    $this->repositoryResolver            = new ObjectResolver;
    $this->repositoryInitializerResolver = new ObjectResolver;
    $this->modelResolver                 = new ObjectResolver;

    //set namespace to load models from
    $this->modelResolver->addNamespace($modelNamespace);

    //resolve repositories from the given namespace
    $this->repositoryResolver->addNamespace($repositoryNamespace);

    //resolve repository from the given namespace
    //but default to the current namespace if none found
    $this->repositoryInitializerResolver->addNamespace($repositoryInitializerNamespace)
    ->addNamespace(__NAMESPACE__);
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

  public function setDatabaseManager(DatabaseManagerInterface $manager){

    $this->databaseManager = $manager;

  }


  protected function getRepository($name){

    $repoWrapper  = $this->repositoryResolver->resolve($name.'Repository');

    if(!$repoWrapper)
    throw new \Exception('Repository ' .$name .' was not found');

    $repoInstance = $repoWrapper->getInstance();

    if( ($repoInstance instanceof RepositoryInterface) === false )
    throw new \Exception('Repository must implement the Repository Interface');

    return $repoInstance;
  }


  protected function getRepositoryInitializer($name){

    $resolver = $this->repositoryInitializerResolver;
    $name     = $name .'RepositoryInitializer';
    $default  = 'repositoryInitializer';

    $repoInitializerWrapper = $resolver->resolve(array($name, $default));

    if(!$repoInitializerWrapper){
      throw new \Exception('Repository Initializer Not Found');
    }

    $repoInitializerInstance = $repoInitializerWrapper->getInstance();

    if(($repoInitializerInstance instanceof RepositoryInitializerInterface) === false)
    throw new \Exception('Repository Initializer must implement RepositoryInitializerInterface');

    return $repoInitializerInstance;

  }


  protected function initializeRepository($name, RepositoryInterface $repository){

    $initializer = $this->getRepositoryInitializer($name);

    $repository->setModelResolver( $this->modelResolver );
    $repository->setDatabaseManager( $this->databaseManager );

    $initializer->initialize( $repository, $this->initializationServices );

    return $repository;

  }



}
