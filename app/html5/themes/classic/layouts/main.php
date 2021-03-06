<?php
/**
 * @link http://www.turen2.com/
 * @copyright Copyright (c) 土人开源CMS
 * @author developer qq:980522557
 */

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\H5Asset;
use app\assets\AppAsset;
use app\assets\QrcodeAsset;
use yii\helpers\Url;

H5Asset::register($this);
QrcodeAsset::register($this);
AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" style="">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">
    <script>document.documentElement.style.fontSize =document.documentElement.clientWidth/750*40 +"px";</script>
    <?= Html::csrfMetaTags() ?>
    <meta name="format-detection" content="telephone=no">
    <meta name="current-url" content="<?= Yii::$app->request->absoluteUrl ?>">
    <title><?= Html::encode($this->title) ?></title>
    <link type="image/x-icon" href="./favicon.ico" rel="shortcut icon">
    
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="tui-page-group ">
	<div class="tui-page tui-page-current">
		<div class="tui-header tui-header-success">
			<div class="tui-header-left"></div>
			<div class="title">默认首页</div>
			<div class="tui-header-right"></div>
		</div>
		
		<div class="tui-content navbar">
    		<?= $content ?>
    		
    		<?= $this->render('_client') ?>
		</div>
	</div>
	
	<?= $this->render('_nav') ?>
	
	<!-- 扫描二维码 -->
	<div class="wap-qrcode-container">
		<p class="example1"><?= Yii::$app->params['config_site_name'] ?></p>
		<div class="wap-qrcode-image" id="wap-qrcode"></div>
		<p class="example1">微信“扫一扫”浏览</p>
	</div>
	
	<!-- 返回顶部 -->
	<a class="diy-gotop external" style="position: fixed; overflow: hidden; z-index: 999; bottom: 55px; right: 10px; text-align: left; display: none;" id="gotop">
		<div style="background: #ff8000; opacity: 0.5; line-height: 34px; text-align: center; border-radius: 32px; height: 32px; width: 32px;">
			<i class="icon icon-top1" style="color: #ffffff; font-size: 22px;"></i>
		</div>
	</a>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>