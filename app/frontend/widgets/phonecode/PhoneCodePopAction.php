<?php
/**
 * @link http://www.turen2.com/
 * @copyright Copyright (c) 土人开源CMS
 * @author developer qq:980522557
 */
namespace app\widgets\phonecode;

use common\helpers\Functions;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;

class PhoneCodePopAction extends Action
{
    const VERIFY_CODE_PARAM = 'VerifyCode';//数字验证码
    const PHONE_CODE_PARAM = 'PhoneCode';
    const PHONE_CODE_VALID_TIME = 60;//验证码有效时间
    const PHONE_MATCH_PATTERN = '/^[1][35678][0-9]{9}$/';

    public $phone = '';//手机号码

    public $maxNum = 6;//数字验证码最大多少位

    public function init()
    {
        parent::init();

        if(empty($this->maxNum) || empty($this->phone)) {
            throw new InvalidConfigException(self::class.'参数配置有误');
        }
    }

    public function run()
    {
        $session = Yii::$app->getSession();
//        $valid = Yii::createObject([
//            'class' => RegularExpressionValidator::class,
//            'pattern' => self::PHONE_MATCH_PATTERN,
//        ]);
        if(!preg_match(self::PHONE_MATCH_PATTERN, $this->phone)) {
            return $this->controller->asJson([
                'state' => false,
                'code' => 200,
                'msg' => '手机号码格式有误',
            ]);
        }
        //发短信
        $code = Functions::PhoneCode(6);
        $phoneCode = [
            'phone' => $this->phone,
            'code' => $code,
            't' => time(),
        ];
        if($rep = $this->sendPhoneCode($this->phone, $code) === true) {
            $session->set(self::PHONE_CODE_PARAM, $phoneCode);
            $this->controller->asJson([
                'state' => true,
                'code' => 200,
                'msg' => '验证码已经发送',//返回验证码
            ]);
        } else {
            $this->controller->asJson([
                'state' => true,
                'code' => 200,
                'msg' => $rep,//返回错误
            ]);
        }
    }

    protected function sendPhoneCode($phone, $code)
    {
        //发送
        $signName = '豹品淘';
        $templateCode = 'SMS_91980004';
        $phoneNumbers = $phone;
        $templateParam = ['code' => $code];

        //Yii::$app->sms->sendSms('13725514524', '小铃铛科技', 'SMS_91980004', ['code' => '1234']);
        $response = Yii::$app->sms->sendSms($phoneNumbers, $signName, $templateCode, $templateParam);
        if($response->Code == 'OK') {
            return true;
        } else {
            return $response;
        }
    }
}