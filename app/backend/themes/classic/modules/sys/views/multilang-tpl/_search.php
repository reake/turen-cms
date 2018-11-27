<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ActiveRecord;

/* @var $this yii\web\View */
/* @var $model app\models\sys\MultilangTplSearch */
/* @var $form yii\widgets\ActiveForm */

$isAll = true;
foreach ($model->attributes as $key => $value) {
    if(!is_null($value)) {
        $isAll = false;
    }
}
?>

<div class="multilang-tpl-search toolbar-tab">
	<ul class="fl">
        <li class="<?= $isAll?'on':''?>"><?= Html::a('全部', ['index']) ?></li>
	</ul>
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'id' => 'searchform',
    'options' => ['class' => 'fr'],
    ]); ?>
		<span class="keyword">
			<?= Html::activeInput('text', $model, 'keyword', ['class' => 'input']) ?>
		</span>
		<a class="s-btn" href="javascript:;" onclick="searchform.submit();">查询</a>
	<?php ActiveForm::end(); ?>
</div>