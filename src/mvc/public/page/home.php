<?php

/** @var bbn\Mvc\Controller $ctrl */
$ctrl->addData(['data_path' => $ctrl->pluginDataPath()])
  ->setIcon('nf nf-fa-home')
  ->setColor('brown', '#FFF')
  ->addData($ctrl->getModel($ctrl->data['root'].'data/tasks'))
  ->addData($ctrl->getModel($ctrl->data['root'].'data/files'))
  ->addData($ctrl->getModel($ctrl->data['root'].'data/quicklist'))
  ->combo(_('Home'), $ctrl->data);