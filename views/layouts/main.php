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
    <div class="container">
        asdas
    </div>
</header>

<div class="wrap">
    <div ng-cloak ng-app="App" class="container">
        <?php
    if(!\Yii::$app->getUser()->isGuest){
      $options = [];
      if(Yii::$app->user->identity->role == 1){
        $options = [
            //['label' => 'Главная', 'url' => ['/site/index']],
            //['label' => 'Отчет', 'url' => ['/report']],
            ['label' => 'Меню',
              'items' => [
                   ['label' => 'Отчёт', 'url' => ['/userreport']],
                   '<li class="divider"></li>',
                   ['label' => 'Учет прочих р/п', 'url' => ['/recognition']],
              ],
            ],
            ['label' => 'Админ',
              'items' => [
                   ['label' => 'Настройки', 'url' => ['/settings']],
                   ['label' => 'Справочник', 'url' => ['/library']],
                   '<li class="divider"></li>',
                   ['label' => 'Отчёт', 'url' => ['/report']],
                   ['label' => 'СМС Отчёт', 'url' => ['/smsreport']],
              ],
            ]
        ];
      }else{
        $options = [
            ['label' => 'Меню',
              'items' => [
                   ['label' => 'Отчёт', 'url' => ['/userreport']],
                   '<li class="divider"></li>',
                   ['label' => 'Учет прочих р/п', 'url' => ['/recognition']],
              ],
            ],
        ];
      }
        NavBar::begin([
            'brandLabel' =>'<span class="glyphicon glyphicon-home" aria-hidden="true"></span> &nbsp;&nbsp;CS.KG',
            'brandUrl' => '/',
            'options' => [
                'class' => 'navbar navbar-fixed-top navbar-inverse',
                'id'=>'bs-example-navbar-collapse-9'
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
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav collapse navbar-collapse navbar-right'],
            'items' => $options,
        ]);
        echo '<form class="navbar-form navbar-nav text-center">
    <button type="button" id="addModal" class="btn btn-success mar-left addModal">
    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;Выдать кредит</button>  <!--data-toggle="modal" data-target="#addDataModal"-->
            <div class="form-group has-success has-feedback">
              <input type="text" placeholder="Введите Ф.И.О. или номер билета" id="searcher" class="min-w input-sm form-control">
              <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>
      <button type="button" id="exchangeModal" class="btn btn-info mar-left exchangeModal">
    <span class="glyphicon glyphicon-random" aria-hidden="true"></span>&nbsp;&nbsp;Конвертация</button>
      </form>';
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
