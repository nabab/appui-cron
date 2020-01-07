<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 12/06/2018
 * Time: 12:57
 */

$ret = ['success' => false];
if (
  !empty($model->data['id']) &&
  !empty($model->data['filename'])
){
  $cfg = [
    'type' => strlen($model->data['id']) === 32 ? 'cron' : $model->data['id']
  ];
  if ( strlen($model->data['id']) === 32 ){
    $cfg['id'] = $model->data['id'];
  }
  $file = dirname($model->inc->cron->log_path($cfg)).'/'.$model->data['filename'];
  if ( is_file($file) && \bbn\file\dir::delete($file) ){
    $ret['success'] = true;
  }
}
return $ret;