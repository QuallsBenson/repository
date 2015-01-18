<?php namespace Designplug\Repository;

class RepositoryInitializer implements RepositoryInitializerInterface{

  public function initialize($repository, array $services = array()){

    if(! ($repository instanceof RepositoryInterface) )
      throw new \Exception(' Repository Must Implement the RepositoryInterface ');

    if(!empty($services))
      $repository->addService($services);

  }

}
