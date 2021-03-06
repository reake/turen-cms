<?php
/**
 * @link http://www.turen2.com/
 * @copyright Copyright (c) 土人开源CMS
 * @author developer qq:980522557
 */
namespace common\models\user;

use Yii;
use yii\base\Model;

/**
 * Forget form
 */
class ForgetForm extends Model
{
    public $email;
    public $verifyCode;//验证码

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => User::class,
                'filter' => ['status' => User::STATUS_ON],
                'message' => '您输入的邮箱地址有误，请重试。',
            ],
            ['verifyCode', 'captcha',
                'skipOnEmpty' => false,
                'caseSensitive' => false,
                'captchaAction' => 'account/user/captcha',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = [
            'email' => '用户邮箱',
            'verifyCode' => '验证码',
        ];

        return $labels;
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ON,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save(false)) {
                return false;
            }
        }

        /* 测试
        return Yii::$app->mailer->compose()
            ->setFrom([Yii::$app->params['config.supportEmail'] => Yii::$app->params['config_site_name']])
            ->setTo($this->email)
            ->setSubject('重置密码 - '.Yii::$app->params['config_site_name'])
            ->setTextBody('Plain text content')
            ->setHtmlBody('<b>HTML content</b>')
            ->send();
        */

        //发送邮件
        try {
            Yii::$app->mailer
                ->compose(
                    ['html' => 'resetForm-html', 'text' => 'resetForm-text'],
                    ['user' => $user]
                )
                ->setFrom([Yii::$app->params['config.supportEmail'] => Yii::$app->params['config_site_name']])
                ->setTo($this->email)
                ->setSubject('重置密码 - ' . Yii::$app->params['config_site_name'])
                ->send();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}