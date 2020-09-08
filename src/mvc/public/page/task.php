<?php
if (
  (strpos($ctrl->baseURL, APPUI_CRON_ROOT . 'page/task/') !== 0) &&
  ($id = $ctrl->arguments[0]) &&
  \bbn\str::is_uid($id) &&
  ($task = $ctrl->inc->cron->get_manager()->get_cron($id))
){
  $ctrl->add_data($task);
  $ctrl->obj->url = APPUI_CRON_ROOT . 'page/task/' . $id;
  $ctrl->set_icon('nf nf-seti-project')
       ->combo($task['file'], $task);
}