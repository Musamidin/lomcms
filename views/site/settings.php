<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = 'Настройки Администратора';
$this->params['breadcrumbs'][] = $this->title;
?>
<div ng-controller="AppCtrlSettings" class="site-settings">
    <div id="settings" class = "body-content-page">
      <input type="hidden" id="token" name="token" value="<?=md5(Yii::$app->session->getId().'opn');?>"/>
      <div id="tabsSett">
        <ul>
          <li><a href="#tabs-101">Справочник</a></li>
          <li><a href="#tabs-102">Пользователи</a></li>
          <li><a href="#tabs-103">Шаблон Залоговый Билета</a></li>
        </ul>
        <div id="tabs-101">
            4645645645645645645
        </div>
        <div id="tabs-102">
            23123123123123
        </div>
        <div id="tabs-103">
            <textarea name="template" id="tinymceeditor" ng-model="poster.template">Next, start a free trial!</textarea>
            <br/>
            <button ng-click="savetemplate()" class="btn btn-info">Сохранить шаблон</button>
            <br/>
            <br/>
            <button ng-click="test()" class="btn btn-info">Test</button>
            <div class="currView"></div>
        </div>
      </div>
    </div>


</div>
<br/>
