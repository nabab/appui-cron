<?php
/** @var $model \bbn\mvc\model */
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

  default:
    $limit = empty($model->data['limit']) || !\is_int($model->data['limit']) ? 50 : $model->data['limit'];
    $start = !isset($model->data['start']) || !\is_int($model->data['start']) ? 0 : $model->data['start'];
    if ( isset($model->data['id_cron']) ){
      $grid = new \bbn\appui\grid($model->db, $model->data, [
        'table' => 'bbn_cron_journal',
        'filters' => [[
          'field' => 'id_cron',
          'operator' => 'eq',
          'value' => $model->data['id_cron']
        ]]
      ]);
    }
    else{
      $grid = new \bbn\appui\grid($model->db, $model->data, [
        'table' => 'bbn_cron'
      ]);
    }
    if ( $grid->check() ){
      return $grid->get_datatable();
    }
}