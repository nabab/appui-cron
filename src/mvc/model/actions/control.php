<?php

use bbn\Str;
use bbn\File\Dir;

/** @var bbn\Mvc\Model $model */
if ($model->hasData('file') && Str::checkName($model->data['file'])) {
  $f = $model->inc->cron->getStatusPath($model->data['file']);
  if (!$model->hasData('value', true)) {
    if (is_file($f)) {
      unlink($f);
    }
  }
  else {
    if (Dir::createPath(dirname($f))) {
      file_put_contents($f, (string)date('Y-m-d H:i:s'));
    }
  }

  return [
    'success' => (bool)$model->data['value'] === is_file($f)
  ];
}
