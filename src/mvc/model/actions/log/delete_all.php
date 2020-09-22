<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 12/06/2018
 * Time: 12:57
 */

$ret = ['success' => false];
if ( !empty($model->data['id']) ){
  $cfg = [
    'type' => \bbn\str::is_uid($model->data['id']) ? 'cron' : $model->data['id']
  ];
  if ( \bbn\str::is_uid($model->data['id']) ){
    $cfg['id'] = $model->data['id'];
  }
  $dir = dirname($model->inc->cron->log_path($cfg));
  if ( is_dir($dir) && \bbn\file\dir::delete($dir, false) ){
    $ret['success'] = true;
  }
}
return $ret;