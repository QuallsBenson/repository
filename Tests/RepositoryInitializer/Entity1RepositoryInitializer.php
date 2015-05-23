<?php namespace Quallsbenson\Repository\Tests\RepositoryInitializer;

use Quallsbenson\Repository\RepositoryInitializer;
use Quallsbenson\Repository\Tests\Repository\Entity1Repository;

class Entity1RepositoryInitializer extends RepositoryInitializer{

  public function initialize($repository, array $services = array()){

    if( ($repository instanceof Entity1Repository) === false)
      throw new \Exception('Repository must be Entity1 Repository');

    parent::initialize($repository, $services);

  }

}
