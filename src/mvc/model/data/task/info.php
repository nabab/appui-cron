<?php
if ($model->hasData('id', true)
    && \bbn\Str::isUid($model->data['id'])
    && ($task = $model->inc->cron->getManager()->getCron($model->data['id']))
) {
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

  return [
    'success' => true,
    'task' => $task
  ];
}
return ['success' => false];