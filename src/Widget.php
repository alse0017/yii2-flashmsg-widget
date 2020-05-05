<?php
/**
 * Created by PhpStorm.
 * User: Alexeenko Sergey Aleksandrovich
 * Email: sergei_alekseenk@list.ru
 * Company: https://machineheads.ru
 * Date: 05.08.2019
 * Time: 12:18
 */

namespace alse0017\flashmsg;

use alse0017\dismsg\Asset;
use yii\base\Widget as BaseWidget;
use yii\helpers\Html;

class Widget extends BaseWidget
{
	public $successMsgKey = 'success';
	public $errorMsgKey = 'error';
	public $infoMsgKey = 'info';

	public $disMsgOptionPosition = 'center'; // "top left"|"top center"|"top right"|"bottom left"|"bottom right"|"bottom center"|"center"
	public $disMsgOptionTime = '7000';

	public function run()
	{
		$view = $this->getView();
		Asset::register($view);
		$session = \Yii::$app->getSession();

		$scripts = [];
		foreach([
			        $this->successMsgKey => 'true',
			        $this->errorMsgKey   => 'false',
			        $this->infoMsgKey    => 'info',
		        ] as $flashKey => $disMsgType)
		{
			if($session->hasFlash($flashKey))
			{
				$errors = implode('<br>', $this->normalizeMsgs($session->getFlash($flashKey)));

				$scripts[] = <<< JS
$.disMsg('{$errors}', {
	type: '{$disMsgType}',
	position: '{$this->disMsgOptionPosition}',
	time: '{$this->disMsgOptionTime}'
});
JS;
			}
		}

		if($scripts)
		{
			$count = count($scripts);
			if($count > 1)
			{
				$script = <<< JS
$.disMsg.setOptions({max_count: {$count}});
JS;

				array_unshift($scripts, $script);
			}
			$view->registerJs(implode("\n", $scripts));
		}


		parent::run();
	}

	/**
	 * @param $msgs array
	 * @return array
	 */
	protected function normalizeMsgs($msgs)
	{
		if(!is_array($msgs)) $msgs = [$msgs];
		return array_map(function ($msg) {
			$msg = str_replace(["\n", "\\"], ["", "\\\\"], Html::encode((string)$msg));
			$msg = str_replace(['&lt;', '&gt;', '&quot;'], ['<', '>', '"'], $msg);
			return $msg;
		}, $msgs);
	}
}