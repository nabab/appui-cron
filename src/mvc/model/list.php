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
    $grid = new \bbn\appui\grid($model->db, $model->data, isset($model->data['id_cron']) ? 'bbn_cron_journal' : 'bbn_cron');
    $limit = empty($model->data['limit']) || !\is_int($model->data['limit']) ? 50 : $model->data['limit'];
    $start = !isset($model->data['start']) || !\is_int($model->data['start']) ? 0 : $model->data['start'];
    if ( isset($model->data['id_cron']) ){
      $limit = empty($model->data['limit']) || !\is_int($model->data['limit']) ? 50 : $model->data['limit'];
      $start = !isset($model->data['start']) || !\is_int($model->data['start']) ? 0 : $model->data['start'];
      $data = $model->db->get_rows("
        SELECT *
        FROM bbn_cron_journal
        WHERE id_cron = ?
        ORDER BY start DESC
        LIMIT $start, $limit",
        $model->data['id_cron']);
      $total = $model->db->count('bbn_cron_journal', ['id_cron' => $model->data['id_cron']]);
    }
    else{
      $data = $model->db->get_rows("
        SELECT bbn_cron.*, AVG(bbn_cron_journal.duration) AS duration, COUNT(bbn_cron_journal.id) AS num
        FROM bbn_cron
          LEFT JOIN bbn_cron_journal
            ON bbn_cron_journal.id_cron = bbn_cron.id
        WHERE project = 1
        GROUP BY bbn_cron.id
        ORDER BY file
        LIMIT $start, $limit");
      $total = $model->db->count('bbn_cron', ['project' => 1]);
    }
    return [
      'data' => array_map(function($a)use($model){
        if ( !isset($model->data['id_cron']) ) {
          $tasks = $model->db->get_rows("
            SELECT *
            FROM bbn_cron_journal
            WHERE id_cron = ?
            ORDER BY start DESC
            LIMIT 50",
            $a['id']);
          if ( (\count($tasks) > 0) && ($tasks[0]['res'] === 'error') ){
            $a['state'] = 'error';
          }
          else if ( (\count($tasks) > 0) && \is_null($tasks[0]['finish']) ){
              $a['state'] = ( isset($tasks[1]) && ($tasks[1]['res'] === 'error') ) ? 'progress_error' : 'progress';
          }
          else {
            $a['state'] = 'hold';
          }
        }
        else {
          $a['state'] = $a['res'] === 'error' ? 'error' : (\is_null($a['finish']) ? 'progress' : 'complete');
        }
        if ( $cfg = json_decode($a['cfg'], 1) ){
          unset($a['cfg']);
          foreach ( $cfg as $k => $v ){
            $a[$k] = $v;
          }
        }
        return $a;
      }, $data),
      'total' => $total
    ];
}