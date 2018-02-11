<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Библиотека';
$this->params['breadcrumbs'][] = $this->title;
?>
<div ng-controller="AppCtrlLibrary" class="site-library">
    <div id="library" class = "body-content-page">

      <ul id="myTab" class="nav nav-tabs">
        <li class="active"><a href="#group" data-toggle="tab">Группа</a></li>
        <li><a href="#sample" data-toggle="tab">Проба</a></li>
        <li><a href="#insertion" data-toggle="tab">Вставка</a></li>
        <li><a href="#deltype" data-toggle="tab">Вид поставки</a></li>
      </ul>
      <div id="myTabContent" class="tab-content">

          <div id="group" class="tab-pane fade active in">
          <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6"><span class="panel-title">Группа</span></div>
                    <div class="col-md-6 text-right"><button class="btn btn-info" ng-click="actionAddLib(1)"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;Добавить</button></div>
                </div>
              </div>
              <div class="panel-body">
              <table class="table">
                <tr ng-repeat="pg in productGroupar">
                  <td><a href="javascript:void(0)" ng-click="actionEditLib(pg,1)">{{pg.name}}</a></td>
                </tr>
              </table>
            </div>
          </div>
        </div>

          <div id="sample" class="tab-pane fade">
          <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6"><span class="panel-title">Проба</span></div>
                    <div class="col-md-6 text-right"><button class="btn btn-info" ng-click="actionAddLib(2)"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;Добавить</button></div>
                </div>
              </div>
              <div class="panel-body">
                <table class="table">
                  <tr ng-repeat="sl in samplesarr">
                    <td><a href="javascript:void(0)" ng-click="actionEditLib(sl,2)">{{sl.name}}</a></td>
                  </tr>
                </table>
            </div>
          </div>
        </div>

          <div id="insertion" class="tab-pane fade">
          <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6"><span class="panel-title">Вставка</span></div>
                    <div class="col-md-6 text-right"><button class="btn btn-info" ng-click="actionAddLib(3)"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;Добавить</button></div>
                </div>
              </div>
              <div class="panel-body">
                <table class="table">
                  <tr ng-repeat="ins in insertionarr">
                    <td><a href="javascript:void(0)" ng-click="actionEditLib(ins,3)">{{ins.name}}</a></td>
                  </tr>
                </table>
            </div>
          </div>
        </div>

          <div id="deltype" class="tab-pane fade">
          <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6"><span class="panel-title">Вид поставки</span></div>
                    <div class="col-md-6 text-right"><button class="btn btn-info" ng-click="actionAddLib(4)"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;Добавить</button></div>
                </div>
              </div>
              <div class="panel-body">
                <table class="table">
                  <tr ng-repeat="td in typeOfDeliveryar">
                    <td><a href="javascript:void(0)" ng-click="actionEditLib(td,4)">{{td.name}}</a></td>
                  </tr>
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--Sall Modal Window-->
    <div id="libModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm" style="width:600px;">
        <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title sm-title-lib">Добавить реализатора</h4>
          </div>
          <div class="modal-body">
            <?php $form = ActiveForm::begin(['id' => 'lib-form']); ?>
              <input type="hidden" name="token" value="<?=md5(Yii::$app->session->getId().'opn'); ?>" id="token"/>
              <input type="hidden" name="dataid" value="" id="dataid"/>
              <input type="hidden" name="state" value="" id="state"/>
              <div class="row mb-row">
                  <div class="col-md-12">
                    <div class="form-group field-mainform-name has-feedback required field-insertion-name">
                      <?= Html::textInput('name','',['placeholder'=>'Наименование','id'=>'sett-field','class'=>'form-control ng-pristine ng-untouched ng-valid ng-empty','ng-model'=>'formData.name']); ?>
                      <span class="glyphicon glyphicon-refresh form-control-feedback"></span>
                    </div>
                  </div>
              </div>
            <?php ActiveForm::end(); ?>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary savebtn" ng-click="onActionSett()">Сохранить</button>
          </div>
        </div>
      </div>
    </div>
    <!--End Sall Modal Window-->

</div>
