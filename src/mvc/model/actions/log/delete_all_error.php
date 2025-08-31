<?php
use bbn\File\Dir;
/** @var bbn\Mvc\Model $model */


if (
  !empty($model->data['id']) &&
  !empty($model->inc->cron) &&
  ($path = $model->inc->cron->getPath()) &&
  is_dir($path.'error/tasks/'.$model->data['id']) &&
  Dir::delete($path.'error/tasks/'.$model->data['id'])
){
  return ['success' => true];
}
return ['success' => false];