<?php

use bbn\Str;

$res = ['success' => false];

if ($model->hasData('id', true)) {
  if ($model->data['id'] === 'poll') {
    
  }
  elseif ($model->data['id'] === 'cron') {
    
  }
  elseif (Str::isUid($model->data['id']) && ($res['task'] = $model->inc->cron->getManager()->getCron($model->data['id']))) {
    if (is_array($res['task']['cfg'])) {
      $res['task'] = array_merge($res['task'], $res['task']['cfg']);
      unset($res['task']['cfg']);
    }

    if (!empty($res['task']['frequency'])) {
      $res['task']['next'] = $model->inc->cron->getManager()->getNextDate(
        $res['task']['frequency'],
        strtotime($res['task']['next'] ?: $res['task']['prev'])
      );
    }
  }
  if (!empty($res['task'])) {
    $res['success'] = true;
  }
}

return $res;
