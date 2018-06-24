<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\mvc\controller */
$ctrl->obj->url = $ctrl->data['root'].'page';
$ctrl->set_icon('far fa-clock')
  ->set_color('brown', '#FFF')
  ->combo(_('Automatized tasks'));