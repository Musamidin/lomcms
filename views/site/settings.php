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
          <li><a href="#tabs-101">Пр. настройки</a></li>
          <li><a href="#tabs-102">Пользователи</a></li>
          <li><a href="#tabs-103">Шаблон Залоговый Билета</a></li>
          <li><a href="#tabs-104">% - ставки</a></li>
          <li><a href="#tabs-105">Предметы</a></li>
          <li><a href="#tabs-106">Пробы</a></li>
        </ul>
        <div id="tabs-101">
            fdgdfgdfgdfgd
        </div>
        <div id="tabs-102">
          <div class="row">
            <div class="col-md-1">
              <a class="sett-addbtn btn btn-app bg-olive"  ng-click="addPASBtn('User','Пользователи')"><i class="fa fa-plus-circle"></i></a>
            </div>
              <div class="col-md-11">
                <table class="table table-striped sett-table">
                  <tbody id="thead">
                    <tr>
                      <th>Дата</th>
                       <th>Ф.И.О.</th>
                       <th>Логин</th>
                       <th>Права</th>
                       <th>Статус</th>
                       <th>Описание</th>
                    </tr>
                  </tbody>
                  <tbody>
                      <tr ng-repeat="usr in user">
                        <td>{{usr.datetime | formatDatetime}}</td>
                        <td>{{usr.fio}}</td>
                        <td>{{usr.login}}</td>
                        <td>{{usr.role}}</td>
                        <td>{{usr.status}}</td>
                        <td>{{usr.description}}</td>
                      </tr>
                  </tbody>
              </table>
              </div>
          </div>
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
        <div id="tabs-104">
          <div class="row">
            <div class="col-md-1">
              <a class="sett-addbtn btn btn-app bg-olive" ng-click="addPASBtn('percent','% - ставка')"><i class="fa fa-plus-circle"></i></a>
            </div>
              <div class="col-md-11">
                <table class="table table-striped sett-table">
                  <tbody id="thead">
                    <tr>
                      <th>Дата</th>
                       <th>Наименование</th>
                       <th>Значение</th>
                       <th>Действие</th>
                    </tr>
                  </tbody>
                  <tbody>
                      <tr ng-repeat="per in percent">
                        <td>{{per.datetime | formatDatetime}}</td>
                        <td>{{per.keyname}}</td>
                        <td>{{per.param}}</td>
                        <td>
                          <!--div ng-if="<?=Yii::$app->user->identity->role; ?> == '1'" class="btn-group">
                              <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                Действие <span class="caret"></span>
                             </button>
                              <ul class="dropdown-menu">
                                <li><a href="javascript:void(0)" ng-click="onCalc(per)"><span class="fa fa-edit"></span>&nbsp;Редактировать</a></li>
                                <li><a href="javascript:void(0)" ng-click="onDelete(per.id)"><span class="glyphicon glyphicon-trash"></span>&nbsp;Удалить</a></li>

                              </ul>
                         </div-->
                        </td>
                      </tr>
                  </tbody>
              </table>
              </div>
          </div>
        </div>
        <div id="tabs-105">
          <div class="row">
              <div class="col-md-1">
                <a class="sett-addbtn btn btn-app bg-olive" ng-click="addPASBtn('article','Предмет')"><i class="fa fa-plus-circle"></i></a>
              </div>
              <div class="col-md-11">
                <table class="table table-striped sett-table">
                  <tbody id="thead">
                    <tr>
                      <th>Дата</th>
                       <th>Наименование</th>
                       <th>Действие</th>
                    </tr>
                  </tbody>
                  <tbody>
                      <tr ng-repeat="art in article">
                        <td>{{art.datetime | formatDatetime}}</td>
                        <td>{{art.keyname}}</td>
                        <td></td>
                      </tr>
                  </tbody>
              </table>
              </div>
          </div>
        </div>
        <div id="tabs-106">
          <div class="row">
            <div class="col-md-1">
              <a class="sett-addbtn btn btn-app bg-olive"  ng-click="addPASBtn('sample','Проба')"><i class="fa fa-plus-circle"></i></a>
            </div>
              <div class="col-md-11">
                <table class="table table-striped sett-table">
                  <tbody id="thead">
                    <tr>
                      <th>Дата</th>
                       <th>Наименование</th>
                       <th>Действие</th>
                    </tr>
                  </tbody>
                  <tbody>
                      <tr ng-repeat="sam in sample">
                        <td>{{sam.datetime | formatDatetime}}</td>
                        <td>{{sam.keyname}}</td>
                        <td></td>
                      </tr>
                  </tbody>
              </table>
              </div>
          </div>
        </div>
      </div>
    </div>

    <!-- START Dialog box for Client history data -->
    <div style="display:none;" id="dialog-form-pas" title="Контактные номера">
      <div class="row">
          <div class="col-md-12">
              <?php $form = ActiveForm::begin(['id' => 'sett-form']); ?>
              <div class="col-md-12">
                  <?=$form->field($lib, 'keyname',['options'=>
                    ['tag' => 'div','class'=> 'form-group field-mainform-keyname has-feedback required'],
                    'template'=>'{input}<span class="fa fa-paste form-control-feedback"></span>{error}{hint}'
                    ])->textInput(['autofocus' => false,'placeholder'=>'Наименование','ng-model'=>'settdata.keyname'])->label('Наименование');
                    ?>
              </div>
              <div class="col-md-12">
                <?=$form->field($lib, 'param',['options'=>
                  ['tag' => 'div','class'=> 'hdn form-group field-mainform-param has-feedback required'],
                  'template'=>'{input}<span class="fa fa-paste form-control-feedback"></span>{error}{hint}'
                  ])->textInput(['autofocus' => false,'placeholder'=>'Значение','ng-model'=>'settdata.param'])->label('Наименование');
                  ?>
              </div>
              <?php ActiveForm::end(); ?>
          </div>
      </div>
    </div>
    <!-- END Dialog box for Client history data -->

</div>
<br/>
