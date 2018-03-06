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
          <div class="col-md-4"></div>
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
        <div class="row">
          <div class="col-md-12">
            <table style="display:none;" class="table report-table1">
              <thead>
                <tr>
                  <th>Штрих код</th>
                  <th>Пользователь</th>
                  <th>Реализатор</th>
                  <th>Наименование</th>
                  <th>Группа</th>
                  <th>Вставка</th>
                  <th>Проба</th>
                  <th>Поставка</th>
                  <th>Размер</th>
                  <th>Грамм</th>
                  <th>Курс</th>
                  <th>Цена покупки</th>
                  <th>Проданная цена</th>
                  <th>Прибыль</th>
                  <th>Комментарии</th>
                  <th>Дата продажи</th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="rep in report1">
                  <td>{{rep.bar_code}}</td>
                  <td>{{rep.users}}</td>
                  <td>{{rep.agent}}</td>
                  <td>{{rep.name}}</td>
                  <td>{{rep.groupby}}</td>
                  <td>{{rep.inser}}</td>
                  <td>{{rep.sample}}</td>
                  <td>{{rep.tdelivery}}</td>
                  <td>{{rep.size}}</td>
                  <td>{{rep.weight_grams | fixedto }}</td>
                  <td>{{rep.exchangerate | fixedto }}</td>
                  <td>{{rep.price_buy | fixedto }} {{rep.buy_currency | currFilt}}</td>
                  <td>{{rep.price_sold | fixedto }} {{rep.sold_currency | currFilt}}</td>
                  <td>{{rep.dohod | fixedto }}</td>
                  <td>{{rep.comment}}</td>
                  <td>{{rep.date_system}}</td>

                </tr>
                <tr>
                  <td colspan="9"></td>
                  <td>{{report1 | totalSumm:'weight_grams' }}</td>
                  <td></td>
                  <td>{{report1 | totalSumm:'price_buy' }}</td>
                  <td>{{report1 | totalSumm:'price_sold' }}</td>
                  <td>{{report1 | totalSumm:'dohod' }}</td>
                  <td colspan="2"></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-12">
            <table style="display:none;" class="table report-table2">
              <thead>
                <tr>
                  <th>Штрих код</th>
                  <th>Пользователь</th>
                  <th>Реализатор</th>
                  <th>Наименование</th>
                  <th>Группа</th>
                  <th>Вставка</th>
                  <th>Проба</th>
                  <th>Поставка</th>
                  <th>Размер</th>
                  <th>Грамм</th>
                  <th>Курс</th>
                  <th>Цена покупки</th>
                  <th>Проданная цена</th>
                  <th>Прибыль</th>
                  <th>Комментарии</th>
                  <th>Дата продажи</th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="rep in report2">
                  <td>{{rep.bar_code}}</td>
                  <td>{{rep.users}}</td>
                  <td>{{rep.agent}}</td>
                  <td>{{rep.name}}</td>
                  <td>{{rep.groupby}}</td>
                  <td>{{rep.inser}}</td>
                  <td>{{rep.sample}}</td>
                  <td>{{rep.tdelivery}}</td>
                  <td>{{rep.size}}</td>
                  <td>{{rep.weight_grams | fixedto }}</td>
                  <td>{{rep.exchangerate | fixedto }}</td>
                  <td>{{rep.price_buy | fixedto }} {{rep.buy_currency | currFilt}}</td>
                  <td>{{rep.price_sold | fixedto }} {{rep.sold_currency | currFilt}}</td>
                  <td>{{rep.dohod | fixedto }}</td>
                  <td>{{rep.comment}}</td>
                  <td>{{rep.date_system}}</td>

                </tr>
                <tr>
                  <td colspan="9"></td>
                  <td>{{report2 | totalSumm:'weight_grams' }}</td>
                  <td></td>
                  <td>{{report2 | totalSumm:'price_buy' }}</td>
                  <td>{{report2 | totalSumm:'price_sold' }}</td>
                  <td>{{report2 | totalSumm:'dohod' }}</td>
                  <td colspan="2"></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-12">
            <table style="display:none;" class="table report-table3">
              <thead>
                <tr>
                  <th>Штрих код</th>
                  <th>Пользователь</th>
                  <th>Реализатор</th>
                  <th>Наименование</th>
                  <th>Группа</th>
                  <th>Вставка</th>
                  <th>Проба</th>
                  <th>Поставка</th>
                  <th>Размер</th>
                  <th>Грамм</th>
                  <th>Курс</th>
                  <th>Цена покупки</th>
                  <th>Проданная цена</th>
                  <th>Прибыль</th>
                  <th>Комментарии</th>
                  <th>Дата продажи</th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="rep in report3">
                  <td>{{rep.bar_code}}</td>
                  <td>{{rep.users}}</td>
                  <td>{{rep.agent}}</td>
                  <td>{{rep.name}}</td>
                  <td>{{rep.groupby}}</td>
                  <td>{{rep.inser}}</td>
                  <td>{{rep.sample}}</td>
                  <td>{{rep.tdelivery}}</td>
                  <td>{{rep.size}}</td>
                  <td>{{rep.weight_grams | fixedto }}</td>
                  <td>{{rep.exchangerate | fixedto }}</td>
                  <td>{{rep.price_buy | fixedto }} {{rep.buy_currency | currFilt}}</td>
                  <td>{{rep.price_sold | fixedto }} {{rep.sold_currency | currFilt}}</td>
                  <td>{{rep.dohod | fixedto }}</td>
                  <td>{{rep.comment}}</td>
                  <td>{{rep.date_system}}</td>

                </tr>
                <tr>
                  <td colspan="9"></td>
                  <td>{{report3 | totalSumm:'weight_grams' }}</td>
                  <td></td>
                  <td>{{report3 | totalSumm:'price_buy' }}</td>
                  <td>{{report3 | totalSumm:'price_sold' }}</td>
                  <td>{{report3 | totalSumm:'dohod' }}</td>
                  <td colspan="2"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
    </div>
</div>
