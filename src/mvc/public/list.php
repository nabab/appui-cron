<?php
/* @var $ctrl \bbn\mvc\controller */
if ( !empty($ctrl->arguments) ){
  $ctrl->post['id_cron'] = $ctrl->arguments[0];
}
if ( empty($ctrl->post) ){
  echo $ctrl->combo(_("Tâches automatisées (CRON)"), [
    'is_dev' => $ctrl->inc->user->is_dev(),
    'root' => $ctrl->data['root'],
    'can_run' => $ctrl->inc->perm->has($ctrl->data['root'].'run'),
    'lng' => [
      'edit' => _("Edit"),
      'deactivate' => _("Deactivate"),
      'reactivate' => _("Reactivate"),
      'edit' => _("Edit"),
      'no_output' => _("No output"),
      'executed_in' => _("Executed in"),
      'an_error_occured' => _("An error occurred"),
      'seconds' => _("sec.")
    ]
  ]);
}
else{
  $ctrl->obj = $ctrl->get_object_model($ctrl->post);
}