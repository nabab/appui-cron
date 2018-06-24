<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 14/06/2018
 * Time: 17:01
 *
 * @var $model \bbn\mvc\model
 */

if (
  !empty($model->data['id']) &&
  !empty($model->data['filename']) &&
  !empty($model->inc->cron) &&
  ($path = $model->inc->cron->get_path()) &&
  is_file($path.'error/tasks/'.$model->data['id'].'/'.$model->data['filename']) &&
  \bbn\file\dir::delete($path.'error/tasks/'.$model->data['id'].'/'.$model->data['filename'])
){
  return ['success' => true];
}
return ['success' => false];