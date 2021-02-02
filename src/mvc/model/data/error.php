<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 14/06/2018
 * Time: 16:03
 *
 * @var $model \bbn\Mvc\Model
 */

if (
  !empty($model->data['data']['id']) &&
  !empty($model->inc->cron) &&
  ($path = $model->inc->cron->getPath())
){
  if (
    is_dir($path.'error/tasks/'. $model->data['data']['id']) &&
    ($files = \bbn\File\Dir::getFiles($path.'error/tasks/'. $model->data['data']['id']))
  ){
    if ( isset($model->data['limit'], $model->data['start']) ){
      $files = array_slice($files, $model->data['start'], $model->data['limit']-1);
    }
    $files = array_map(function($f){
      return [
        'moment' => \bbn\Str::fileExt($f, true)[0],
        'content' => file_get_contents($f),
        'filename' => basename($f)
      ];
    }, $files);
    return ['data' => $files];
  }
}
return ['data' => []];