<?php
/**
 * @link http://www.turen2.com/
 * @copyright Copyright (c) 土人开源CMS
 * @author developer qq:980522557
 */
namespace app\models\cms;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use app\behaviors\InsertLangBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use app\widgets\select2\TaggableBehavior;
use app\widgets\laydate\LaydateBehavior;

/**
 * This is the model class for table "{{%cms_video}}".
 *
 * @property string $id 视频信息id
 * @property string $columnid 所属栏目id
 * @property int $cateid 类别id
 * @property string $parentid 所属栏目上级id
 * @property string $parentstr 所属栏目上级id字符串
 * @property string $cateid 所属类别id
 * @property string $catepid 所属类别上级id
 * @property string $catepstr 所属类别上级id字符串
 * @property string $title 标题
 * @property string $colorval 字体颜色
 * @property string $boldval 字体加粗
 * @property string $flag 属性
 * @property string $source 信息来源
 * @property string $author 作者编辑
 * @property string $linkurl 跳转链接
 * @property string $keywords 关键词
 * @property string $description 摘要
 * @property string $content 详细内容
 * @property string $picurl 缩略视频
 * @property string $videolink 视频地址
 * @property string $hits 点击次数
 * @property string $orderid 排列排序
 * @property int $posttime 发布时间
 * @property int $status 审核状态
 * @property int $delstate 删除状态
 * @property string $deltime 删除时间
 * @property string $lang
 */
class Video extends \app\models\base\Cms
{
	public $keyword;

	public function behaviors()
	{
	    return [
	        'taggabble' => TaggableBehavior::class,//tags
	        'posttime' => [
	            'class' => LaydateBehavior::class,
	            'timeAttribute' => 'posttime',
	        ],
	        'timemap' => [
	            'class' => TimestampBehavior::class,
	            'createdAtAttribute' => 'created_at',
	            'updatedAtAttribute' => 'updated_at'
	        ],
	        'insertLang' => [//自动填充多站点和多语言
	            'class' => InsertLangBehavior::class,
	            'insertLangAttribute' => 'lang',
	        ],
	        //动态值由此属性行为处理
	        'defaultPosttime' => [
	            'class' => TimestampBehavior::class,
	            'createdAtAttribute' => 'posttime',
	            'updatedAtAttribute' => false,
	        ],
	        'defaultOrderid' => [
	            'class' => AttributeBehavior::class,
	            'attributes' => [
	                ActiveRecord::EVENT_BEFORE_INSERT => 'orderid',
	                //ActiveRecord::EVENT_BEFORE_UPDATE => 'attribute2',
	            ],
	            'value' => function ($event) {
    	            if(empty($this->orderid)) {
    	                $maxModel = self::find()->current()->orderBy(['orderid' => SORT_DESC])->one();
    	                if($maxModel) {
    	                    return $maxModel->orderid + 1;
    	                } else {
    	                    return Yii::$app->params['config.orderid'];//配置默认值
    	                }
    	            }
    	            
    	            return $this->orderid;
	            }
            ],
	    ];
	}
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_video}}';
    }
    
    /**
     * 为联表操作做准备
     * {@inheritDoc}
     * @see \yii\db\ActiveRecord::attributes()
     */
    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), ['keyword']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['columnid', 'title', 'videolink'], 'required'],
            [['columnid', 'parentid', 'cateid', 'catepid', 'deltime', 'delstate'], 'integer'],
            [['content', 'videolink', 'posttime'], 'string'],
            [['parentstr', 'catepstr', 'title'], 'string', 'max' => 80],
            [['colorval', 'boldval'], 'string', 'max' => 10],
            [['source', 'author', 'keywords'], 'string', 'max' => 50],
            [['linkurl', 'description'], 'string', 'max' => 255],
            [['picurl'], 'string', 'max' => 100],
            [['lang'], 'string', 'max' => 8],
            [['author'], 'default', 'value' => $this->getAdmin()->username],
            [['flag'], 'safe'],
            //静态默认值由规则来赋值
            [['status'], 'default', 'value' => self::STATUS_ON],
            [['hits'], 'default', 'value' => Yii::$app->params['config.hits']],
            ['tagNames', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '视频信息ID',
            'columnid' => '所属栏目',
            'cateid' => '所属类别',
            'parentid' => '所属栏目上级ID',
            'parentstr' => '所属栏目上级ID字符串',
            'cateid' => '所属所属类别',
            'catepid' => '所属类别上级ID',
            'catepstr' => '所属类别上级ID字符串',
            'title' => '视频标题',
            'colorval' => '字体颜色',
            'boldval' => '字体加粗',
            'flag' => '展示标记',
            'tags' => '标签',
            'source' => '信息来源',
            'author' => '作者编辑',
            'linkurl' => '跳转链接',
            'keywords' => 'SEO关键词',
            'description' => 'SEO摘要',
            'content' => '详细内容',
            'picurl' => '视频缩略图',
            'videolink' => '视频地址',
            'hits' => '点击次数',
            'orderid' => '排列排序',
            'posttime' => '发布时间',
            'status' => '审核状态',
            'delstate' => '删除状态',
            'deltime' => '删除时间',
            'lang' => '多语言',
        ];
    }
    
    /**
     * 插入之前整理，且通过过滤器
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        
        //转化flag为字符串
        if(is_array($this->flag)) {
            $this->flag = implode(',', $this->flag);
        }
        
        //添加和编辑，都要处理parentstr
        $columModel = Column::findOne(['id' => $this->columnid]);
        $this->parentid = $columModel->parentid;
        $this->parentstr = $columModel->parentstr;
        
        $cateModel = Cate::findOne(['id' => $this->cateid]);
        $this->catepid = $cateModel->parentid;
        $this->catepstr = $cateModel->parentstr;
        
        return true;
    }

    /**
     * @inheritdoc
     * @return VideoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VideoQuery(get_called_class());
    }
}