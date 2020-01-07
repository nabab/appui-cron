<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\mvc\model*/
if ($model->has_var('id', true) && $model->inc->cron->check()) {
  $model->add_data(['type' => 'cron']);
  
  return [
    'path' => $model->inc->cron->get_status_path('cron'),
    'path_log' => $model->inc->cron->get_log_path($model->data),
    'path_pid' => $model->inc->cron->get_pid_path($model->data),
    'tree' => $model->inc->cron->get_log_tree($model->data),
    'test' => $model->data,
    'success' => true
  ];
}
return [
  'success' => false
];