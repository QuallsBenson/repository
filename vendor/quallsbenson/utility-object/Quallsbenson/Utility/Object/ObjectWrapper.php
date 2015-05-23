<?php namespace Quallsbenson\Utility\Object;


class ObjectWrapper{

  protected $staticObject,
            $instance;

  public function __construct($object){

    $this->staticObject = $object;

  }

  public static function wrap($object){

    $wrapper = __CLASS__;

    if(!is_array($object))
      return new $wrapper($object);

    $objectArray = array();

    foreach($object as $tag => $staticObject)
      $objectArray[$tag] = new $wrapper($staticObject);

    return $objectArray;

  }

  public function getName(){

    return $this->staticObject;

  }

  public function getInstance(){

    $arguments = func_get_args();

    if(is_callable($this->staticObject)){

      return call_user_func_array($this->staticObject, $arguments);

    }

    $class = $this->staticObject;

    return \getInstance($class, $arguments);

  }

  public function getSingleton(){

    if(!isset($this->instance))
      $this->instance = $this->getInstance();

    return $this->instance;

  }

  public function __destroy(){

    unset($this->staticObject);
    unset($this->instance);

  }


}
