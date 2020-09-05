<?php
return [
  'quicklist' => $model->db->rselect_all('bbn_cron', [
    'active',
    'text' => 'file',
    'value' => 'id'
  ], [
    ['prev', 'isnotnull'],
    ['prev', '>', date('Y-m-d H:i:s', time() - 365*24*3600)]
  ], ['file' => 'ASC'])
];