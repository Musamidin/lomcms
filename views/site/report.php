<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Отчёт';
$this->params['breadcrumbs'][] = $this->title;
?>
<div ng-controller="AppCtrlReport" class="site-report">
    <div id="report" class = "body-content-page">
        <div class="row">
          <div class="col-md-4">
            <input type="hidden" id="token" name="token" value="<?=md5(Yii::$app->session->getId().'opn');?>"/>
          </div>
          <div class="col-md-4">
            <div class="input-group date">
              <input type="text" class="form-control getbydatetime">
              <span class="input-group-addon rep-dpicker"><i class="glyphicon glyphicon-calendar"></i></span>
            </div>
          </div>
          <div class="col-md-4">
          </div>
        </div>
        <br/>
        <div class="row report-border">
          <div class="col-md-rep">
            <table style="display:none;" class="table table-striped">
              <thead>
                <tr>
                  <th colspan="2" style="text-align:center;">Выдача</th>
                </tr>
                <tr>
                  <th>Дата</th>
                  <th>Сумма</th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="vyda in vydacha">
                  <td>{{vyda.date}}</td>
                  <td>{{vyda.summ | fixedto }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-rep">
            <table style="display:none;" class="table table-striped">
              <thead>
                <tr>
                  <th colspan="2" style="text-align:center;">Выкуп</th>
                </tr>
                <tr>
                  <th>Дата</th>
                  <th>Сумма</th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="vyk in vykup">
                  <td>{{vyk.date}}</td>
                  <td>{{vyk.summ | fixedto }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-rep">
            <table style="display:none;" class="table table-striped">
              <thead>
                <tr>
                  <th colspan="2" style="text-align:center;">%-погашения</th>
                </tr>
                <tr>
                  <th>Дата</th>
                  <th>Сумма</th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="compog in comission_pog">
                  <td>{{compog.date}}</td>
                  <td>{{compog.summ | fixedto }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-rep">
            <table style="display:none;" class="table table-striped">
              <thead>
                <tr>
                  <th colspan="2" style="text-align:center;">продление</th>
                </tr>
                <tr>
                  <th>Дата</th>
                  <th>Сумма</th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="comperez in comission_perez">
                  <td>{{comperez.date}}</td>
                  <td>{{comperez.summ | fixedto }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-rep">
            <table style="display:none;" class="table table-striped">
              <thead>
                <tr>
                  <th colspan="2" style="text-align:center;">Ч/П</th>
                </tr>
                <tr>
                  <th>Дата</th>
                  <th>Сумма</th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="chpog in ch_pog">
                  <td>{{chpog.date}}</td>
                  <td>{{chpog.summ | fixedto }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-rep">
            <table style="display:none;" class="table table-striped">
              <thead>
                <tr>
                  <th colspan="2" style="text-align:center;">прочие приходы</th>
                </tr>
                <tr>
                  <th>Дата</th>
                  <th>Сумма</th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="prochpr in proch_prih">
                  <td>{{prochpr.date}}</td>
                  <td>{{prochpr.summ | fixedto }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-rep">
            <table style="display:none;border-right: none;" class="table table-striped">
              <thead>
                <tr>
                  <th colspan="2" style="text-align:center;">прочие расходы</th>
                </tr>
                <tr>
                  <th>Дата</th>
                  <th>Сумма</th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="prochrd in proch_rashod">
                  <td>{{prochrd.date}}</td>
                  <td>{{prochrd.summ | fixedto }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-12 gram-box">
          <div class="col-md-4">
            <table style="border-right: none;" class="table table-striped">
              <thead>
                <tr>
                  <th>Проба</th>
                  <th>Грамм</th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="golds in curr_golds">
                  <td>{{golds.sample}}</td>
                  <td>{{golds.gramm | fixedto }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-4">
            <table style="border-right: none;" class="table table-striped">
              <thead>
                <tr>
                  <th>Залоговые билеты</th>
                  <th>Сумма</th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="tick in tickets">
                  <td>{{tick.ticketcount}}</td>
                  <td>{{tick.summ | fixedto }} {{tick.currency | currFilt}}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-4">
            <table style="border-right: none;" class="table table-striped">
              <thead>
                <tr>
                  <th>Касса на начало периода</th>
                  <th>Касса на конец периода</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{{data.strKgs | fixedto}} KGS</td>
                  <td>{{data.currKgs | fixedto }} KGS</td>
                </tr>
                <tr>
                  <td>{{data.strUsd | fixedto}} USD</td>
                  <td>{{data.currUsd | fixedto }} USD</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        </div>
    </div>
</div>
