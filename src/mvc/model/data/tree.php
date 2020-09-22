<?php
/* @var $model \bbn\mvc\model */
if ( !\is_dir($model->app_path()) ){
  die('You must define BBN_APP_PATH!');
}
$model->data['dir'] = $model->app_path().'cli/'.(!empty($model->data['dirpath']) ? $model->data['dirpath'].'/' : '');
$old_path = getcwd();
$max_history = 50;
chdir($model->data['dir']);

$dirs = array_map(function($a) use($model){
  $fs = \bbn\file\dir::get_files($a, 1);
  return [
    'dirpath' => str_replace("./", "", str_replace($model->data['dir'], '', $a)),
    'text' => basename($a),
    'numChildren' => \count($fs),
    'icon' => "nf nf-fa-folder",
    'folder' => true
  ];
}, \bbn\file\dir::get_dirs($model->data['dir']));

$files = array_map(function($a) use($model){
  return [
    'text' => basename($a, ".php"),
    'icon' => "nf nf-fa-file_code",
    'folder' => false
  ];
}, \bbn\file\dir::get_files($model->data['dir']));

$ret = array_merge($dirs, $files);
chdir($old_path);
return $ret;
