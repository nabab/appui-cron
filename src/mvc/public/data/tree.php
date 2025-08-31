<?php
/* @var bbn\Mvc\Controller $ctrl */

$ctrl->data = $ctrl->post['data'] ?? $ctrl->post;
$ctrl->obj->data = $ctrl->getModel();
