<?php namespace Designplug\Repository\Tests\RepositoryInitializer;

use Designplug\Repository\RepositoryInitializer;
use Designplug\Repository\Tests\Repository\Entity1Repository;

class Entity1RepositoryInitializer extends RepositoryInitializer{

  public function initialize($repository){

    if( ($repository instanceof Entity1Repository) === false)
      throw new \Exception('Repository must be Entity1 Repository');

  }

}
