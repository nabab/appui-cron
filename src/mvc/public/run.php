<?php
/** @var $ctrl \bbn\mvc\controller */
if ( $ctrl->is_cli() ){
  \bbn\x::log("We execute from mvc/public/run", 'cron1');

  \bbn\x::dump("We are in the controller ".$ctrl->get_controller());

  // Looking for the cron class
  if ( $ctrl->db && class_exists("\\bbn\\appui\\cron") ){
    $cron = new \bbn\appui\cron($ctrl);
    $cron->run_all();
  }
}
else {
  $ctrl->obj = $ctrl->get_object_model($ctrl->post);  
}