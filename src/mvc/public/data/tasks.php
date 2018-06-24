<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\mvc\controller */
if ( !isset($ctrl->data['ctrl']) ){
  $ctrl->data['ctrl'] = $ctrl;
}
$ctrl->action();