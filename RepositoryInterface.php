<?php namespace Designplug\Repository;

use Designplug\Utility\Object\ObjectResolver;
use Designplug\Repository\Database\DatabaseManagerInterface;

interface RepositoryInterface{

  public function setModelResolver(ObjectResolver $namespace);
  public function getModel($name);
  public function setDatabaseManager(DatabaseManagerInterface $manager);
  public function getDatabaseManager();

}
