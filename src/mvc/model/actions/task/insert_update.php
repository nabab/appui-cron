<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 14/06/2018
 * Time: 10:51
 */
if (
  !empty($model->data['action']) &&
  !empty($model->data['file']) &&
  !empty($model->data['description']) &&
  !empty($model->data['next']) &&
  !empty($model->data['frequency']) &&
  !empty($model->data['timeout'])
){
  $d = [
    'file' => $model->data['file'],
    'description' => $model->data['description'],
    'next' => $model->data['next'],
    'priority' => $model->data['priority'],
    'cfg' => json_encode([
      'frequency' => $model->data['frequency'],
      'timeout' => $model->data['timeout']
    ])
  ];
  switch ( $model->data['action'] ){
    case 'insert':
      if (
        defined('BBN_PROJECT') &&
        !empty(BBN_PROJECT) &&
        ($d = array_merge($d, [
          'project' => BBN_PROJECT,
          'active' => 1
        ])) &&
        $model->db->insert('bbn_cron', $d)
      ){
        $d['id'] = $model->db->last_id();
        return [
          'success' => true,
          'data' => $d
        ];
      }
      break;

    case 'update':
      if (
        !empty($model->data['id']) &&
        ($d = array_merge($d, ['project' => $model->data['project']])) &&
        $model->db->update('bbn_cron', $d, [
          'id' => $model->data['id']
        ])
      ){
        $d['id'] = $model->data['id'];
        return [
          'success' => true,
          'data' => $d
        ];
      }
      break;
  }
}
return ['success' => false];