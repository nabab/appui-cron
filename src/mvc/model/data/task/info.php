<?php
if (
  $model->has_var('id', true) &&
  \bbn\str::is_uid($model->data['id']) &&
  ($task = $model->inc->cron->get_manager()->get_cron($model->data['id']))
){
  if ( is_array($task['cfg']) ){
    $task = array_merge($task, $task['cfg']);
    unset($task['cfg']);
  }
  $task['next'] = $model->inc->cron->get_manager()->get_next_date($task['frequency'], strtotime($task['next'] ?: $task['prev']));
  return [
    'success' => true,
    'task' => $task
  ];
}
return ['success' => false];