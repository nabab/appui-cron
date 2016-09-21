<?php
/* @var $ctrl \bbn\mvc\controller */
if ( isset($ctrl->post['appui_baseURL']) ){
  echo $ctrl->set_title('Tâches automatisées (CRON)')->add_js(['is_dev' => $ctrl->inc->user->is_admin()])->get_view();
}
else{
  $ctrl->data = $ctrl->post;
  $ctrl->obj = \bbn\x::to_object($ctrl->get_model());
}