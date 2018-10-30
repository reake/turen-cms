<?php
/**
 * @link http://www.turen2.com/
 * @copyright Copyright (c) 土人开源CMS
 * @author developer qq:980522557
 */
namespace app\modules\wap;

use Yii;

/**
 * wap module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    //public $defaultRoute = 'wap/site/home';
    
    /**
     * @inheritdoc
     */
    //public $controllerNamespace = 'app\modules\wap\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        //当前为wap模板
        Yii::$app->errorHandler->errorAction = 'wap/site/error';
        // custom initialization code goes here
    }
}