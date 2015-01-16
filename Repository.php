<?php namespace Designplug\Repository;

use Designplug\Utility\Object\ObjectResolver;
use Designplug\Repository\Database\DatabaseManager;


abstract class Repository implements RepositoryInterface{

  protected $models = array(),
            $modelResolver,
            $databaseManager,
            $services;

  public function setModelResolver(ObjectResolver $resolver){

    $this->modelResolver = $resolver;
    return $this;

  }

  public function getModel($name){

    if(isset($this->models[$name]))
      return $this->models[$name];

    $model = $this->modelResolver->resolve($name);

    if(!$model)
      throw new \Exception("Could Not Resolve Model: " .$name ." from given namespace(s)");

    //connect to database if not connected
    if(!$this->databaseManager->isConnected())
      $this->databaseManager->conntect();

    return $this->models[$name] = $model->getInstance();

  }


  public function setDatabaseManager(DatabaseManagerInterface $manager){

    $this->databaseManager = $manager;

  }

  public function getDatabaseManager(){

    return $this->databaseManager;

  }

}
