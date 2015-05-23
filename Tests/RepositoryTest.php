<?php

use Quallsbenson\Utility\Object\ObjectResolver;
use Quallsbenson\Utility\Object\ObjectWrapper;
use Quallsbenson\Repository\RepositoryManager;
use Quallsbenson\Repository\Database\DatabaseManagerInterface;
use Quallsbenson\Repository\Tests\Database\DatabaseManager;


require dirname(dirname(__FILE__)) .'/vendor/autoload.php';

class RepositoryTest extends PHPUnit_Framework_TestCase{

  public function testInitializeManager(){

    $manager = new RepositoryManager('Quallsbenson\Repository\Tests\Repository',
                                     'Quallsbenson\Repository\Tests\Entity',
                                     'Quallsbenson\Repository\Tests\RepositoryInitializer');

    $manager->setDatabaseManager(new DatabaseManager);
    $manager->setInitializationServices(array(
                                        'service1' => 'Quallsbenson\Repository\Tests\Service\Service1',
                                        'service2' => 'Quallsbenson\Repository\Tests\Service\Service2'
                                        )
                                      );

    return $manager;

  }

  /**
  *
  * @depends testInitializeManager
  **/


  public function testGetRepository($manager){

    $repo    = $manager->get('Entity1');


    $this->assertEquals($repo->create(), 'created');
    $this->assertEquals($repo->find(),   'found');

    return $manager;

  }

  /**
  *
  * @depends testGetRepository
  */

  public function testGetRepoService($manager){

    $repo    = $manager->get('Entity1');

    $service = $repo->getService('service1');

    $this->assertEquals($service->getName(), 'Quallsbenson\Repository\Tests\Service\Service1');

    $serviceInstance = $service->getInstance();

    $this->assertEquals(get_class($serviceInstance), 'Quallsbenson\Repository\Tests\Service\Service1');


    $this->assertEquals($repo->service1()->getName(), 'Quallsbenson\Repository\Tests\Service\Service1');

    return $repo;

  }

  /**
  *
  * @depends testGetRepoService
  */

  public function testDatabaseManager($repo){

    $db = $repo->getDatabaseManager();

    $this->assertEquals($db->insert(), 'insert');
    $this->assertEquals($db->delete(), 'delete');

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
