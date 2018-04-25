<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'SMS Отчёт';
$this->params['breadcrumbs'][] = $this->title;
?>
<div ng-controller="AppCtrlSmsReport" class="site-smsreport">
    <div id="smsreport" class = "body-content-page">
        <div class="row">
          <div class="col-md-3"></div>
          <div class="col-md-2 text-center">
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
                  <th>Номер тел.</th>
                  <th>Номер СМС</th>
                  <th>Номер ЗБ</th>
                  <th>Текст СМС</th>
                  <th>кол. СМС</th>
                  <th>Статус доставки</th>
                  <th>Статус Отправки</th>
                  <th>Дата</th>
                </tr>
              </tbody>
              <tbody>
                <tr dir-paginate="sms in smslist | itemsPerPage: mainlistPerPage" total-items="totalmainlist" current-page="pagination.current">

                  <td>{{sms.phone}}</td>
                  <td>{{sms.messageID}}</td>
                  <td>{{sms.ticket}}</td>
                  <td>{{sms.msgText}}</td>
                  <td>{{sms.smsCount}}</td>
                  <td>{{sms.dlvr_state}}</td>
                  <td>{{sms.status}}</td>
                  <td>{{sms.datetime | formatDatetime}}</td>
                </tr>
              </tbody>
            </table>
            <dir-pagination-controls on-page-change="pageChanged(newPageNumber)">
            </dir-pagination-controls>
          </div>
        </div>
    </div>
</div>
