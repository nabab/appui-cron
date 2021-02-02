<?php
/*
 * Describe what it does!
 *
 **/
/** @var $this \bbn\Mvc\Model*/
if ( isset($model->data['file']) && \bbn\Str::checkName($model->data['file']) ){
  $f = $model->inc->cron->getStatusPath($model->data['file']);
  if ( empty($model->data['value']) ){
    if (is_file($f)) {
      unlink($f);
    }
  }
  else if (file_put_contents($f, (string)date('Y-m-d H:i:s'))) {
    if ( $model->data['file'] === 'poll' ){
      $model->inc->cron->launchPoll();
    }
    else if ( $model->data['file'] === 'cron' ){
      $model->inc->cron->launchTaskSystem();
    }
  }
  return [
    'success' => (bool)$model->data['value'] === is_file($f)
  ];
}