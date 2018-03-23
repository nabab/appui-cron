<?php
/*
 * Describe what it does!
 *
 **/
/** @var $this \bbn\mvc\model*/
if ( isset($model->data['file']) && \bbn\str::check_name($model->data['file']) ){
  $f = $model->inc->cron->get_status_path($model->data['file']);
  if ( empty($model->data['value']) ){
    unlink($f);
  }
  else{
    file_put_contents($f, (string)date('Y-m-d H:i:s'));
    if ( $model->data['file'] === 'poll' ){
      $model->inc->cron->launch_poll();
    }
    else if ( $model->data['file'] === 'cron' ){
      $model->inc->cron->launch_task_system();
    }
  }
  return [
    'success' => (bool)$model->data['value'] === is_file($f)
  ];
}