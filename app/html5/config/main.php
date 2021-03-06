<?php 
/**
 * @link http://www.turen2.com/
 * @copyright Copyright (c) 土人开源CMS
 * @author developer qq:980522557
 */

use app\bootstrap\Init;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-html5',
    'timeZone' => 'Asia/Shanghai',
    'basePath' => dirname(__DIR__),
    'name' => 'Turen2',
    'version' => '1.0',
    'charset' => 'UTF-8',
    'sourceLanguage' => 'zh-CN', // 默认源语言
    'language' => 'en-US', // 默认当前环境使用的语言
    'controllerNamespace' => 'app\\controllers',
    'defaultRoute' => 'site/home', // 默认路由，后台默认首页
    'layout' => 'main', // 默认布局
    'bootstrap' => [
        'log',
        'devicedetect',//客户端检测
        [
            'class' => Init::class,//初始化环境：模板、语言、缓存
        ],
    ],
    'modules' => [
        //
    ],
    'components' => [
        'devicedetect' => [
            'class' => 'alexandernst\devicedetect\DeviceDetect',
        ],
        'request' => [
            'class' => 'yii\web\Request',
            'cookieValidationKey' => 'OUX1YppF-bHW9cm86EAmg4MwmBQ6Xvni',
            'csrfParam' => '_csrf-html5',
            'enableCsrfValidation' => true,//默认显示csrf验证（前提）
            'enableCsrfCookie' => true,//默认显示了基于cookie的csrf，否则将以session传递验证数据
            'enableCookieValidation' => true,//默认配合上面启用验证
        ],
        /*
         'user' => [
             'identityClass' => 'common\models\User',
             'enableAutoLogin' => true,
             'identityCookie' => ['name' => '_identity-html5', 'httpOnly' => true],
         ],
         */
        'session' => [
            // this is the name of the session cookie used for login on the html5
            'name' => 'app-html5',
        ],
        'view' => [
            // 主题配置(module目录下的views > 根目录下的views > 主题下的模板)
            'class' => 'app\components\View',
            //theme的功能是重新映射的关系，即将原模板系统默认的目录结果映射为自定义目录结构！！
            //默认机制是，模板目录结构与控制器挂件模块等结构保持一致。
            /*
            'theme' => [
                'class' => 'yii\base\Theme',
                //已经在module中设置了，不需要重复设置
                'basePath' => '@app/themes/classic1',//主题所在文件路径
                'baseUrl' => '@app/themes/classic1',//与主题相关的url资源路径
                'pathMap' => [
                    '@app/modules' => '@app/themes/classic',//模块模板
                    '@app/widgets' => '@app/themes/classic1/web/widgets',//部件模板
                    '@app/layouts' => '@app/themes/classic/web/layouts',//内容模板
                    '@app/views' => '@app/themes/classic/web/views',//布局模板
                ],
            ]
            */
        ],
        //前端资源管理
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            //强制更换核心资源的版本，前端兼容性考虑
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => '@app/assets/jquery/',
                    'js' => [
                        'jquery.min.js'//
                    ]
                ],
            ],
        ],
        //异常处理
        'errorHandler' => [
            'class' => 'yii\web\ErrorHandler',
            'errorAction' => 'site/error',//默认显示pc版路由
        ],
        //伪静态管理
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'i18n' => [// 多语言组件配置，每个应用app都有独立的多语言配置，减少相关度
            'translations' => [
                //只配置一个应用
                'h5'=> [// 匹配所有翻译//通用配置，使用*配置所有应用，再以fileMap分隔
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath'=>'@app/messages',//统一存储为一个翻译包
                    //'sourceLanguage' => Yii::$app->sourceLanguage,
                    /*
                     'fileMap'=>[// 简单的映射
                     'common'=>'common.php',
                     'yii' => 'yii.php',
                     ],
                     */
                    //'on missingTranslation' => ['app\events\TranslationEventHandler', 'handleMissingTranslation'],//事件解决，获取未翻译内容
                ],
            ]
        ],
        //日志管理
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    
    'params' => $params,
];
