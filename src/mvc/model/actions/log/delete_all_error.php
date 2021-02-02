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
  !empty($model->inc->cron) &&
  ($path = $model->inc->cron->getPath()) &&
  is_dir($path.'error/tasks/'.$model->data['id']) &&
  \bbn\File\Dir::delete($path.'error/tasks/'.$model->data['id'])
){
  return ['success' => true];
}
return ['success' => false];