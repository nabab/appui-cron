<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\Mvc\Model*/
if ($model->hasVar('id', true) && $model->inc->cron->check()) {
  if ($model->data['id'] === 'poll') {
    $model->addData(['type' => 'poll']);
    unset($model->data['id']);
  }
  else {
    $model->addData(['type' => 'cron']);
    if ($model->data['id'] === 'cron') {
      unset($model->data['id']);
    }
  }

  $data = $model->inc->cron->getDayLogs($model->data);
  $task = [];
  if (!empty($model->data['id']) ) {
    $task = $model->inc->cron->getManager()->getCron($model->data['id']);
    if (is_array($task['cfg'])) {
      $task = array_merge($task, $task['cfg']);
      unset($task['cfg']);
    }

    if (!empty($task['frequency'])) {
      $task['next'] = $model->inc->cron->getManager()->getNextDate(
        $task['frequency'],
        strtotime($task['next'] ?: $task['prev'])
      );
    }
  }

  return [
    'data' => $data ?: [],
    'total' => is_array($data) ? count($data) : 0,
    'task' => $task,
    'test' => $model->data,
    'success' => true
  ];
}

return [
  'success' => false
];