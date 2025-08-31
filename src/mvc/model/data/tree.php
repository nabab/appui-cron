<?php
/* @var bbn\Mvc\Model $model */
if ( !\is_dir($model->appPath()) ){
  die('You must define BBN_APP_PATH!');
}
$model->data['dir'] = $model->appPath().'cli/'.(!empty($model->data['dirpath']) ? $model->data['dirpath'].'/' : '');
$old_path = getcwd();
$max_history = 50;
chdir($model->data['dir']);

$dirs = array_map(function($a) use($model){
  $fs = \bbn\File\Dir::getFiles($a, 1);
  return [
    'dirpath' => str_replace("./", "", str_replace($model->data['dir'], '', $a)),
    'text' => basename($a),
    'numChildren' => \count($fs),
    'icon' => "nf nf-fa-folder",
    'folder' => true
  ];
}, \bbn\File\Dir::getDirs($model->data['dir']));

$files = array_map(function($a) use($model){
  return [
    'text' => basename($a, ".php"),
    'icon' => "nf nf-fa-file_code",
    'folder' => false
  ];
}, \bbn\File\Dir::getFiles($model->data['dir']));

$ret = array_merge($dirs, $files);
chdir($old_path);
return $ret;
