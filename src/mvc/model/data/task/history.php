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

  $data = $model->inc->cron->get_day_logs($model->data);
  $task = [];
  if (!empty($model->data['id'])) {
    $task = $model->inc->cron->get_manager()->get_cron($model->data['id']);
    if (is_array($task['cfg'])) {
      $task = array_merge($task, $task['cfg']);
      unset($task['cfg']);
    }

    if (!empty($task['frequency'])) {
      $task['next'] = $model->inc->cron->get_manager()->get_next_date(
        $task['frequency'],
        strtotime($task['next'] ?: $task['prev'])
      );
    }
  }

  return [
    'data' => $data,
    'total' => count($data),
    'task' => $task,
    'test' => $model->data,
    'success' => true
  ];
}

return [
  'success' => false
];