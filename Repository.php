<?php namespace Designplug\Repository;

use Designplug\Utility\Object\ObjectResolver;

abstract class Repository implements RepositoryInterface{

  protected $models = array(),
            $modelResolver;

  public function setModelResolver(ObjectResolver $resolver){

    $this->modelResolver = $resolver;
    return $this;

  }


  public function getModel($name){

    if(isset($this->models[$name]))
      return $this->models[$name];

    $model = $this->modelResolver->resolve($name);

    if(!$model)
      throw new \Exception("Could Not Resolve Model: " .$name ." from given namespace(s)");

    return $this->models[$name] = $model->getInstance();

  }

}
