<?php


function getInstance($object, $arguments = array()){

  switch(count($arguments)){

    case 0:

      return new $object;

    break;
    case 1:

      return new $object($arguments[0]);

    break;
    case 2:

      return new $object($arguments[0], $arguments[1]);

    break;
    case 3:

      return new $object($arguments[0], $arguments[1], $arguments[2]);

    break;
    case 4:

      return new $object($arguments[0],
                         $arguments[1],
                         $arguments[2],
                         $arguments[3]);

    break;
    case 5:

      return new $object($arguments[0],
                         $arguments[1],
                         $arguments[2],
                         $arguments[3],
                         $arguments[4]);
    break;
    case 6:

      return new $object($arguments[0],
                         $arguments[1],
                         $arguments[2],
                         $arguments[3],
                         $arguments[4],
                         $arguments[5]);

    break;
    case 7:

      return new $object($arguments[0],
                         $arguments[1],
                         $arguments[2],
                         $arguments[3],
                         $arguments[4],
                         $arguments[5],
                         $arguments[6]);

    break;
    default:

      throw new \Exception('Call to instantiate ' .get_class($oject) .' failed,
                            cannot use more than 7 arguments');


    break;

  }

}
