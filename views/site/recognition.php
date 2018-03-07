<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Учет расходов и приходов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div ng-controller="AppCtrlRecognition" class="site-recognition">
    <div id="recognition" class = "body-content-page">
        <div class="row">
          <div class="col-md-3"></div>
          <div class="col-md-2 text-center">
            <button type="button" ng-click="addlog()" class="btn btn-success">
            <span class="glyphicon glyphicon-sort" aria-hidden="true"></span>&nbsp;Регистрация
          </button>
          </div>
          <div class="col-md-3">
            <div class="input-group date rep-dpicker">
              <input type="text" class="form-control getbydatetime">
              <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
            </div>
          </div>
          <div class="col-md-3">
            <input type="hidden" id="token" name="token" value="<?=md5(Yii::$app->session->getId().'opn');?>"/>
          </div>
        </div>
        <br/>
        <div class="row">
          <div class="col-md-12">
            <table class="table table-striped ml-table">
              <tbody id="thead">
                <tr>
                  <th>Дата</th>
                  <th>Пользователь</th>
                  <th>Расход/Приход</th>
                  <th>Сумма</th>
                  <th>Валюта</th>
                  <th>Описание</th>
                  <th>Действие</th>
                </tr>
              </tbody>
              <tbody>
                <tr dir-paginate="rn in rnlist | itemsPerPage: mainlistPerPage" total-items="totalmainlist" current-page="pagination.current">
                  <td>{{rn.date_system | formatDatetime}}</td>
                  <td>{{rn.user_id}}</td>
                  <td>{{rn.status}}</td>
                  <td>{{rn.summ | fixedto}}</td>
                  <td>{{rn.currency | currFilt }}</td>
                  <td>{{rn.comments}}</td>
                  <td>
                    <div ng-if="<?=Yii::$app->user->identity->role; ?> == '1'">
                    <a href="javascript:void(0)" title="Удалить" ng-click="onDelete(ml)"><span class="glyphicon glyphicon-trash"></span></a>
                  </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <dir-pagination-controls on-page-change="pageChanged(newPageNumber)">
            </dir-pagination-controls>
          </div>
        </div>
    </div>


    <!-- START Dialog box for recognition data-->
    <div style="display:none;" id="dialog-form-recognition">
      <div id="recognition-data">
        <div class="row">
          <div class="col-md-6">
              <textarea id="comments" name="comments" placeholder="Описание" class="form-control"></textarea>
          </div>
          <div class="col-md-6">
              <div class="row">
                <div class="col-md-12">
                  <div class="input-group">
                    <input type="text" name="summ" id="summ" placeholder="Сумма" class="input-sm form-control">
                    <span class="input-group-addon input-sm"></span>
                    <select name="currency" id="currency" class="input-sm form-control">
                        <option></option>
                        <option value="1">KGS</option>
                        <option value="2">USD</option>
                    </select>
                  </div>
                </div>
              </div>
              <br/>
              <div class="row">
                <div class="col-md-12">
                  <div class="input-group">
                    <span class="input-group-addon">Расход/Приход</span>
                    <select id="status_inout" value="" name="status" class="form-control">
                      <option></option>
                      <option value="Расход">Расход</option>
                      <option value="Приход">Приход</option>
                    </select>
                    </div>
                </div>
              </div>
              <br/>
              <div class="row">
                <div class="col-md-12">
                  <input type="checkbox" id="trf" class="form-control">
                  <div class="input-group">
                    <span class="input-group-addon prv-span">Перевод</span>
                    <select id="transfer" value="" name="transfer" class="form-control" style="display: none;">
                      <option></option>
                      <option value="1">Мидас-Кредит</option>
                      <option value="2">Мидас-Голд</option>
                    </select>
                    </div>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
    <!--  END Dialog box for recognition data -->

</div>
