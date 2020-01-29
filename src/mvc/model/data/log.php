<?php
/*
 * @var $model \bbn\mvc\model
 *
 **/
$r = ['success' => false];
/** @var $this \bbn\mvc\model*/
if (\bbn\x::has_props($model->data, ['file', 'id'], true)) {
  if ($model->data['id'] === 'cron') {
    $cfg = ['type' => 'cron'];
  }
  else if ($model->data['id'] === 'poll') {
    $cfg = ['type' => 'poll'];
  }
  else {
    $cfg = ['type' => 'cron', 'id' => $model->data['id']];
  }
  if (($path = $model->inc->cron->get_log_path($cfg, false, true)) && is_file($path.$model->data['file'])) {
	  $f = $path.$model->data['file'];
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