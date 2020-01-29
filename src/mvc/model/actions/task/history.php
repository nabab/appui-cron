<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\mvc\model*/
if ($model->has_var('id', true) && $model->inc->cron->check()) {
  if ($model->data['id'] === 'poll') {
    $model->add_data(['type' => 'poll']);
    unset($model->data['id']);
  }
  else {
    $model->add_data(['type' => 'cron']);
    if ($model->data['id'] === 'cron') {
      unset($model->data['id']);
    }
  }
  return [
    'data' => $model->inc->cron->get_log_tree($model->data),
    'test' => $model->data,
    'success' => true
  ];
}
return [
  'success' => false
];