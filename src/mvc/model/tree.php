<?php
/* @var $model \bbn\mvc\model */
if ( !defined('BBN_APP_PATH') ){
  die('You must define BBN_APP_PATH!');
}
$model->data['dir'] = BBN_APP_PATH.'mvc/cli/';
$old_path = getcwd();
$max_history = 50;
chdir($model->data['dir']);
$ofiles = \bbn\file\dir::get_files($model->data['dir']);
$dirs = array_map(function($a){
  $fs = \bbn\file\dir::get_files($a, 1);
  return [
    'path' => str_replace("./", "", $a),
    'name' => basename($a),
    'is_parent' => count($fs) > 0,
    'icon' => "folder",
    'type' => "dir"
  ];
}, \bbn\file\dir::get_dirs($model->data['dir']));
$files = array_map(function($a){
  $fs = \bbn\file\dir::get_files($a);
  return [
    'path' => $a,
    'name' => basename($a, ".php"),
    'is_parent' => false,
    'icon' => "html",
    'type' => "file"
  ];
}, $ofiles);
$model = array_merge($dirs, $files);
chdir($old_path);
return $model;
