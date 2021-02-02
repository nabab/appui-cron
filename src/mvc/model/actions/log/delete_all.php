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
    'type' => \bbn\Str::isUid($model->data['id']) ? 'cron' : $model->data['id']
  ];
  if ( \bbn\Str::isUid($model->data['id']) ){
    $cfg['id'] = $model->data['id'];
  }
  $dir = dirname($model->inc->cron->logPath($cfg));
  if ( is_dir($dir) && \bbn\File\Dir::delete($dir, false) ){
    $ret['success'] = true;
  }
}
return $ret;