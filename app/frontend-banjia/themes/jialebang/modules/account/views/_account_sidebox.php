<?php
/**
 * @link http://www.turen2.com/
 * @copyright Copyright (c) 土人开源CMS
 * @author developer qq:980522557
 */

use yii\helpers\Url;

$route = Yii::$app->controller->getRoute();

$links = [
    [
        'route' => 'account/center/info',
        'label' => '<i class="iconfont jia-aboutus"></i> 基本资料',
        'isPost' => false,
    ],
    [
        'route' => 'account/order/list',
        'label' => '<i class="iconfont jia-i-order"></i> 服务订单',
        'isPost' => false,
    ],
    [
        'route' => 'account/ticket/list',
        'label' => '<i class="iconfont jia-File"></i> 工单管理',
        'isPost' => false,
    ],
    [
        'route' => 'account/company/info',
        'label' => '<i class="iconfont jia-identityauthentication"></i> 企业资质',
        'isPost' => false,
    ],
    [
        'route' => 'account/safe/info',
        'label' => '<i class="iconfont jia-security"></i> 账户安全',
        'isPost' => false,
    ],
    [
        'route' => 'account/msg/list',
        'label' => '<i class="iconfont jia-task"></i> 消息中心',
        'isPost' => false,
    ],
    [
        'route' => 'account/user/logout',
        'label' => '<i class="iconfont jia-ico-exit"></i> 退出',
        'isPost' => true,
    ],
];
?>
<ul class="sidelist">
    <?php foreach ($links as $link) { ?>
        <li><a href="<?= Url::to(['/'.$link['route']]) ?>" class="<?= ($link['route'] == $route)?'on':'' ?>" data-method="<?= $link['isPost']?'post':'' ?>"><?= $link['label'] ?></a></li>
    <?php } ?>
</ul>