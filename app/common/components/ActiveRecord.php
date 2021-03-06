<?php
/**
 * @link http://www.turen2.com/
 * @copyright Copyright (c) 土人开源CMS
 * @author developer qq:980522557
 */
namespace common\components;

use Yii;
use yii\base\UnknownPropertyException;
use yii\helpers\Json;

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
        
        //custom code
    }

    /**
     * 由对象的picarr属性json decode返回解释图片数组
     * @return array|mixed
     * @throws UnknownPropertyException
     */
    public function picList()
    {
        if(!isset($this->picarr)) {
            //没有属性报错
            throw new UnknownPropertyException('模型对象没有这样的属性：picarr');
        }
        return empty($this->picarr)?[]:Json::decode($this->picarr);
    }
}