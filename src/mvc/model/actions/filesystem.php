<?php
/*
 * Describe what it does!
 *
 **/
/** @var $this \bbn\mvc\model*/
if ( isset($model->data['file']) && \bbn\str::check_name($model->data['file']) ){
  $f = $model->data['data_path'].'.'.$model->data['file'];
  if ( empty($model->data['value']) ){
    unlink($f);
  }
  else{
    if ( $model->data['file'] === 'active' ){
      file_put_contents($f, (string)date('Y-m-d H:i:s'));
    }
    else if ( $model->data['file'] === 'poll' ){
      $model->inc->cron->poll();
    }
    else if ( $model->data['file'] === 'cron' ){
      $model->inc->cron->launch();
    }
  }
  return [
    'success' => !!$model->data['value'] === is_file($f)
  ];
}