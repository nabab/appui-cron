<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 14/06/2018
 * Time: 11:38
 */

if (
  !empty($model->data['id']) &&
  $model->inc->perm->has(APPUI_CRON_ROOT.'actions/task/delete') &&
  $model->db->delete('bbn_cron', ['id' => $model->data['id']])
){
  return ['success' => true];
}
return ['success' => false];