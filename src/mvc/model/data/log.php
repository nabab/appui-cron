<?php
/*
 * Describe what it does!
 *
 **/
$r = ['success' => false];
/** @var $this \bbn\mvc\model*/
if ( isset($model->data['id']) ){
  $cfg = [
    'type' => strlen($model->data['id']) === 32 ? 'cron' : $model->data['id']
  ];
  if ( strlen($model->data['id']) === 32 ){
    $cfg['id'] = $model->data['id'];
  }
  clearstatcache();
  $files = \bbn\file\dir::get_files(dirname($model->inc->cron->get_log_path($cfg)));
  $f = $files[count($files)-1];
  $r['log'] = file_get_contents($f);
  $r['success'] = true;
}
return $r;