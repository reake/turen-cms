<?php 
/**
 * @link http://www.turen2.com/
 * @copyright Copyright (c) 土人开源CMS
 * @author developer qq:980522557
 */

use app\models\ext\AdType;
use common\helpers\BuildHelper;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

//获取数据
$models = AdType::find()->current()->orderBy(['orderid' => SORT_DESC])->all();

$models = BuildHelper::reBuildModelKeys($models, 'id');//重构模型数组索引
$nexus = BuildHelper::getModelNexus($models, AdType::class, 'id', 'parentid');//获取父子关系
$list = BuildHelper::buildList($nexus);

$adTypeArray = [];
foreach ($list as $id => $item) {
    //按照新的关系，重新排序
    $adTypeArray[$id] = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $item['level']-1).(empty($item['level']-1)?'':'|-').($models[$id]->typename);
}
?>

<?php
//-----------------------------------------------
$name = 'config_face_banjia_cn_home_main_ad_type_id';
$value = isset($config[$name])?$config[$name]:null;
?>
<tr>
    <td class="first-column">搬家站点首页主广告位</td>
    <td class="second-column" width="33%">
        <?= Html::dropDownList($name, $value, ArrayHelper::merge([null => '请选择一个配置'], $adTypeArray), ['id' => $name, 'encode' => false]) ?>
    </td>
    <td style="border-bottom: 1px dashed #efefef;">
        Yii::$app->params['<?=$name?>']
    </td>
</tr>