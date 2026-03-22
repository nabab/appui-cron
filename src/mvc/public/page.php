<?php
use bbn\Str;

/** @var bbn\Mvc\Controller $ctrl */
if (Str::pos($ctrl->getConstant('baseURL') ?: '', $ctrl->data['root'] . 'page/') !== 0 ){
  $ctrl->obj->url = $ctrl->data['root'].'page';
  $ctrl->setIcon('nf nf-fa-tasks')
       ->setColor('brown', '#FFF')
       ->combo(_('Automatized tasks'), true);
}