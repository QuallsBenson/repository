<?php namespace Designplug\Repository;

use Designplug\Utility\Object\ObjectResolver;
use Designplug\Utility\Object\ObjectWrapper;
use Designplug\Repository\Database\DatabaseManagerInterface;


abstract class Repository implements RepositoryInterface{

  protected $models = array(),
            $modelResolver,
            $databaseManager,
            $services = array();

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

    if(!$this->databaseManager->isConnected())
      $this->databaseManager->connect();

    return $this->databaseManager;

  }

  public function db(){

    return $this->getDatabaseManager();

  }

  public function addService(array $services){

    $this->services = array_merge($this->services, ObjectWrapper::wrap($services) );

  }

  public function getService($serviceName){

    if(!$this->hasService($serviceName))
      throw new \Exception('attempted to call non-existent Serice ' .$serviceName);

    return $this->services[$serviceName];

  }

  public function hasService($serviceName){

    return isset($this->services[$serviceName]);

  }

  public function __call($name, $param){

    if($this->hasService($name)){

      //if method is a service name, we assume that user was calling a service
      $service = $this->getService($name);

      //if parameters passed, return singleton instance
      if(count($param) > 0)
        return call_user_func_array(array($service, 'getSingleton'), $param);

      //else just return service and let user decide how to instantiate
      return $service;

    }
    throw new \Exception('Call to undefined method: '.__CLASS__.'::' .$name);

  }

}
