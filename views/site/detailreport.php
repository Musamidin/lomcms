<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Сводный отчёт';
$this->params['breadcrumbs'][] = $this->title;
?>
<div ng-controller="AppCtrlDetailReport" class="site-detailreport">
    <div id="detailreport" class = "body-content-page">
        <div class="row">
          <div class="col-md-5">
            <input type="hidden" id="token" name="token" value="<?=md5(Yii::$app->session->getId().'opn');?>"/>
          </div>
          <div class="col-md-2">
              <select id="report-status" value="" name="reportstatus" class="form-control">
              	<option value="-1">Все</option>
                <option value="0">Выданные</option>
                <option value="1">Продленные</option>
                <option value="2">Выкупы</option>
                <option value="3">Просроченные</option>
                <option value="4">Продвинут срок</option>
                <option value="5">Реализованные</option>
              </select>
              <!--span class="input-group-addon input-sm"></span>
              <input type="text" class="form-control getbydatetime">
              <span class="input-group-addon rep-dpicker"><i class="glyphicon glyphicon-calendar"></i></span-->
          </div>
          <div class="col-md-1 text-center">
          <span class="excel">
	 					<a class="saveExcel" title="Выгрузить в Excel" href="/downloadconsreport" style="text-decoration: none;">
							<img src="/img/excel-icon.png">

						</a>
					</span>
        </div>
          <div class="col-md-4">
          		Количество: <span>{{totalmainlist}}</span>
          </div>
        </div>
        <br/>
        <div class="row report-box" id="report-grid">
          <div class="col-md-12" id="table2excel">
              <table class="mytformat tableshead table table-striped" border="0">
              		<thead>
                		<tr>
                			<th>Дата</th>
                  			<th>Пользователь</th>
                  			<th>Ф.И.О.</th>
                  			<th>Док. ID</th>
                  			<th>Орган выд. док.</th>
                  			<th>Дата выд. док.</th>
                  			<th>Адрес</th>
                  			<th>моб.номер</th>
                  			<th>Номер З.Б.</th>
                  			<th>Ссуда</th>
                  			<th>% - ставка</th>
                  			<th>Дата начало</th>
                  			<th>Дата выплаты</th>
                  			<th>Дата получения ссуды</th>
                  			<th>Дата посл. при.</th>
                  			<!--th>Статус</th-->
                  		</tr>
                  	</thead>			
                    <tbody>
                    <tr class="color{{ml.status}}" dir-paginate="ml in mainlistview | itemsPerPage: mainlistPerPage" total-items="totalmainlist" current-page="pagination.current" pagination-id="crv">
                        <td>{{ml.sysDate}}</td>
                        <td>{{ml.user}}</td>
                        <td>{{ml.fio}}</td>
                        <td>{{ml.passport_id}}</td>
                        <td>{{ml.passport_issued}}</td>
                        <td>{{ml.date_of_issue}}</td>
                        <td>{{ml.address}}</td>
                        <td>{{ml.phone}}</td>
                        <td>{{ml.ticket}}</td>
                        <td>{{ml.loan}}</td>
                        <td>{{ml.percents | fixedto }}</td>
                        <td>{{ml.dateStart}}</td>
                        <td>{{ml.dateEnd}}</td>
                        <td>{{ml.actionDate}}</td>
                        <td>{{ml.last_up_date}}</td>
                        <!--td>{{ml.status}}</td-->
                    </tbody>
              </table>
              <dir-pagination-controls pagination-id="crv" on-page-change="pageChanged(newPageNumber)"></dir-pagination-controls>
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
