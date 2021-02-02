<?php
/* @var $ctrl \bbn\Mvc\Controller */
if ( !empty($ctrl->arguments) ){
  $ctrl->post['id_cron'] = $ctrl->arguments[0];
}
if ( empty($ctrl->post) ){
  $ctrl->setIcon('nf nf-oct-tasklist')
       ->combo(_("Tasks' list"), [
    'is_dev' => $ctrl->inc->user->isDev(),
    'root' => APPUI_CRON_ROOT,
    'can_run' => $ctrl->inc->perm->has(APPUI_CRON_ROOT.'run'),
    'can_delete' => $ctrl->inc->perm->has(APPUI_CRON_ROOT.'actions/task/delete'),
    'can_delete_error' => $ctrl->inc->perm->has(APPUI_CRON_ROOT.'actions/log/delete_error'),
    'can_delete_all_error' => $ctrl->inc->perm->has(APPUI_CRON_ROOT.'actions/log/delete_all_error')
  ]);
}
else{
  $ctrl->obj = $ctrl->getObjectModel($ctrl->post);
}