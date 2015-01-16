<?php namespace Designplug\Repository;

class RepositoryInitializer implements RepositoryInitializerInterface{

  public function initialize($repository, array $services){

    if(! ($repository instanceof RepositoryInterface) )
      throw new \Exception(' Repository Must Implement the RepositoryInterface ');

    //does nothing except ensure that repository implements the Repository
    //Initializer interface, override this in your Initializers to enforce types
    //and other intialization

  }

}
