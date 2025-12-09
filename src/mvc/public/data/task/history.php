<?php
/** @var bbn\Mvc\Controller $ctrl */
if (count($ctrl->arguments)) {
	if ( !empty($ctrl->post['data']) && !empty($ctrl->post['data']['day']) ){
		$ctrl->addData(['day' => $ctrl->post['data']['day']]);
		unset($ctrl->post['data']);
	}
	$ctrl->addData(['id' => $ctrl->arguments[0]])->action();
}