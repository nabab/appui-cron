<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 14/06/2018
 * Time: 11:38
 */

if (!empty($model->data['id'])
  && \bbn\str::is_uid($model->data['id'])
  && $model->inc->perm->has(APPUI_CRON_ROOT.'actions/task/reset')
  && $model->inc->cron->get_manager()->unset_pid($model->data['id'])
){
  return ['success' => true];
}
return ['success' => false];