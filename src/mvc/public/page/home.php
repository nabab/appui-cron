<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\mvc\controller */
$ctrl->add_data(['data_path' => $ctrl->plugin_data_path()])
  ->set_icon('nf nf-fa-home')
  ->set_color('brown', '#FFF')
  ->add_data($ctrl->get_model($ctrl->data['root'].'data/tasks'))
  ->add_data($ctrl->get_model($ctrl->data['root'].'data/files'))
  ->add_data($ctrl->get_model($ctrl->data['root'].'data/quicklist'))
  ->combo(_('Home'), true);