<?php namespace Designplug\Repository;


interface RepositoryInitializerInterface{

  public function initialize($repository, array $services = array());

}
