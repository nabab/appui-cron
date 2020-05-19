<?php
/* @var $ctrl \bbn\mvc\controller */

$ctrl->data = $ctrl->post['data'] ?? $ctrl->post;
$ctrl->obj->data = $ctrl->get_model();
