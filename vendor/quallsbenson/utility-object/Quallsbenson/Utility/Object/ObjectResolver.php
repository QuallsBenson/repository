<?php namespace Quallsbenson\Utility\Object;

use Quallsbenson\Utility\Object\ObjectWrapper;

class ObjectResolver{

  protected $resolveNamespaces = array();

  public function addNamespace($resolveNamespace){

    if(!is_array($resolveNamespace))
      $resolveNamespace = (array) $resolveNamespace;

    foreach($resolveNamespace as $ns)
      $this->resolveNamespaces[] = (string) trim(trim($ns), '\\') .'\\';

    return $this;

  }

  public function resolve($name){

    if(!is_array($name)) $name = (array) $name;

    foreach($this->resolveNamespaces as $ns){


      foreach($name as $obj){

        $cls = $ns.ltrim($obj, '\\');

        if(class_exists($cls))
          return new ObjectWrapper($cls);

      }

    }

    return false;

  }





}
