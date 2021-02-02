<?php
/*
 * @var $model \bbn\Mvc\Model
 *
 **/
$r = ['success' => false];
/** @var $this \bbn\Mvc\Model*/
if (\bbn\X::hasProps($model->data, ['file', 'id'], true)) {
  if ($model->data['id'] === 'cron') {
    $cfg = ['type' => 'cron'];
  }
  else if ($model->data['id'] === 'poll') {
    $cfg = ['type' => 'poll'];
  }
  else {
    $cfg = ['type' => 'cron', 'id' => $model->data['id']];
  }
  if (($path = $model->inc->cron->getLogPath($cfg, false, true))
    && is_file($path.$model->data['file'])
  ) {
	  $f = $path.$model->data['file'];
    $r['success'] = true;
  }
}
else if ( \bbn\X::hasProps($model->data, ['filename', 'id', 'action'], true) ){
  if ( $f = $model->inc->cron->getLogPrevNext($model->data) ){
    $r['success'] = true;
  }
}
else if (isset($model->data['id']) && ($f = $model->inc->cron->getLastLog($model->data))) {
  $r['success'] = true;
}
if ( !empty($r['success']) && !empty($f) ){
  $r['log'] = file_get_contents($f);
}
return $r;