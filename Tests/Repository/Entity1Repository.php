<?php namespace Quallsbenson\Repository\Tests\Repository;

use Quallsbenson\Repository\Repository;

class Entity1Repository extends Repository{

  function create(){
    return 'created';
  }

  function find(){
    return 'found';
  }

}
