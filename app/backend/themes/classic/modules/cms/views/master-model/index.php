<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use app\models\cms\Column;
use yii\widgets\ActiveForm;
use app\widgets\edititem\EditItemWidget;
use app\models\cms\DiyField;
use app\models\cms\MasterModel;
use app\models\cms\Cate;
use app\models\cms\Flag;

/* @var $this yii\web\View */
/* @var $searchModel app\models\cms\MasterModelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $diyModel->dm_title.'列表';
?>

<?= $this->render('_search', ['model' => $searchModel, 'diyModel' => $diyModel, 'modelid' => $modelid]); ?>

<?php $form = ActiveForm::begin([
    'enableClientScript' => false,
    'options' => ['id' => 'batchform'],
]); ?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="data-table">
	<tr align="left" class="head">
		<td width="4%" class="first-column"><input type="checkbox" name="checkid" id="checkid" onclick="turen.com.checkAll(this.checked);"></td>
		<td width="5%">ID</td>
		<td width="24%"><?= $dataProvider->sort->link('title', ['label' => '标题']) ?></td>
		<td width="11%">所属栏目</td>
		<?php if(Yii::$app->params['config.openCate']) { ?>
		<td width="8%">所属类别</td>
		<?php } ?>
		
		<?php foreach (MasterModel::DisplayFieldModelList() as $listModel) { ?>
		<td width="8%"><?= $listModel->fd_title ?></td>
		<?php } ?>
		
		<td width="5%"><?= $dataProvider->sort->link('orderid', ['label' => '排序']) ?></td>
		<td width="10%"><?= $dataProvider->sort->link('posttime', ['label' => '发布时间']) ?></td>
		<td class="end-column">操作</td>
	</tr>
	<?php foreach ($dataProvider->getModels() as $key => $model) {
		$options = [
	        'title' => '点击进行显示和隐藏操作',
	        'data-url' => Url::to(['check', 'kid' => $model->id, 'mid' => $diyModel->dm_id]),
	        'onclick' => 'turen.com.updateStatus(this)',
        ];
		$checkstr = Html::a(($model->status?'显示':'隐藏'), 'javascript:;', $options);
		
		$options = [
		    'data-url' => Url::to(['delete', 'id' => $model->id, 'mid' => $diyModel->dm_id, 'returnUrl' => Url::current()]),
		    'onclick' => 'turen.com.deleteItem(this, \''.$model->title.'\')',
		];
		$delstr = Html::a('删除', 'javascript:;', $options);
	?>
	
	<tr align="left" class="data-tr">
		<td  class="first-column">
			<input type="checkbox" name="checkid[]" id="checkid[]" value="<?= $model->id; ?>">
		</td>
		<td><?= $model->id; ?></td>
		<td><span class="title" style="color:<?= $model->colorval; ?>;font-weight:<?= $model->boldval; ?>"><?= $model->title; ?><span class="title-flag"><?= implode('&nbsp;', $model->activeFlagList($modelid)); ?></span><?=empty($model->picurl)?'':' <span class="titpic"><i class="fa fa-picture-o"></i></span>'?></span></td>
		<td><?= Column::ColumnName($model->columnid).' ['.$model->columnid.']'; ?></td>
		<?php if(Yii::$app->params['config.openCate']) { ?>
		<td><?= Cate::CateName($model->cateid) ?></td>
		<?php } ?>
		
		<?php foreach (MasterModel::DisplayFieldModelList() as $listModel) { ?>
		<td><?= $model->{DiyField::FIELD_PRE.$listModel->fd_name} ?></td>
		<?php } ?>
		
		<td><?= EditItemWidget::widget([
		    'model' => $model,
		    'primaryKey' => 'id',
		    'attribute' => 'orderid',
		    'url' => Url::to(['/cms/master-model/edit-item', 'mid' => $diyModel->dm_id]),
		    'options' => [],
		]); ?></td>
		<td><?= Yii::$app->getFormatter()->asDate($model->posttime); ?></td>
		<td class="action end-column"><span><?= $checkstr; ?></span> | <span><a href="<?= Url::to(['update', 'mid' => $diyModel->dm_id, 'id' => $model->id]) ?>">修改</a></span> | <span class="nb"><?= $delstr; ?></span></td>
	</tr>
	<?php } ?>
</table>
<?php ActiveForm::end(); ?>

<?php //判断无记录样式
if(empty($dataProvider->count))
{
	echo '<div class="data-empty">暂时没有相关的记录</div>';
}
?>

<div class="bottom-toolbar clearfix">
	<span class="sel-area">
    	<span class="sel-name">选择：</span> 
    	<a href="javascript:turen.com.checkAll(true);">全选</a> - 
    	<a href="javascript:turen.com.checkAll(false);">反选</a>
    	<span class="op-name">操作：</span>
    	<a href="javascript:turen.com.batchSubmit('<?=Url::to(['batch', 'mid' => $diyModel->dm_id, 'type' => 'delete'])?>', 'batchform');">删除</a>
	</span>
	<?= Html::a('添加新'.$diyModel->dm_title, ['create', 'mid' => $diyModel->dm_id], ['class' => 'data-btn']) ?>
	<div class="page">
    	<?= LinkPager::widget([
    	    'pagination' => $dataProvider->getPagination(),
    	    'options' => ['class' => 'page-list', 'tag' => 'div'],
    	    'activePageCssClass' => 'on',
    	    'firstPageLabel' => '首页',
    	    'lastPageLabel' => '尾页',
    	    'nextPageLabel' => '下页',
    	    'prevPageLabel' => '上页',
    	    'linkContainerOptions' => ['tag' => 'span'],
    	]);
    	?>
    </div>
</div>

<div class="quick-toolbar">
	<div class="qiuck-warp">
		<div class="quick-area">
			<span class="sel-area">
                <span class="sel-name">选择：</span>
            	<a href="javascript:turen.com.checkAll(true);">全选</a> -
            	<a href="javascript:turen.com.checkAll(false);">反选</a>
            	<span class="op-name">操作：</span>
            	<a href="javascript:turen.com.batchSubmit('<?=Url::to(['batch', 'type' => 'delete'])?>', 'batchform');">删除</a>
            	<span class="total">共 <?= $dataProvider->getTotalCount() ?> 条记录</span>
			</span>
			<?= Html::a('添加新'.$diyModel->dm_title, ['create', 'mid' => $diyModel->dm_id], ['class' => 'data-btn']) ?>
			<span class="page-small">
			<?= LinkPager::widget([
			    'pagination' => $dataProvider->getPagination(),
			    'options' => ['class' => 'page-list', 'tag' => 'div'],
			    'activePageCssClass' => 'on',
			    'firstPageLabel' => '首页',
			    'lastPageLabel' => '尾页',
			    'nextPageLabel' => '下页',
			    'prevPageLabel' => '上页',
			    'linkContainerOptions' => ['tag' => 'span'],
			]);
			?>
			</span>
		</div>
		<div class="quick-area-bg"></div>
	</div>
</div>
<p class="cp tc"><?= Yii::$app->params['config_copyright'] ?></p>

