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
    'type' => strlen($model->data['id']) === 32 ? 'cron' : $model->data['id']
  ];
  if ( strlen($model->data['id']) === 32 ){
    $cfg['id'] = $model->data['id'];
  }
  $dir = dirname($model->inc->cron->get_log_path($cfg));
  if ( is_dir($dir) && \bbn\file\dir::delete($dir, false) ){
    $ret['success'] = true;
  }
}
return $ret;