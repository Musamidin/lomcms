<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title='Ломбард') ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<header>
    <div class="topLine"></div>
    <div class="container">
        asdas
    </div>
</header>

<div class="wrap">
    <div class="container">
        <?php
    if(!\Yii::$app->getUser()->isGuest){
        NavBar::begin([
            'brandLabel' => '<span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp;&nbsp;CS.KG',
            'brandUrl' => '/',
            'options' => [
                'class' => 'navbar-fixed-top navbar navbar-inverse',
                'id'=>'bs-example-navbar-collapse-9'
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav collapse navbar-collapse navbar-left'],
            // 'items' => [
            //     ['label' => 'Главная', 'url' => ['/site/index']],
            // ]
        ]);

        echo '<form class="navbar-form navbar-left w75pc"><div class="form-group has-feedback w100pc">
          <button type="button" class="btn btn-primary" onclick="addBtn()" ng-click="onAddProd()"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;Выдать кредит</button><input type="text" class="spoz form-control ng-pristine ng-valid ng-empty ng-touched" placeholder="Введите Ф.И.О. или номер билета" ng-model="searchInput" id="searchId" ng-keyup="onSearch($event)" aria-required="true" aria-invalid="true"><span class="glyphicon glyphicon-search form-control-feedback"></span>
        </div></form>'; 
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                ['label' => 'Меню',  
                    'url' => ['#'],
                    //'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                    'items' => [
                        ['label' => '<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>&nbsp;Настройки', 'url' => ['/settings']],
                        ['label' => '<span class="glyphicon glyphicon-book" aria-hidden="true"></span>&nbsp;Журнал', 'url' => ['/log']],
                        ['label' => '<span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span>&nbsp;Отчет', 'url' => ['/report']],
                        ['label' => '<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>&nbsp;Отчет по sms', 'url' => ['/smsreport']],
                    ],
                ],
                ['label' => 'Касса',  
                    'url' => ['#'],
                    //'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                    'items' => [
                        ['label' => '<span class="glyphicon glyphicon-inbox" aria-hidden="true"></span>&nbsp;KGS:&nbsp;<span id="main-kassa">121551</span>', 'url' => ['#']],
                        ['label' => '<span class="glyphicon glyphicon-inbox" aria-hidden="true"></span>&nbsp;USD:&nbsp;<span id="main-kassa">121551</span>', 'url' => ['#']],
                    ],
                ],
                Yii::$app->user->isGuest ? (
                    ['label' => 'Login', 'url' => ['/site/login']]
                ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Выход (' . Yii::$app->user->identity->login . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
                )
            ],
            'encodeLabels' => false,
        ]);
        NavBar::end();
    }
        ?>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <div class="col-md-12 main" style="min-height:500px;">
            <?= $content ?>
        </div>        
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="col-md-6">
            <div class="col-md-12"></div>
            <div class="col-md-12">© Cloud Services 2017</div>
        </div>
        <div class="col-md-6">
            <div class="col-md-12"></div>
            <div class="col-md-12">Моб. +996 772 03 03 17</div>
            <div class="col-md-12"><a style="color: white;" href="http://cs.kg">www.cs.kg</a></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>