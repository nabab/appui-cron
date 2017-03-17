<?php
/**
 * Created by PhpStorm.
 * User: BBN
 * Date: 24/01/2017
 * Time: 18:12
 */

namespace boo;


class test
{
  function __construct()
  {
    $this->name = "TEST";
  }

  public function say_name(){
    var_dump($this->name);
  }
}