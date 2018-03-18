<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Пользовательский отчёт';
$this->params['breadcrumbs'][] = $this->title;
?>
<div ng-controller="AppCtrlUserReport" class="site-userreport">
    <div id="userreport" class = "body-content-page">
        <div class="row">
          <div class="col-md-3">
            <input type="hidden" id="token" name="token" value="<?=md5(Yii::$app->session->getId().'opn');?>"/>
          </div>
          <div class="col-md-4">
            <div class="input-group date">
              <select id="typereport" value="" name="typereport" class="form-control">
                <option value="1">Ежедневный</option>
                <option value="2">Ежемесячный</option>
              </select>
              <span class="input-group-addon input-sm"></span>
              <input type="text" class="form-control getbydatetime">
              <span class="input-group-addon rep-dpicker"><i class="glyphicon glyphicon-calendar"></i></span>
            </div>
          </div>
          <div class="col-md-1 text-center">
          <span class="excel">
	 					<a class="saveExcel" title="Выгрузить в Excel" href="javascript:void(0);" style="text-decoration: none;">
							<img src="/img/excel-icon.png">
						</a>
					</span>
        </div>
          <div class="col-md-4">
            <div class="kassBadg">Остаток на начало:&nbsp;<span>{{kassa.strKgs | fixedto}}</span>&nbsp;KGS&nbsp;|&nbsp;<span>{{kassa.strUsd | fixedto}}</span>&nbsp;USD</div>
          </div>
        </div>
        <br/>
        <div class="row report-box">
          <div class="col-md-12" id="table2excel">
              <table class="mytformat tableshead table table-striped" border="0">
                    <tbody id="data_responsef">
                      <tr style="background: #d2d6de;">
                        <td colspan="3"><b>Остаток на начало:</b></td>
                        <td><b>{{kassa.strKgs | fixedto}}&nbsp;KGS</b></td>
                        <td><b>{{kassa.strUsd | fixedto}}&nbsp;USD</b></td>
                      </tr>
                    </tbody>
              </table>

              <table class="mytformat tableshead table table-striped" border="0">
                <tr class="noExl">
                 <thead class="dreport" style="display: table-header-group;">
                       <th class="rashod" colspan="5">ВЫДАЧА</th>
                 </thead>
                 <thead class="dreport" style="display: table-header-group;">
                       <th class="rashod">№</th>
                       <th class="rashod">Дата</th>
                       <th class="rashod">№ Зал.билета</th>
                       <th class="rashod">Ф.И.О.</th>
                       <th class="rashod">Сумма</th>
                 </thead>
                 </tr>
                 <tbody>
                   <tr ng-repeat="report1 in kgs.rep1">
                     <td>{{report1.num}}</td>
                     <td>{{report1.actionDate | formatDatetime}}</td>
                     <td>{{report1.ticket}}</td>
                     <td>{{report1.fio}}</td>
                     <td>{{report1.loan | fixedto }} {{report1.currency | currFilt }}</td>
                   </tr>
                 </tbody>
              </table>

              <table class="mytformat tableshead table table-striped" border="0">
                <tr class="noExl">
                 <thead class="dreport" style="display: table-header-group;">
                       <th class="k1-prihod" colspan="5">ВЫКУП</th>
                 </thead>
                 <thead class="dreport" style="display: table-header-group;">
                      <th class="k1-prihod">№</th>
                      <th class="k1-prihod">Дата</th>
                      <th class="k1-prihod">№ Зал.билета</th>
                      <th class="k1-prihod">Ф.И.О.</th>
                      <th class="k1-prihod">Сумма</th>
                 </thead>
                 </tr>
                 <tbody>
                   <tr ng-repeat="report2 in kgs.rep2">
                     <td>{{report2.num}}</td>
                     <td>{{report2.actionDate | formatDatetime}}</td>
                     <td>{{report2.ticket}}</td>
                     <td>{{report2.fio}}</td>
                     <td>{{report2.loan | fixedto }} {{report2.currency | currFilt }}</td>
                   </tr>
                 </tbody>
              </table>

              <table class="mytformat tableshead table table-striped" border="0">
                <tr class="noExl">
                 <thead class="dreport" style="display: table-header-group;">
                       <th class="k1-prihod" colspan="5">% - ПОГАШЕНИЯ</th>
                 </thead>
                 <thead class="dreport" style="display: table-header-group;">
                      <th class="k1-prihod">№</th>
                      <th class="k1-prihod">Дата</th>
                      <th class="k1-prihod">№ Зал.билета</th>
                      <th class="k1-prihod">Ф.И.О.</th>
                      <th class="k1-prihod">Сумма</th>
                 </thead>
                </tr>
                 <tbody>
                   <tr ng-repeat="report3 in kgs.rep3">
                     <td>{{report3.num}}</td>
                     <td>{{report3.actionDate | formatDatetime}}</td>
                     <td>{{report3.ticket}}</td>
                     <td>{{report3.fio}}</td>
                     <td>{{report3.comission | fixedto }} {{report3.currency | currFilt }}</td>
                   </tr>
                 </tbody>
              </table>

              <table class="mytformat tableshead table table-striped" border="0">
                <tr class="noExl">
                 <thead class="dreport" style="display: table-header-group;">
                       <th class="k1-prihod" colspan="5">ПРОДЛЕНИЕ</th>
                 </thead>
                 <thead class="dreport" style="display: table-header-group;">
                      <th class="k1-prihod">№</th>
                      <th class="k1-prihod">Дата</th>
                      <th class="k1-prihod">№ Зал.билета</th>
                      <th class="k1-prihod">Ф.И.О.</th>
                      <th class="k1-prihod">Сумма</th>
                 </thead>
               </tr>
                 <tbody>
                   <tr ng-repeat="report4 in kgs.rep4">
                     <td>{{report4.num}}</td>
                     <td>{{report4.actionDate | formatDatetime}}</td>
                     <td>{{report4.ticket}}</td>
                     <td>{{report4.fio}}</td>
                     <td>{{report4.comission | fixedto }} {{report4.currency | currFilt }}</td>
                   </tr>
                 </tbody>
              </table>

              <table class="mytformat tableshead table table-striped" border="0">
                <tr class="noExl">
                 <thead class="dreport" style="display: table-header-group;">
                       <th class="k1-prihod" colspan="5">ЧАСТИЧНОЕ ПОГОЩЕНИЕ</th>
                 </thead>
                 <thead class="dreport" style="display: table-header-group;">
                      <th class="k1-prihod">№</th>
                      <th class="k1-prihod">Дата</th>
                      <th class="k1-prihod">№ Зал.билета</th>
                      <th class="k1-prihod">Ф.И.О.</th>
                      <th class="k1-prihod">Сумма</th>
                 </thead>
               </tr>
                 <tbody>
                   <tr ng-repeat="report5 in kgs.rep5">
                     <td>{{report5.num}}</td>
                     <td>{{report5.actionDate | formatDatetime}}</td>
                     <td>{{report5.ticket}}</td>
                     <td>{{report5.fio}}</td>
                     <td>{{report5.part_of_loan | fixedto }} {{report5.currency | currFilt }}</td>
                   </tr>
                 </tbody>
              </table>

              <table class="mytformat tableshead table table-striped" border="0">
                <tr class="noExl">
                 <thead class="dreport" style="display: table-header-group;">
                       <th class="k1-prihod" colspan="5">ПРОЧИЕ ПРИХОДЫ</th>
                 </thead>
                 <thead class="dreport" style="display: table-header-group;">
                      <th class="k1-prihod">№</th>
                      <th class="k1-prihod">Дата</th>
                      <th class="k1-prihod">Статус</th>
                      <th class="k1-prihod">Коментарии</th>
                      <th class="k1-prihod">Сумма</th>
                 </thead>
               </tr>
                 <tbody>
                   <tr ng-repeat="report6 in kgs.rep6">
                     <td>{{report6.num}}</td>
                     <td>{{report6.actionDate | formatDatetime}}</td>
                     <td>{{report6.status}}</td>
                     <td>{{report6.comments}}</td>
                     <td>{{report6.summ | fixedto }} {{report6.currency | currFilt }}</td>
                   </tr>
                 </tbody>
              </table>

              <table class="mytformat tableshead table table-striped" border="0">
                <tr class="noExl">
                 <thead class="dreport" style="display: table-header-group;">
                       <th class="rashod" colspan="5">ПРОЧИЕ РАСХОДЫ</th>
                 </thead>
                 <thead class="dreport" style="display: table-header-group;">
                      <th class="rashod">№</th>
                      <th class="rashod">Дата</th>
                      <th class="rashod">Статус</th>
                      <th class="rashod">Коментарии</th>
                      <th class="rashod">Сумма</th>
                 </thead>
               </tr>
                 <tbody>
                   <tr ng-repeat="report7 in kgs.rep7">
                     <td>{{report7.num}}</td>
                     <td>{{report7.actionDate | formatDatetime}}</td>
                     <td>{{report7.status}}</td>
                     <td>{{report7.comments}}</td>
                     <td>{{report7.summ | fixedto }} {{report7.currency | currFilt }}</td>
                   </tr>
                 </tbody>
              </table>

              <hr id="ceps" style="color: rgb(0, 29, 255); border: 2px solid;">

              <table class="mytformat tableshead table table-striped" border="0">
                <tr class="noExl">
                 <thead class="dreport" style="display: table-header-group;">
                       <th class="rashod" colspan="5">ВЫДАЧА</th>
                 </thead>
                 <thead class="dreport" style="display: table-header-group;">
                       <th class="rashod">№</th>
                       <th class="rashod">Дата</th>
                       <th class="rashod">№ Зал.билета</th>
                       <th class="rashod">Ф.И.О.</th>
                       <th class="rashod">Сумма</th>
                 </thead>
               </tr>
                 <tbody>
                   <tr ng-repeat="report1 in usd.rep1">
                     <td>{{report1.num}}</td>
                     <td>{{report1.actionDate | formatDatetime}}</td>
                     <td>{{report1.ticket}}</td>
                     <td>{{report1.fio}}</td>
                     <td>{{report1.loan | fixedto }} {{report1.currency | currFilt }}</td>
                   </tr>
                 </tbody>
              </table>

              <table class="mytformat tableshead table table-striped" border="0">
                <tr class="noExl">
                 <thead class="dreport" style="display: table-header-group;">
                       <th class="k1-prihod" colspan="5">ВЫКУП</th>
                 </thead>
                 <thead class="dreport" style="display: table-header-group;">
                      <th class="k1-prihod">№</th>
                      <th class="k1-prihod">Дата</th>
                      <th class="k1-prihod">№ Зал.билета</th>
                      <th class="k1-prihod">Ф.И.О.</th>
                      <th class="k1-prihod">Сумма</th>
                 </thead>
               </tr>
                 <tbody>
                   <tr ng-repeat="report2 in usd.rep2">
                     <td>{{report2.num}}</td>
                     <td>{{report2.actionDate | formatDatetime}}</td>
                     <td>{{report2.ticket}}</td>
                     <td>{{report2.fio}}</td>
                     <td>{{report2.loan | fixedto }} {{report2.currency | currFilt }}</td>
                   </tr>
                 </tbody>
              </table>

              <table class="mytformat tableshead table table-striped" border="0">
                <tr class="noExl">
                 <thead class="dreport" style="display: table-header-group;">
                       <th class="k1-prihod" colspan="5">% - ПОГАШЕНИЯ</th>
                 </thead>
                 <thead class="dreport" style="display: table-header-group;">
                      <th class="k1-prihod">№</th>
                      <th class="k1-prihod">Дата</th>
                      <th class="k1-prihod">№ Зал.билета</th>
                      <th class="k1-prihod">Ф.И.О.</th>
                      <th class="k1-prihod">Сумма</th>
                 </thead>
               </tr>
                 <tbody>
                   <tr ng-repeat="report3 in usd.rep3">
                     <td>{{report3.num}}</td>
                     <td>{{report3.actionDate | formatDatetime}}</td>
                     <td>{{report3.ticket}}</td>
                     <td>{{report3.fio}}</td>
                     <td>{{report3.comission | fixedto }} {{report3.currency | currFilt }}</td>
                   </tr>
                 </tbody>
              </table>

              <table class="mytformat tableshead table table-striped" border="0">
                <tr class="noExl">
                 <thead class="dreport" style="display: table-header-group;">
                       <th class="k1-prihod" colspan="5">ПРОДЛЕНИЕ</th>
                 </thead>
                 <thead class="dreport" style="display: table-header-group;">
                      <th class="k1-prihod">№</th>
                      <th class="k1-prihod">Дата</th>
                      <th class="k1-prihod">№ Зал.билета</th>
                      <th class="k1-prihod">Ф.И.О.</th>
                      <th class="k1-prihod">Сумма</th>
                 </thead>
               </tr>
                 <tbody>
                   <tr ng-repeat="report4 in usd.rep4">
                     <td>{{report4.num}}</td>
                     <td>{{report4.actionDate | formatDatetime}}</td>
                     <td>{{report4.ticket}}</td>
                     <td>{{report4.fio}}</td>
                     <td>{{report4.comission | fixedto }} {{report4.currency | currFilt }}</td>
                   </tr>
                 </tbody>
              </table>

              <table class="mytformat tableshead table table-striped" border="0">
                <tr class="noExl">
                 <thead class="dreport" style="display: table-header-group;">
                       <th class="k1-prihod" colspan="5">ЧАСТИЧНОЕ ПОГОЩЕНИЕ</th>
                 </thead>
                 <thead class="dreport" style="display: table-header-group;">
                      <th class="k1-prihod">№</th>
                      <th class="k1-prihod">Дата</th>
                      <th class="k1-prihod">№ Зал.билета</th>
                      <th class="k1-prihod">Ф.И.О.</th>
                      <th class="k1-prihod">Сумма</th>
                 </thead>
               </tr>
                 <tbody>
                   <tr ng-repeat="report5 in usd.rep5">
                     <td>{{report5.num}}</td>
                     <td>{{report5.actionDate | formatDatetime}}</td>
                     <td>{{report5.ticket}}</td>
                     <td>{{report5.fio}}</td>
                     <td>{{report5.part_of_loan | fixedto }} {{report5.currency | currFilt }}</td>
                   </tr>
                 </tbody>
              </table>

              <table class="mytformat tableshead table table-striped" border="0">
                <tr class="noExl">
                 <thead class="dreport" style="display: table-header-group;">
                       <th class="k1-prihod" colspan="5">ПРОЧИЕ ПРИХОДЫ</th>
                 </thead>
                 <thead class="dreport" style="display: table-header-group;">
                      <th class="k1-prihod">№</th>
                      <th class="k1-prihod">Дата</th>
                      <th class="k1-prihod">Статус</th>
                      <th class="k1-prihod">Коментарии</th>
                      <th class="k1-prihod">Сумма</th>
                 </thead>
               </tr>
                 <tbody>
                   <tr ng-repeat="report6 in usd.rep6">
                     <td>{{report6.num}}</td>
                     <td>{{report6.actionDate | formatDatetime}}</td>
                     <td>{{report6.status}}</td>
                     <td>{{report6.comments}}</td>
                     <td>{{report6.summ | fixedto }} {{report6.currency | currFilt }}</td>
                   </tr>
                 </tbody>
              </table>

              <table class="mytformat tableshead table table-striped" border="0">
                <tr class="noExl">
                 <thead class="dreport" style="display: table-header-group;">
                       <th class="rashod" colspan="5">ПРОЧИЕ РАСХОДЫ</th>
                 </thead>
                 <thead class="dreport" style="display: table-header-group;">
                      <th class="rashod">№</th>
                      <th class="rashod">Дата</th>
                      <th class="rashod">Статус</th>
                      <th class="rashod">Коментарии</th>
                      <th class="rashod">Сумма</th>
                 </thead>
               </tr>
                 <tbody>
                   <tr ng-repeat="report7 in usd.rep7">
                     <td>{{report7.num}}</td>
                     <td>{{report7.actionDate | formatDatetime}}</td>
                     <td>{{report7.status}}</td>
                     <td>{{report7.comments}}</td>
                     <td>{{report7.summ | fixedto }} {{report7.currency | currFilt }}</td>
                   </tr>
                 </tbody>
              </table>

              <table class="mytformat tableshead table table-striped" border="0">
              	   	<tbody id="data_responsef">
                      <tr style="background: #f0eb4e;">
                        <td colspan="3"><b>ИТОГО ПО ЛОМБАРДУ:</b></td>
                        <td><b>{{kassa.currKgs | fixedto }}&nbsp;KGS</b></td>
                        <td><b>{{kassa.currUsd | fixedto }}&nbsp;USD</b></td>
                      </tr>
                    </tbody>
              </table>
          </div>
        </div>
    </div>
</div>
<style>
.table>thead>tr>th {
    line-height: 0.5;
}
.tableshead thead {
    cursor: pointer;
    background-color: sandybrown;
}
.rashod {
    background-color: #F5BFBF;
}
.k1-prihod {
    background-color: #6FC184;
}
.mytformat th, td {
    text-align: center;
}
.table {
    margin-bottom: 2px;
}
</style>
