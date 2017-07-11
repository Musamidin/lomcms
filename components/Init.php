<?php
namespace app\components;

use Yii;
use yii\helpers\Url;

class Init  extends \yii\base\Component  {

    public function init() {
        if (\Yii::$app->getUser()->isGuest && \Yii::$app->getRequest()->url !== Url::to('/login') ) 
        { 	//\Yii::$app->getUser()->loginUrl[0]
            \Yii::$app->getResponse()->redirect('/login');
        }
        // else{
        // 	\Yii::$app->getResponse()->redirect('/about');
        // }

        parent::init();
    }
}
?>