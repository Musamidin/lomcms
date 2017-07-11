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
    <title><?= Html::encode($this->title='Ювелирный магазин') ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<header>
    <div class="container">
        asdas
    </div>
</header>

<div class="wrap">
    <div class="container">
        <?php
    if(!\Yii::$app->getUser()->isGuest){
        NavBar::begin([
            //'brandLabel' => 'MyService.kg',
            //'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar navbar-inverse',
                'id'=>'bs-example-navbar-collapse-9'
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav collapse navbar-collapse navbar-left'],
            'items' => [
                ['label' => 'Главная', 'url' => ['/site/index']],
                ['label' => 'Отчет', 'url' => ['/report']]
            ],
        ]);        
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
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
