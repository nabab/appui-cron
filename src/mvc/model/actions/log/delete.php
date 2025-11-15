<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 12/06/2018
 * Time: 12:57
 */
use bbn\Str;

$ret = ['success' => false];
if (
  !empty($model->data['id']) &&
  !empty($model->data['filename'])
){
  $cfg = [
    'type' => Str::len($model->data['id']) === 32 ? 'cron' : $model->data['id']
  ];
  if ( Str::len($model->data['id']) === 32 ){
    $cfg['id'] = $model->data['id'];
  }
  $file = dirname($model->inc->cron->logPath($cfg)).'/'.$model->data['filename'];
  if ( is_file($file) && \bbn\File\Dir::delete($file) ){
    $ret['success'] = true;
  }
}
return $ret;