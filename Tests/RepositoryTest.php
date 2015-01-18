<?php

use Designplug\Utility\Object\ObjectResolver;
use Designplug\Utility\Object\ObjectWrapper;
use Designplug\Repository\RepositoryManager;u
se Designplug\Repository\Tests\Database\DatabaseManager;


require dirname(dirname(__FILE__)) .'/vendor/autoload.php';

class RepositoryTest extends PHPUnit_Framework_TestCase{

  public function testGetRepository(){

    $manager = new RepositoryManager('Designplug\Repository\Tests\Repository',
                                     'Designplug\Repository\Tests\Entity',
                                     'Designplug\Repository\Tests\RepositoryInitializer');

    $manager->setDatabaseManager(new DatabaseManager);

    $repo    = $manager->get('Entity1');


    $this->assertEquals($repo->create(), 'created');
    $this->assertEquals($repo->find(),   'found');

    return $manager;

  }

  /**
  *
  * @depends testGetRepository
  */

  public function testGetEntity2($manager){

    $repo = $manager->get('Entity2');

    $this->assertEquals($repo->create(), 'created 2');
    $this->assertEquals($repo->find(),   'found 2');

    return $repo;

  }

  /**
  *
  * @depends testGetEntity2
  */

  public function testGetModel($repo){

    $model  = $repo->getModel('Entity2');
    $model2 = $repo->getModel('Entity1');

    $this->assertEquals($model->insert(), 'inserted 2');
    $this->assertEquals($model2->insert(), 'inserted');

    $this->assertEquals($model->delete(), 'deleted 2');
    $this->assertEquals($model2->delete(), 'deleted');
  }


}
