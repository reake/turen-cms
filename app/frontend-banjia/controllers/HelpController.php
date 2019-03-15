<?php
/**
 * @link http://www.turen2.com/
 * @copyright Copyright (c) 土人开源CMS
 * @author developer qq:980522557
 */

namespace app\controllers;

use Yii;
use app\components\Controller;
use common\models\cms\Article;
use common\models\cms\Column;
use yii\web\NotFoundHttpException;

/**
 * help controller
 */
class HelpController extends Controller
{
    public $layout = '/easy_main';

    public function actionIndex($slug = null)
    {
        $models = Article::find()->active()->where(['columnid' => Yii::$app->params['config_face_banjia_cn_help_center_column_id']])->orderBy(['orderid' => SORT_DESC])->all();
        $currentModel = Article::findOne(['slug' => $slug]);
        $columnModel = Column::findOne(['id' => Yii::$app->params['config_face_banjia_cn_help_center_column_id']]);
        if($columnModel || (!is_null($slug) && $currentModel)) {
            return $this->render('index', [
                'columnModel' => $columnModel,
                'models' => $models,
                'slug' => $slug,
                'currentModel' => $currentModel,
            ]);
        } else {
            throw new NotFoundHttpException('请求页面不存在！');
        }
    }

    public function actionDetail($slug)
    {
        $model = null;
        return $this->render('detail', [
            'model' => $model,
        ]);
    }
}
