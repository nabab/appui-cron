<?php
/* @var $ctrl \bbn\Mvc\Controller */

$ctrl->data = $ctrl->post['data'] ?? $ctrl->post;
$ctrl->obj->data = $ctrl->getModel();
