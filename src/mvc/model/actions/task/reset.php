<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 14/06/2018
 * Time: 11:38
 */

if (!empty($model->data['id'])
  && \bbn\Str::isUid($model->data['id'])
  && $model->inc->perm->has(APPUI_CRON_ROOT.'actions/task/reset')
  && $model->inc->cron->getManager()->unsetPid($model->data['id'])
){
  return ['success' => true];
}
return ['success' => false];