<?php
/**
 * @link http://www.turen2.com/
 * @copyright Copyright (c) 土人开源CMS
 * @author developer qq:980522557
 */
namespace app\modules\com;

/**
 * ext module definition class
 */
class Module extends \app\components\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\com\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
