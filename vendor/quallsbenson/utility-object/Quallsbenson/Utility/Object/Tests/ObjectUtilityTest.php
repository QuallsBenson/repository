<?php

use Quallsbenson\Utility\Object\ObjectResolver;
use Quallsbenson\Utility\Object\ObjectWrapper;

require dirname(dirname(__FILE__)) .'/vendor/autoload.php';

class ObjectUtilityTest extends PHPUnit_Framework_TestCase{

      public function testObjectResolver(){

        $resolver = new ObjectResolver;
        $resolver->addNamespace('\Quallsbenson\Utility\Object\Tests');
        $class    = $resolver->resolve(array('ResolvedObjectNonExisting', 'ResolvedObject1'));

        $this->assertEquals($class->getName(), 'Quallsbenson\Utility\Object\Tests\ResolvedObject1');

        return $class;

      }

      /**
      *
      * @depends testObjectResolver
      */

      public function testObjectWrapper($objectWrapper){

        $this->assertTrue($objectWrapper->getInstance() instanceof Quallsbenson\Utility\Object\Tests\ResolvedObject1);

      }


}
