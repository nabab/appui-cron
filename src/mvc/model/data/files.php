<?php
use bbn\X;
use bbn\Str;
/** @var bbn\Mvc\Model $model */
if ( isset($model->data['data_path']) ){
  clearstatcache();
  $has_active = is_file($model->inc->cron->getStatusPath('active'));
  $has_cron = is_file($model->inc->cron->getStatusPath('cron'));
  $has_poll = is_file($model->inc->cron->getStatusPath('poll'));
  $crontime = false;
  $cronid = false;
  $polltime = false;
  $pollid = false;
  if (
    $has_cron
    && ($cronfile = $model->inc->cron->getPidPath(['type' => 'cron']))
    && is_file($cronfile)
  ) {
    [$cronid, $crontime] = explode('|', File_get_contents($cronfile));
    if (!file_exists('/proc/'.$cronid)) {
      unlink($cronfile);
      $crontime = false;
      $cronid = false;
    }
  }
  if (
    $has_poll
    && ($pollfile = $model->inc->cron->getPidPath(['type' => 'poll']))
    && is_file($pollfile)
  ){
    [$pollid, $polltime] = explode('|', File_get_contents($pollfile));
    if (!file_exists('/proc/'.$pollid)) {
      unlink($pollfile);
      $polltime = false;
      $pollid = false;
    }
  }
  $failed = $model->inc->cron->getManager()->getFailed();
  $fs = new \bbn\File\System();
  $fs->cd(dirname($model->inc->cron->getPidPath(['type' => 'cron'])));
  $current = [];
  $files  = $fs->getFiles('./', null, true);
  foreach ($files as $f){
    if (
      ($tmp = $model->inc->cron->getManager()->getCron(Str::sub($f, 1))) &&
      (X::search($failed, ['id' => $tmp['id']]) === null)
    ){
      $current[] = $tmp;
    }
  }
  return [
    'files' => $files,
    'current' => $current,
    'active' => $has_active,
    'failed' => $failed,
    'cron' => $has_cron,
    'poll' => $has_poll,
    'cronid' => $cronid,
    'crontime' => $crontime,
    'polltime' => $polltime,
    'pollid' => $pollid
  ];
}
return ['success' => false];