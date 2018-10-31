<?php
/**
 * @link http://www.turen2.com/
 * @copyright Copyright (c) 土人开源CMS
 * @author developer qq:980522557
 */
namespace common\components;

use Yii;

class ActiveRecord extends \yii\db\ActiveRecord
{
    //字段null值
    const DEFAULT_NULL = '-1';
    
    //顶级id为0
    const TOP_ID = 0;
    
    //审核状态
    const STATUS_ON = 1;
    const STATUS_OFF = 0;
    
    //是否状态
    const IS_ON = 1;
    const IS_OFF = 0;
    
    //删除状态
    const IS_DEL = 1;
    const IS_NOT_DEL = 0;
    
    //移动方向
    const ORDER_UP_TYPE = 'up';
    const ORDER_DOWN_TYPE = 'down';
    
    private $_user;
    
    public function init()
    {
        parent::init();
        
        if(!Yii::$app->getUser()->getIsGuest()) {
            $this->_user = Yii::$app->getUser()->getIdentity();
        }
    }
    
    public function getUser()
    {
        return $this->_user;
    }
}