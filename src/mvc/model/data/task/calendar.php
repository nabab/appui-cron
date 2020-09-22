<?php
if ( isset($model->data['data']) ){
  $model->data = $model->data['data'];
}
$data = [];
if (
  $model->has_vars(['id', 'start'], true) &&
  $model->inc->cron->check() &&
  ($y = date('Y/', strtotime($model->data['start']))) &&
  ($file = date('Y-m', strtotime($model->data['start'])) . '.json')
){
  if ($model->data['id'] === 'poll') {
    $model->add_data(['type' => 'poll']);
    unset($model->data['id']);
  }
  else {
    $model->add_data(['type' => 'cron']);
    if ($model->data['id'] === 'cron') {
      unset($model->data['id']);
    }
  }
  if (
    ($path = $model->inc->cron->get_log_path($model->data, false, true)) &&
    is_file($path . $y . $file) &&
    ($f = json_decode(file_get_contents($path . $y . $file), true)) &&
    !empty($f['dates'])
  ){
    foreach ( $f['dates'] as $d ){
      $tmp = date('Y-m-', strtotime($model->data['start'])) . $d;
      $data[] = [
        'start' => $tmp,
        'end' => $tmp
      ];
    }
  }
  return [
    'data' => $data,
    'total' => count($data),
    'success' => true
  ];
}
return [
  'success' => false,
  'data' => $data
];