<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\mvc\model*/
if ( isset($model->data['data_path']) ){
  clearstatcache();
  $has_active = is_file($model->inc->cron->get_status_path('active'));
  $has_cron = is_file($model->inc->cron->get_status_path('cron'));
  $has_poll = is_file($model->inc->cron->get_status_path('poll'));
  $crontime = false;
  $cronid = false;
  $polltime = false;
  $pollid = false;
  if (
    $has_cron
    && ($cronfile = $model->inc->cron->get_pid_path(['type' => 'cron']))
    && is_file($cronfile)
  ) {
    [$cronid, $crontime] = explode('|', file_get_contents($cronfile));
    if (!file_exists('/proc/'.$cronid)) {
      unlink($cronfile);
      $crontime = false;
      $cronid = false;
    }
  }
  if (
    $has_poll
    && ($pollfile = $model->inc->cron->get_pid_path(['type' => 'poll']))
    && is_file($pollfile)
  ){
    [$pollid, $polltime] = explode('|', file_get_contents($pollfile));
    if (!file_exists('/proc/'.$pollid)) {
      unlink($pollfile);
      $polltime = false;
      $pollid = false;
    }
  }
  $failed = $model->inc->cron->get_manager()->get_failed();
  $fs = new \bbn\file\system();
  $fs->cd(dirname($model->inc->cron->get_pid_path(['type' => 'cron'])));
  $current = [];
  $files  = $fs->get_files('./', null, true);
  foreach ($files as $f){
    if (
      ($tmp = $model->inc->cron->get_manager()->get_cron(substr($f, 1))) &&
      (\bbn\x::find($failed, ['id' => $tmp['id']]) === null)
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