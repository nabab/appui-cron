<?php
return [
  'quicklist' => $model->db->rselectAll('bbn_cron', [
    'active',
    'text' => 'file',
    'value' => 'id'
  ], [
    ['prev', 'isnotnull'],
    ['prev', '>', Date('Y-m-d H:i:s', Time() - 365*24*3600)]
  ], ['file' => 'ASC'])
];