<?php namespace Quallsbenson\Repository;

use Quallsbenson\Utility\Object\ObjectResolver;
use Quallsbenson\Repository\Database\DatabaseManagerInterface;

interface RepositoryInterface{

  public function setModelResolver(ObjectResolver $namespace);
  public function getModel($name);
  public function setDatabaseManager(DatabaseManagerInterface $manager);
  public function getDatabaseManager();
  public function addService(array $services);
  public function getService($serviceName);

}
