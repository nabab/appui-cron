<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\mvc\model*/
if ( isset($model->data['data_path']) ){
  $has_active = is_file($model->data['data_path'].'.active');
  $has_cron = is_file($model->data['data_path'].'.cron');
  $has_poll = is_file($model->data['data_path'].'.poll');
  $crontime = false;
  $cronid = false;
  $polltime = false;
  $pollid = false;
  if ( $has_cron ){
    $tmp = explode('|', file_get_contents($model->data['data_path'].'.cron'));
    $cronid = $tmp[0];
    $crontime = $tmp[1];
  }
  if ( $has_poll ){
    $tmp = explode('|', file_get_contents($model->data['data_path'].'.pollid'));
    $pollid = $tmp[0];
    $polltime = $tmp[1];
  }
  return [
    'active' => $has_active,
    'cron' => $has_cron,
    'poll' => $has_poll,
    'cronid' => $cronid,
    'crontime' => $crontime,
    'polltime' => $polltime,
    'pollid' => $pollid
  ];
}
return ['success' => false];