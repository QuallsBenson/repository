<?php namespace Designplug\Repository\Tests\Repository;

use Designplug\Repository\Repository;

class Entity1Repository extends Repository{

  function create(){
    return 'created';
  }

  function find(){
    return 'found';
  }

}
