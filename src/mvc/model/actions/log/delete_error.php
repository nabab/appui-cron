<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 14/06/2018
 * Time: 17:01
 *
 * @var $model \bbn\Mvc\Model
 */

if (
  !empty($model->data['id']) &&
  !empty($model->data['filename']) &&
  !empty($model->inc->cron) &&
  ($path = $model->inc->cron->getPath()) &&
  is_file($path.'error/tasks/'.$model->data['id'].'/'.$model->data['filename']) &&
  \bbn\File\Dir::delete($path.'error/tasks/'.$model->data['id'].'/'.$model->data['filename'])
){
  return ['success' => true];
}
return ['success' => false];