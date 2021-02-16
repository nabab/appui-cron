<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\Mvc\Controller */
$ctrl->addData(['data_path' => $ctrl->pluginDataPath()])
  ->setIcon('nf nf-fa-home')
  ->setColor('brown', '#FFF')
  ->addData($ctrl->getModel($ctrl->data['root'].'data/tasks'))
  ->addData($ctrl->getModel($ctrl->data['root'].'data/files'))
  ->addData($ctrl->getModel($ctrl->data['root'].'data/quicklist'))
  ->combo(_('Home'));