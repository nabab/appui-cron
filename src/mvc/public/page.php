<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\Mvc\Controller */
if ( strpos(BBN_BASEURL, $ctrl->data['root'] . 'page/') !== 0 ){
  $ctrl->obj->url = $ctrl->data['root'].'page';
  $ctrl->setIcon('nf nf-fa-tasks')
       ->setColor('brown', '#FFF')
       ->combo(_('Automatized tasks'), true);
}