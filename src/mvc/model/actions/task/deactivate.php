<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\mvc\model*/
$res = ['success' => false];
if ( isset($model->data['id']) ){
  $res['success'] = $model->inc->cron->deactivate($model->data['id']);
}
return $res;