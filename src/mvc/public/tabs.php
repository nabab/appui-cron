<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\mvc\controller */
$ctrl->obj->url = $ctrl->data['root'].'tabs';
$ctrl->set_icon('fa fa-clock-o')
  ->set_color('brown', '#FFF')
  ->combo(_('Automatized tasks'));