<?php
/*
 * Describe what it does!
 *
 * @var $ctrl \bbn\Mvc\Controller
 *
 */
if (count($ctrl->arguments)) {
	if ( !empty($ctrl->post['data']) && !empty($ctrl->post['data']['day']) ){
		$ctrl->addData(['day' => $ctrl->post['data']['day']]);
		unset($ctrl->post['data']);
	}
	$ctrl->addData(['id' => $ctrl->arguments[0]])->action();
}