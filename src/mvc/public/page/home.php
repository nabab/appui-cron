<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\mvc\controller */
$ctrl->add_data(['data_path' => $ctrl->plugin_data_path()])
  ->set_icon('fa fa-home')
  ->set_color('brown', '#FFF')
  ->add_data($ctrl->get_model($ctrl->data['root'].'data/tasks', ['ctrl' => $ctrl]))
  ->add_data($ctrl->get_model($ctrl->data['root'].'data/files'))
  ->combo(_('Home'), true);