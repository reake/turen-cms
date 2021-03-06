<?php
/**
 * @link http://www.turen2.com/
 * @copyright Copyright (c) 土人开源CMS
 * @author developer qq:980522557
 */
namespace app\models\tool;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\base\Model;

/**
 * This is the model class for table "{{%tool_notify_user}}".
 *
 * @property string $nu_id
 * @property int $nu_userid 关系的系统用户
 * @property string $nu_username 用户名（昵称）
 * @property string $nu_realname 真实名称
 * @property string $nu_phone 手机号码
 * @property string $nu_email 邮箱
 * @property string $nu_comment 备注
 * @property int $nu_fr_id 来源id
 * @property string $nu_order_total 交易总额
 * @property string $nu_reg_time 获取用户的时间
 * @property int $nu_star 用户星级
 * @property string $nu_province 省
 * @property string $nu_city 市
 * @property string $nu_area 区县
 * @property int $nu_is_sms_white 可否发短信
 * @property int $nu_is_notify_white 可否发站内信
 * @property int $nu_is_email_white 可否发邮件
 * @property string $nu_last_login_time 最近一次登录时间
 * @property string $nu_last_order_time 最近一次下单时间
 * @property string $nu_last_send_time 最近一次发消息时间
 * @property string $created_at 添加时间
 * @property string $updated_at 修改时间
 */
class NotifyUser extends \app\models\base\Tool
{
	public $keyword;
	
	public function behaviors()
	{
	    return [
	        'timemap' => [
	            'class' => TimestampBehavior::class,
	            'createdAtAttribute' => 'created_at',
	            'updatedAtAttribute' => 'updated_at'
	        ],
	        'regTime' => [
	            'class' => TimestampBehavior::class,
	            'createdAtAttribute' => 'nu_reg_time',
	            'updatedAtAttribute' => false,
	        ],
	        'lastLoginTime' => [
	            'class' => TimestampBehavior::class,
	            'createdAtAttribute' => 'nu_last_login_time',
	            'updatedAtAttribute' => false,
	        ],
	        'lastOrderTime' => [
	            'class' => TimestampBehavior::class,
	            'createdAtAttribute' => 'nu_last_order_time',
	            'updatedAtAttribute' => false,
	        ],
	        'lastSendTime' => [
	            'class' => TimestampBehavior::class,
	            'createdAtAttribute' => 'nu_last_send_time',
	            'updatedAtAttribute' => false,
	        ],
	    ];
	}

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tool_notify_user}}';
    }
    
    /**
     * 为联表操作做准备
     * {@inheritDoc}
     * @see \yii\db\ActiveRecord::attributes()
     */
    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), []);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        //静态默认值由规则来赋值
        //[['status'], 'default', 'value' => self::STATUS_ON],
        //[['hits'], 'default', 'value' => Yii::$app->params['config.hits']],
        return [
            [['nu_id'], 'required'],
            [['nu_id', 'nu_userid', 'nu_fr_id', 'nu_reg_time', 'nu_star', 'nu_is_sms_white', 'nu_is_notify_white', 'nu_is_email_white', 'nu_last_login_time', 'nu_last_order_time', 'nu_last_send_time', 'created_at', 'updated_at', 'nq_user_id'], 'integer'],
            [['nu_order_total'], 'number'],
            [['nu_username', 'nu_realname', 'nq_phone', 'nq_email'], 'string'],
            [['nu_phone'], 'string', 'max' => 11],
            [['nu_email'], 'string', 'max' => 38],
            [['nu_comment'], 'string', 'max' => 255],
            [['nu_province', 'nu_city', 'nu_area'], 'string', 'max' => 20],
            [['nu_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nu_id' => 'ID',
            'nu_userid' => '关系的系统用户',
            'nu_username' => '用户名（昵称）',
            'nu_realname' => '真实名称',
            'nu_phone' => '手机号码',
            'nu_email' => '邮箱',
            'nu_comment' => '备注',
            'nu_fr_id' => '来源id',
            'nu_order_total' => '交易总额',
            'nu_reg_time' => '获取用户的日期',
            'nu_star' => '用户星级',
            'nu_province' => '省',
            'nu_city' => '市',
            'nu_area' => '区县',
            'nq_phone' => '手机号码',
            'nq_email' => '邮箱',
            'nq_user_id' => '用户ID',
            'nu_is_sms_white' => '可否发短信',//可发名单
            'nu_is_notify_white' => '可否发站内信',//可发名单
            'nu_is_email_white' => '可否发邮件',//可发名单
            'nu_last_login_time' => '最后登录日期',
            'nu_last_order_time' => '最后下单日期',
            'nu_last_send_time' => '最后发送日期',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
        ];
    }
    
    /**
     * 添加用户到指定的队列，执行发送
     * @param Model $models
     * @param integer $groupId
     * @return number
     */
    public static function AddToNotifySmsQueue($models, $groupId)
    {
        $smsTotal = 0;
        //队列不存在
        if(!NotifyGroup::find()->where(['ng_id' => $groupId])->exists()) {
            return $smsTotal;
        }
        
        //此处可优化：如，每10条，执行一次插入
        $notifySmsQueueModel = new NotifySmsQueue();
        foreach ($models as $model) {
            if($model->nu_is_sms_white && !empty($model->nu_phone)) {
                $notifySmsQueueModel->nq_sms_id = null;
                $notifySmsQueueModel->isNewRecord = true;
                $notifySmsQueueModel->nq_nu_id = $model->nu_id;
                $notifySmsQueueModel->nq_ng_id = $groupId;
                $notifySmsQueueModel->nq_user_id = $model->nu_userid;
                $notifySmsQueueModel->nq_phone = $model->nu_phone;
                $notifySmsQueueModel->nq_sms_send_time = 0;
                $notifySmsQueueModel->nq_sms_arrive_time = 0;
                if($notifySmsQueueModel->save(false)) {
                    $smsTotal++;
                }
            }
        }
        
        //重新计算发送量：ng_count
        NotifyGroup::updateAll(['ng_count' => NotifySmsQueue::find()->where(['nq_ng_id' => $groupId])->count('nq_sms_id')], ['ng_id' => $groupId]);
        
        return $smsTotal;
    }
    
    /**
     * 添加用户到指定的队列，执行发送
     * @param Model $models
     * @param integer $groupId
     * @return number
     */
    public static function AddToNotifyEmailQueue($models, $groupId)
    {
        /**
         * @todo
         * 待实现
         */
    }
    
    /**
     * 添加用户到指定的队列，执行发送
     * @param Model $models
     * @param integer $groupId
     * @return number
     */
    public static function AddToNotifySiteQueue($models, $groupId)
    {
        /**
         * @todo
         * 待实现
         */
    }

    /**
     * @inheritdoc
     * @return NotifyUserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NotifyUserQuery(get_called_class());
    }
}
