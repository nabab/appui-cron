<?php
if ( isset($model->data['data']) ){
  $model->data = $model->data['data'];
}
$data = [];
if (
  $model->hasVars(['id', 'start'], true) &&
  $model->inc->cron->check() &&
  ($y = date('Y/', Strtotime($model->data['start']))) &&
  ($file = date('Y-m', Strtotime($model->data['start'])) . '.json')
){
  if ($model->data['id'] === 'poll') {
    $model->addData(['type' => 'poll']);
    unset($model->data['id']);
  }
  else {
    $model->addData(['type' => 'cron']);
    if ($model->data['id'] === 'cron') {
      unset($model->data['id']);
    }
  }
  if (
    ($path = $model->inc->cron->getLogPath($model->data, false, true)) &&
    is_file($path . $y . $file) &&
    ($f = json_decode(file_get_contents($path . $y . $file), true)) &&
    !empty($f['dates'])
  ){
    foreach ( $f['dates'] as $d ){
      $tmp = date('Y-m-', Strtotime($model->data['start'])) . $d;
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