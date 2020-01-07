<?php
/*
 * @var $model \bbn\mvc\model
 *
 **/
$r = ['success' => false];
/** @var $this \bbn\mvc\model*/
if (\bbn\x::has_props($model->data, ['file', 'id'], true) && ($path = $model->inc->cron->get_log_path(['type' => 'cron', 'id' => $model->data['id']], false, true))) {
  $f = $path.$model->data['file'];
  if (is_file($f)) {
    $r['log'] = file_get_contents($f);
    $r['filename'] = basename($f);
    $r['success'] = true;
  }
}
else if (isset($model->data['id']) && ($f = $model->inc->cron->get_last_log($model->data))) {
  $r['log'] = file_get_contents($f);
  $r['filename'] = basename($f);
  $r['success'] = true;
}
return $r;