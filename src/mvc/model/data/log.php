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
  if (($path = $model->inc->cron->get_log_path($cfg, false, true))
    && is_file($path.$model->data['file'])
  ) {
	  $f = $path.$model->data['file'];
    $r['success'] = true;
  }
}
else if ( \bbn\x::has_props($model->data, ['filename', 'id', 'action'], true) ){
  if ( $f = $model->inc->cron->get_log_prev_next($model->data) ){
    $r['success'] = true;
  }
}
else if (isset($model->data['id']) && ($f = $model->inc->cron->get_last_log($model->data))) {
  $r['success'] = true;
}
if ( !empty($r['success']) && !empty($f) ){
  $r['log'] = file_get_contents($f);
}
return $r;