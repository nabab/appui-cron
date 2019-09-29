<?php
/** @var $ctrl \bbn\mvc\controller */
if ( $ctrl->is_cli() ){

  \bbn\x::dump("We are in the controller ".$ctrl->get_controller());

  // Looking for the cron class
  if ( $ctrl->db && class_exists ("\\bbn\\appui\\cron") ){
    $mail = new \apst\mail(1);
    $cron = new \bbn\appui\cron($ctrl, ['mail' => $mail]);
    $cron->run_all();
  }
}
$ctrl->obj = $ctrl->get_object_model($ctrl->post);