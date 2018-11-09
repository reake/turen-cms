<?php

namespace app\modules\cms\controllers;

use Yii;
use app\models\cms\DiyField;
use app\models\cms\DiyFieldSearch;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\actions\SimpleMoveAction;
use app\actions\CheckAction;
use app\models\cms\Column;
use yii\helpers\Html;
use app\actions\ValidateAction;

/**
 * DiyFieldController implements the CRUD actions for DiyField model.
 */
class DiyFieldController extends Controller
{
    public function actions()
    {
        $request = Yii::$app->getRequest();
        return [
            //简单排序
            'simple-move' => [
                'class' => SimpleMoveAction::class,
                'className' => DiyField::class,
                'id' => $request->get('id'),
                'type' => $request->get('type'),
                'orderid' => $request->get('orderid'),
                'nameField' => 'fd_title',
            ],
            'validate-title' => [
                'class' => ValidateAction::class,
                'className' => DiyField::class,
                'fieldname' => 'fd_title',
                'fieldvalue' => $request->get('DiyField'),
                'primaryId' => $request->get('id'),
            ],
            'validate-name' => [
                'class' => ValidateAction::class,
                'className' => DiyField::class,
                'fieldname' => 'fd_name',
                'fieldvalue' => $request->get('DiyField'),
                'primaryId' => $request->get('id'),
            ],
            'check' => [
                'class' => CheckAction::class,
                'className' => DiyField::class,
                'id' => $request->get('id'),
            ],
        ];
    }
    
    /**
     * @inheritdoc
      * 强制使用post进行删除操作，post受csrf保护
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all DiyField models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DiyFieldSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $columnList = [];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new DiyField model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DiyField();
        $model->loadDefaultValues();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	Yii::$app->getSession()->setFlash('success', $model->fd_title.' 添加成功，结果将展示在列表。');
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DiyField model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	Yii::$app->getSession()->setFlash('success', $model->fd_title.' 已经修改成功！');
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Deletes an existing DiyField model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id, $returnUrl = ['index'])
    {
        $model = $this->findModel($id);
        $model->delete();
        
        if(Yii::$app->getRequest()->isAjax) {
            return $this->asJson([
                'state' => true,
                'msg' => $model->fd_title.' 已经成功删除！',
            ]);
        }
        
        Yii::$app->getSession()->setFlash('success', $model->fd_title.' 已经成功删除！');
        return $this->redirect($returnUrl);
    }
    
    /**
     * ajax获取类型对应的栏目 checkboxlist
     */
    public function actionColumnCheckBoxList()
    {
        $typeid = Yii::$app->getRequest()->post('typeid', null);
        
        $items = Column::ColumnListByType($typeid);
        $model = new DiyField();
        
        if($items) {
            return $this->asJson([
                'state' => true,
                'msg' => Html::activeCheckboxList($model, 'columnid_list', $items, ['tag' => 'span', 'separator' => '&nbsp;&nbsp;&nbsp;']),
            ]);
        } else {
            return $this->asJson([
                'state' => true,
                'msg' => '请先选择栏目类型',
            ]);
        }
    }
    
    /**
     * 批量提交并处理
     * @param string $type delete | order
     * @return \yii\web\Response
     */
    public function actionBatch($type)
    {
        if($type == 'order') {//全局提交
            $ids = Yii::$app->getRequest()->post('checkid', []);
            $orders = Yii::$app->getRequest()->post('orderid', []);
            foreach ($ids as $key => $id) {
                if($model = DiyField::find()->current()->andWhere(['id' => $id])->one()) {
                    $model->orderid = $orders[$key];
                    $model->save(false);
                }
            }
            Yii::$app->getSession()->setFlash('success', '已完成批量排序操作！');
        }
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the DiyField model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return DiyField the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DiyField::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('此请求页面不存在。');
        }
    }
}