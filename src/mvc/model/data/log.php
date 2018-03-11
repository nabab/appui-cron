<?php
/*
 * Describe what it does!
 *
 **/
$r = ['success' => false];
/** @var $this \bbn\mvc\model*/
if ( isset($model->data['id']) ){
  if ( $model->data['id'] === 'poll' ){
    $files = \bbn\file\dir::get_files($model->data['data_path'].'poller');
    $f = $files[count($files)-1];
    $r['log'] = file_get_contents($f);
    $r['success'] = true;
  }
}
return $r;