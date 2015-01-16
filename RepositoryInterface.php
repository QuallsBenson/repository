<?php namespace Designplug\Repository;

use Designplug\Utility\Object\ObjectResolver;

interface RepositoryInterface{

  public function setModelResolver(ObjectResolver $namespace);
  public function getModel($name);

}
