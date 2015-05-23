<?php namespace Quallsbenson\Repository;


interface RepositoryInitializerInterface{

  public function initialize($repository, array $services = array());

}
