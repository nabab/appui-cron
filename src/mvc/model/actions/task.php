<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\mvc\controller */
if ( isset($model->data['file']) && !isset($model->data['action']) ){
  $model->data['action'] = empty($model->data['id']) ? 'insert' : 'update';
}
if ( !isset($model->data['action']) ){
  $model->data['action'] = false;
}
$time = time();
$date = date('Y-m-d H:i:s', $time);
switch ( $model->data['action'] ){

  case 'delete':
  if ( isset($model->data['id']) ){
    if ( $model->db->update("bbn_cron", [
      'active' => 0,
      ], [
      'id' => $model->data['id']
    ]) ){
      $model->data['active'] = 0;
      return $model->data;
    }
  }
  break;

  case 'restore':
  if ( isset($model->data['id']) ){
    if ( $model->db->update("bbn_cron", [
      'active' => 1,
      ], [
      'id' => $model->data['id']
    ]) ){
      $model->data['active'] = 1;
      return $model->data;
    }
  }
  break;

  case 'insert':
  if ( isset($model->data['file'], $model->data['project'], $model->data['description'], $model->data['next'], $model->data['frequency'], $model->data['timeout']) ){
    $cfg = json_encode([
      'frequency' => $model->data['frequency'],
      'timeout' => $model->data['timeout']
    ]);
    if ( $model->db->insert('bbn_cron', [
      'file' => $model->data['file'],
      'description' => $model->data['description'],
      'project' => $model->data['project'],
      'next' => $model->data['next'],
      'priority' => $model->data['priority'],
      'cfg' => $cfg,
      'active' => 1
    ]) ){
      $model->data['id'] = $model->db->last_id();
      $model->data['active'] = 1;
      return $model->data;
    }
  }
  break;

  case 'update':
  if ( isset($model->data['id'], $model->data['file'], $model->data['project'], $model->data['description'], $model->data['next'], $model->data['frequency'], $model->data['timeout']) ){
    $cfg = json_encode([
      'frequency' => $model->data['frequency'],
      'timeout' => $model->data['timeout']
    ]);
    if ( $model->db->update('bbn_cron', [
      'file' => $model->data['file'],
      'description' => $model->data['description'],
      'project' => $model->data['project'],
      'next' => $model->data['next'],
      'priority' => $model->data['priority'],
      'cfg' => $cfg
    ], [
      'id' => $model->data['id']
    ]) ){
      $model->data['id'] = $model->db->last_id();
      $model->data['active'] = 1;
      return $model->data;
    }
  }
  break;
}