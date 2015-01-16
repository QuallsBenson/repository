<?php namespace Designplug\Repository\Tests\Repository;

use Designplug\Repository\Repository;

class Entity2Repository extends Repository{

  function create(){
    return 'created 2';
  }

  function find(){
    return 'found 2';
  }

}
