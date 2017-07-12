<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\assets\LoginAsset;

LoginAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title='SMS рассылка') ?></title>
    <?php $this->head() ?>
</head>
<body class="login-page">
<?php $this->beginBody() ?>

    <div class="wrap">
        <div class="container"><?= $content; ?></div>
    </div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>