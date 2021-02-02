<?php
/** @var $ctrl \bbn\Mvc\Controller */
if ( $ctrl->isCli() ){
  \bbn\X::log("We execute from mvc/public/run", 'cron1');

  \bbn\X::dump("We are in the controller ".$ctrl->getController());

  // Looking for the cron class
  if ( $ctrl->db && class_exists("\\bbn\\Appui\\cron") ){
    $cron = new \bbn\Appui\cron($ctrl);
    $cron->runAll();
  }
}
else {
  $ctrl->obj = $ctrl->getObjectModel($ctrl->post);  
}