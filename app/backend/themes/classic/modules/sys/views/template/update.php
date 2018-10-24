<?php
/**
 * @link http://www.turen2.com/
 * @copyright Copyright (c) 土人开源CMS
 * @author developer qq:980522557
 */
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $model app\models\sys\Template */

$this->title = '编辑模板: ' . $model->temp_name;
?>
<div class="template-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>