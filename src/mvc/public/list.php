<?php
/* @var $ctrl \bbn\mvc\controller */
if ( empty($ctrl->post) ){
  echo $ctrl->combo(_("Tâches automatisées (CRON)"), [
    'is_dev' => $ctrl->inc->user->is_admin(),
    'root' => $ctrl->data['root']
  ]);
}
else{
  $ctrl->obj = $ctrl->get_object_model($ctrl->post);
}