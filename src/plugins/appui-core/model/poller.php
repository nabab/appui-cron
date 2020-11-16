<?php
//return [];
if (!isset($model->inc->cron)) {
  $mvc = \bbn\mvc::get_instance();
  $ctrl = new \bbn\mvc\controller($mvc, []);
  $model->add_inc('cron', (new \bbn\cron($model->db, $ctrl)));
}
$plugin_data = $model->plugin_data_path('appui-cron');
$plugin_url = $model->plugin_url('appui-cron');
return [[
  'id' => 'appui-cron-0',
  'frequency' => 5,
  'function' => function(array $data) use($plugin_data, $plugin_url, $model){
    $res = [
      'success' => true,
      'data' => []
    ];
    if (isset($data['data']['filesHash'])
      && ($mod = $model->get_model($plugin_url . '/data/files', ['data_path' => $plugin_data]))
      && ($hash = md5(json_encode($mod)))
      && ($hash !== $data['data']['filesHash'])
    ) {
      $res['data']['files'] = $mod;
      $res['data']['serviceWorkers'] = ['filesHash' => $hash];
    }
    return $res;
  }
], [
  'id' => 'appui-cron-1',
  'frequency' => 30,
  'function' => function(array $data) use($plugin_url, $model){
    $res = [
      'success' => true,
      'data' => []
    ];
    if (isset($data['data']['tasksHash'])
      && ($mod = $model->get_model($plugin_url . '/data/tasks'))
      && !empty($mod['success'])
      && ($hash = md5(json_encode($mod)))
      && ($hash !== $data['data']['tasksHash'])
    ) {
      $res['data']['tasks'] = $mod;
      $res['data']['serviceWorkers'] = ['tasksHash' => $hash];
    }
    return $res;
  }
]];