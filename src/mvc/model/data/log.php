<?php
/*
 * @var $model \bbn\mvc\model
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
  if (
    ($dir = dirname($model->inc->cron->get_log_path($cfg))) &&
    ($files = \bbn\file\dir::get_files($dir))
  ){
    if (
      !empty($model->data['filename']) &&
      !empty($model->data['action']) &&
      (($idx = array_search($dir.'/'.$model->data['filename'], $files)) !== false)
    ){
      $idx2 = $model->data['action'] === 'next' ? $idx+1 : $idx-1;
      if ( isset($files[$idx2]) ){
        $f = $files[$idx2];
      }
      else {
        $f = $files[$idx];
      }
    }
    else {
      $f = $files[count($files)-1];
    }
    $r['log'] = file_get_contents($f);
    $r['filename'] = basename($f);
    $r['success'] = true;
  }
}
return $r;